<?php

namespace App\Services;

use App\Entity\Currency;
use Exception;
use App\Entity\QuoteOrder;
use Psr\Log\LoggerInterface;
use App\Services\OrderHydrate;
use App\Entity\QuoteOrderDetail;
use App\Repository\BillRepository;
use App\Repository\OrderStatusRepository;
use App\Repository\QuoteOrderRepository;
use App\Repository\QuoteOrderDetailRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderManager{

    protected $token;
    protected $manager;
    protected $container;
    protected $orderRepo;
    protected $orderHydrate;
    protected $orderDetailRepo;
    protected $webApi;
    protected $logger;
    protected $errorHandler;
    protected $statusRepo;
    protected $billRepo;
    protected $utility;

    public function __construct(QuoteOrderRepository $orderRepo, 
                                QuoteOrderDetailRepository $orderDetailRepo,  
                                ApiManager $webApi,
                                ObjectManager $manager, 
                                LoggerInterface $logger,
                                ErrorHandler $errorHandler,
                                OrderHydrate $orderHydrate, 
                                OrderStatusRepository $statusRepo, 
                                BillRepository $billRepo, 
                                Utility $utility, 
                                TokenStorageInterface $tokenStorage, 
                                ContainerInterface $container)
    {
        $this->orderRepo = $orderRepo;
        $this->token = $tokenStorage->getToken();
        $this->orderHydrate = $orderHydrate;
        $this->logger = $logger;
        $this->webApi = $webApi;
        $this->orderDetailRepo = $orderDetailRepo;
        $this->manager = $manager;
        $this->container = $container;
        $this->errorHandler = $errorHandler;
        $this->statusRepo = $statusRepo;
        $this->billRepo = $billRepo;
        $this->utility = $utility;
    }

    public function getHydrater(){
        return $this->orderHydrate;
    }

    public function getCommandeInfo(array $orderDetails, QuoteOrder $order){
        $output = $this->getOrderDetailStats($orderDetails, $order);
        return $output;
    }

    /**
     * Calcul des stats de la commande
     */
    public function getOrderDetailStats(array $orderDetails, QuoteOrder $order){
        $output = [
            'total_PA' => 0,
            'total_PV' => 0,
            'total_HT' => 0,
            'total_TTC' => 0,
            'marge_perc' => 0,
            'marge_amount' => 0,
            'VAT_amount' => 0,
            'VAT' => 0,
        ];

        if(count($orderDetails) > 0){

           $currency = $order->getCurrency();
            try{
                /** @var QuoteOrderDetail */
                foreach ($orderDetails as $orderDetail) {
                   
                    $qt = $orderDetail->getQuantity();
                    $pa = $orderDetail->getItem()->getPurchasePrice();
                    $pv = ($orderDetail->getItemSellPrice() > 0) ? $orderDetail->getItemSellPrice() : $orderDetail->getItem()->getSellPrice();

                    if ($pv == 0)
                        throw new Exception('Votre liste de produits contient un produit avec un prix de vente à 0!');
                   
                    $total_HT = $pv * $qt;
                    $marge_perc = $pv > 0 ?  ($pv - $pa) / $pv * 100 : 0;
                    $marge_amount = $pv - $pa;

                    $output['total_PA'] += $pa;
                    $output['total_PV'] += $pv;
                    $output['total_HT'] += $total_HT;
                    $output['marge_perc'] += $marge_perc;
                    $output['marge_amount'] += $marge_amount;

                    $tax = empty($orderDetail->getTax()) ? $order->getTax() : $orderDetail->getTax();

                    if (!empty($tax)) {
                        $bTvaMarge = $tax->getIsTVAMarge();
                        $tva = $tax->getValue();
                        
                        $output['VAT'] = $tva;
                        $output['VAT_amount'] += ($bTvaMarge ? $marge_amount : $pv) * $qt * $tva / 100;
                        $output['total_TTC'] += $bTvaMarge ? ($pv + $marge_amount * $tva / 100) * $qt : $pv * (1 + $tva / 100) * $qt;
                    } else {
                        $output['total_TTC'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity();
                    }
                }

                if (!empty($currency)) {
                    $currencyValue = $this->webApi->execCurrencyRequest($currency->getSymbol());
                    $output['total_HT'] = round($output['total_HT'] * $currencyValue, 2);
                    $output['total_TTC'] = round($output['total_TTC'] * $currencyValue, 2);
                    $output['marge_perc'] = round((($output['total_PV'] - $output['total_PA']) / $output['total_PV'])*100, 2);
                    $output['marge_amount'] = round(($output['total_PV'] - $output['total_PA']) * $currencyValue, 2);
                    $output['VAT_amount'] = round($output['VAT_amount'] * $currencyValue, 2);
                }                

            }catch(Exception $ex){
                $this->errorHandler->error("Une erreur s'est produite durant l'exécution de votre requête!");
                $this->errorHandler->error($ex->getMessage()); 
            }
        }    
        
        return $output;
    }

    /**
     * alimentation de la commande avec les données stats + relation
     */
    public function hydrateOrderDetailStats(Array $orderDetails)
    {
        $output = [];
        foreach ($this->orderHydrate->hydrateOrderDetail($orderDetails) as $orderDetail) {
            /** @var QuoteOrder */
            $order = $orderDetail->getQuoteOrder();
            $stats = $this->getOrderDetailStats([$orderDetail], $order);
            $orderDetail->setItemSellPriceTotal($stats['total_HT']);
            $orderDetail->setItemSellPriceVATTotal($stats['total_TTC']);

            $orderDetail->setItemROIPercent($stats['marge_perc']);
            $orderDetail->setItemROICurrency($stats['marge_amount']);

            $this->manager->persist($orderDetail);
            array_push($output, $orderDetail);
        }
        $this->manager->flush();
        return $output;
    }

    /**
     * Facture la commande
     */
    function setOrderbilled($order){
        dump('setOrderbilled');
        $TotoalOrderBill = $this->orderRepo->findScalarOrderBill($order);
        //dump($TotoalOrderBill);die();

        //---- recherche le montant de la facture
        $bills = $this->utility->getDistinct($this->billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']));
        $totalBilled = 0;
        foreach ($bills as $bill) {
            $totalBilled += $bill->getPay();
        }

        //--- facturer et expedier la commande
        if (!empty($TotoalOrderBill) && $TotoalOrderBill > 0 && $TotoalOrderBill == $totalBilled) {
           
            $status = null;
            if ($order->getStatus()->getName() == "STATUS_ORDER")
                $status = $this->statusRepo->findOneBy(['Name' => 'STATUS_BILL']);
            else if ($order->getStatus()->getName() == "STATUS_REFUND")
                $status = $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDBILL']);

            if (!empty($status)) {
                $order->setStatus($status);
            }
        }
        return $order;
    }

    /**
     * Vérification du stock, afin de déterminer si le produit peut-être vendu
     */
    public function checkOrderStock($orderDetails){
        /** @var QuoteOrderDetail */
        foreach ($orderDetails as $orderDetail) {
            if (!empty($orderDetail->getItem()->getStock() >= $orderDetail->getQuantity())) {
                if ($orderDetail->getQuantity() + $orderDetail->getQuantityRecieved() <= $orderDetail->getQuantity()) {
                    $orderDetail->setQuantityRecieved($orderDetail->getQuantity());
                } else {
                    $orderDetail->setQuantityRecieved($orderDetail->getQuantity() - $orderDetail->getQuantityRecieved());
                }
                $this->manager->persist($orderDetail);
            }        
        }
        $this->manager->flush();
    }

}