<?php

namespace App\Services;

use App\Entity\QuoteOrder;
use Psr\Log\LoggerInterface;
use App\Services\OrderHydrate;
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

    public function __construct(QuoteOrderRepository $orderRepo, 
                                QuoteOrderDetailRepository $orderDetailRepo,  
                                ApiManager $webApi,
                                ObjectManager $manager, 
                                LoggerInterface $logger,
                                OrderHydrate $orderHydrate, 
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
    }

    public function getHydrater(){
        return $this->orderHydrate;
    }

    public function getCommandeInfo($orderDetails, QuoteOrder $order){
        $output = [
            'total_HT' => 0,
            'total_TTC' => 0,
            'marge_perc' => 0,
            'marge_amount' => 0,
            'VAT_amount' => 0,
            'VAT' => 0,
        ];
        $currency = $order->getCurrency();
        $currencyValue = 1;
        if(!empty($currency))
            $currencyValue = $this->webApi->execCurrencyRequest($currency->getSymbol());
        else
            $this->loggCommandeInfo($order, "La commande ne possède pas de Devis");

        foreach ($orderDetails as $orderDetail) {

            // $orderDetail->setItemSellPrice($orderDetail->getItemSellPrice());
            // $orderDetail->setItemPurchasePrice($orderDetail->getItemPurchasePrice());
            
            $output['total_HT'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity();
            $output['marge_perc'] += (($orderDetail->getItemSellPrice() - $orderDetail->getItemPurchasePrice()) / $orderDetail->getItemSellPrice()) * 100;
            $output['marge_amount'] += ($orderDetail->getItemSellPrice() - $orderDetail->getItemPurchasePrice());
            
            $tax = empty($orderDetail->getTax()) ? $order->getTax() : $orderDetail->getTax();

            if(!empty($tax)){
                $output['VAT'] = $tax->getValue();
                $output['VAT_amount'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity() *  $tax->getValue() / 100;
                $output['total_TTC'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity() * (1 + $tax->getValue() / 100);
            }
            else{
                $output['total_TTC'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity();
            }            
        }
        $output['total_HT'] = round($output['total_HT'] , 2) * $currencyValue;
        $output['total_TTC'] = round($output['total_TTC'] , 2) * $currencyValue;
        $output['marge_perc'] = round($output['marge_perc'] , 2) * $currencyValue;
        $output['marge_amount'] = round($output['marge_amount'] , 2) * $currencyValue;
        $output['VAT_amount'] = round($output['VAT_amount'] , 2) * $currencyValue;
        return $output;
    }

    public function loggCommandeInfo(QuoteOrder $order, $message){
        $this->logger->info($this->token->getUser()->getUsername() . "(id agent = " . $this->token->getUser()->getId() . ") - commande n°" . $order->getId(), [
            'detail' => $message
        ]);
    }

    public function loggCommandeAccessInfo(QuoteOrder $order){
        $this->logger->info($this->token->getUser()->getUsername() . "(id agent = " . $this->token->getUser()->getId() . ") - Accède à la commande n°" . $order->getId());
    }

    public function loggCommandeRegisterInfo(QuoteOrder $order){
        $this->logger->info($this->token->getUser()->getUsername() . "(id agent = " . $this->token->getUser()->getId() . ") - vient de créer la commande n°" . $order->getId());
    }

    public function loggCommandeSaveInfo(QuoteOrder $order, $data){
        $this->logger->info($this->token->getUser()->getUsername() . "(id agent = " . $this->token->getUser()->getId() . ") - vient de modifier la commande n°" . $order->getId(),[
            'détail' => print_r($data, true)
        ]);
    }

    public function loggCommandeErr(QuoteOrder $order, $message){
        $this->logger->error($this->token->getUser()->getUsername() . "(id agent = " . $this->token->getUser()->getId() . ") - une erreur s'est produite sur la commande n°" . $order->getId(),[
            'détail' => $message
        ]);
    }

    public function loggCommandeCritical(QuoteOrder $order, $message){
        $this->logger->critical($this->token->getUser()->getUsername() . "(id agent = " . $this->token->getUser()->getId() . ") - une erreur critique s'est produite sur la commande n°" . $order->getId(),[
            'détail' => $message
        ]);
    }

}