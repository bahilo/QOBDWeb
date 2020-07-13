<?php

namespace App\Services;

use Exception;
use App\Entity\Item;
use App\Entity\Comment;
use App\Entity\QuoteOrder;
use App\Entity\QuoteOrderDetail;
use App\Repository\TaxRepository;
use App\Repository\BillRepository;
use App\Services\CatalogueHydrate;
use App\Repository\ContactRepository;
use App\Repository\CurrencyRepository;
use JMS\Serializer\SerializerInterface;
use App\Repository\QuoteOrderDetailRepository;
use Doctrine\Common\Persistence\ObjectManager;


class OrderHydrate{

    protected $serializer; 
    protected $contactRepo;
    protected $billRepo;
    protected $currencyRepo;
    protected $taxRepo;
    protected $orderDetailRepo;
    protected $catHydrate;
    protected $manager;
    protected $webApi;
    protected $errorHandler;

    public function __construct( SerializerInterface $serializer,
                                ContactRepository $contactRepo,
                                CurrencyRepository $currencyRepo,  
                                ApiManager $webApi,
                                TaxRepository $taxRepo,
                                QuoteOrderDetailRepository $orderDetailRepo,
                                BillRepository $billRepo,
                                CatalogueHydrate $catHydrate,
                                ErrorHandler $errorHandler,
                                ObjectManager $manager){

        $this->serializer = $serializer;
        $this->webApi = $webApi;
        $this->contactRepo = $contactRepo;
        $this->currencyRepo = $currencyRepo;
        $this->taxRepo = $taxRepo;
        $this->billRepo = $billRepo;
        $this->orderDetailRepo = $orderDetailRepo;
        $this->catHydrate = $catHydrate;
        $this->manager = $manager;
        $this->errorHandler = $errorHandler;
    }

    public function hydrateOrderDetail(Array $orderDetails){

        $output = [];
        foreach ($orderDetails as $orderDetail) {
            
            $item = $orderDetail->getItem();
            
            if ($item) {
                /** @var Item */
                $item = $this->catHydrate->hydrateItem([$item])[0];
                
                $qt = $orderDetail->getQuantity();
                $pa = !empty($item->getPurchasePrice()) ? $item->getPurchasePrice() : 0 ;//$orderDetail->getItemPurchasePrice();
                $pv = !empty($item->getSellPrice()) ? $item->getSellPrice() : 0;
                
                // $orderDetail->setItemRef($item->getRef());
                // $orderDetail->setItemName($item->getName());

                if (empty($orderDetail->getItemSellPrice())) {
                    $orderDetail->setItemSellPrice($pv);
                }

                if (empty($orderDetail->getItemPurchasePrice())) {
                    $orderDetail->setItemPurchasePrice($pa);
                }

                $this->manager->persist($orderDetail);
            }
            $this->manager->flush();
            array_push($output, $orderDetail);                
        }
        
        return $output;
    }

    public function hydrateQuoteOrder($orders){
        $output = [];     
        
        foreach($orders as $order){
            $agent = $order->getAgent();
            $client = $order->getClient();

            if($agent){
                $order->setAgentFirstName($agent->getFirstName());
                $order->setAgentLastName($agent->getLastName());
            }

            if($client)
                $order->setClientCompanyName($client->getCompanyName());
            
            $order->setCreatedAtToString(date_format($order->getCreatedAt(),"d/m/Y"));

            $output[] = $order;
        }

        return $output;
    }

    public function hydrateQuantityDelivery($quantityDeliveries, QuoteOrder $order){
        $output = [];

        foreach ($quantityDeliveries as $qtDelivery) {

            $item = $qtDelivery->getOrderDetail()->getItem();
            
            $tax = empty($qtDelivery->getOrderDetail()->getTax()) || empty($qtDelivery->getOrderDetail()->getTax()->getValue()) ? $order->getTax() : $qtDelivery->getOrderDetail()->getTax();

            $qtDelivery->setItemRef($qtDelivery->getOrderDetail()->getItem()->getRef());
            $qtDelivery->setItemName($qtDelivery->getOrderDetail()->getItem()->getName());
            $qtDelivery->setItemSellPriceVATTotal(($qtDelivery->getQuantity() * $qtDelivery->getOrderDetail()->getItemSellPrice()) * (1 + $tax->getValue()/100));
            $qtDelivery->setItemROIPercent(($qtDelivery->getOrderDetail()->getItemSellPrice() - $item->getPurchasePrice()) / $qtDelivery->getOrderDetail()->getItemSellPrice() * 100);

            array_push($output, $qtDelivery);
        }

        return $output;
    }

    public function hydrateBill($bills, QuoteOrder $order){
        $output = [];

        foreach ($bills as $bill) {
            $total = 0;
            foreach ($bill->getQuantityDeliveries() as $qtDelivery) {
                $tax = empty($qtDelivery->getOrderDetail()->getTax()) || empty($qtDelivery->getOrderDetail()->getTax()->getValue()) ? $order->getTax() : $qtDelivery->getOrderDetail()->getTax();

                $total += ($qtDelivery->getQuantity() * $qtDelivery->getOrderDetail()->getItemSellPrice()) * (1 + $tax->getValue() / 100);
            }
            $currency = $order->getCurrency();
            if(!empty($currency)){
                $total = $total * $this->webApi->execCurrencyRequest($currency->getSymbol());
            }
            
            $bill->setItemSellPriceVATTotal(round($total, 2));

            if(empty($bill->getPayMode())){
                $bill->setPayMode("");
            }

            $bill->setBillPublicComment("");
            $bill->setBillPrivateComment("");

            if(!empty($bill->getPublicComment()))
                $bill->setBillPublicComment($bill->getPublicComment()->getContent());
            if (!empty($bill->getPrivateComment()))
                $bill->setBillPrivateComment($bill->getPrivateComment()->getContent());

            array_push($output, $bill);
        }

        return $output;
    }

    /**
     * Polulate order from form
     */
    public function hydrateQuoteOrderRelationFromForm(QuoteOrder $order, $form)
    {
        $privateComment = $order->getPrivateComment();
        $adminComment = $order->getAdminComment();
        $publicComment = $order->getPublicComment();
        $contact = $this->contactRepo->find($form['contact']);

       
        if(isset($form['commentaire'])){

            $privateComment = $order->getPrivateComment();
            if (!$privateComment) {
                $privateComment = new Comment();
                $privateComment->setCreateAt(new \DateTime());
            }
            $privateComment->setContent($form['commentaire']['prive']);
            
            $adminComment = $order->getAdminComment();
            if (!$adminComment) {
                $adminComment = new Comment();
                $adminComment->setCreateAt(new \DateTime());
            }
            $adminComment->setContent($form['commentaire']['admin']);
    
            $publicComment = $order->getPublicComment();
            if (!$publicComment) {
                $publicComment = new Comment();
                $publicComment->setCreateAt(new \DateTime());
            }
            $publicComment->setContent($form['commentaire']['public']);
        }


        if (isset($form['setting']['devis']['type'])) {
            if ($form['setting']['devis']['type'] == 'devis')
                $order->setIsQuote(true);
            else
                $order->setIsQuote(false);
        }

        if (!empty($form['setting']['devis']['duree']))
            $order->setValidityPeriode($form['setting']['devis']['duree']);

        if (isset($form['setting']['devis']['ref_visible']))
            $order->setIsRefVisible(true);
        else
            $order->setIsRefVisible(false); 

        if(!empty($form['currency'])){
            $order->setCurrency($this->currencyRepo->find($form['currency']));
        }

        if(!empty($form['tax'])){
            $order->setTax($this->taxRepo->find($form['tax']));
        }

        //---[ saving items table data ]
        if(!empty($form['tab']['items'])){
            foreach ($form['tab']['items'] as $key => $val) {
                $orderDetail = $this->hydrateQuoteOrderDetailRelationFromForm($this->orderDetailRepo->find($key), $val);
                
                $this->manager->persist($orderDetail->getItem());
                $this->manager->persist($orderDetail);
            }
        }

        //---[ saving bill table data ]
        if(!empty($form['tab']['bill'])){
            foreach ($form['tab']['bill'] as $key => $val) {
                $bill = $this->billRepo->find($key);
                if (!empty($bill)) {

                    $order->setIsRefVisible(false);
                    if(!empty($val['ref_visible']))
                        $order->setIsRefVisible(true);

                    if (!empty($val['pay_mode']))
                        $bill->setPayMode($val['pay_mode']);
                    if (!empty($val['payed_amount']))
                        $bill->setPayReceived($val['payed_amount']);
                    if (!empty($val['pay_date']))
                        $bill->setPayedAt(new \Datetime($val['pay_date']));

                    if (!empty($val['comment_private'])) {

                        $privCom = $bill->getPrivateComment();
                        if (empty($privCom))
                            $privCom = new Comment();

                        $privCom->setCreateAt(new \DateTime());
                        $privCom->setContent($val['comment_private']);
                        $bill->setPrivateComment($privCom);

                        $this->manager->persist($privCom);
                    }

                    if (!empty($val['comment_public'])) {

                        $publicCom = $bill->getPublicComment();
                        if (empty($publicCom))
                            $publicCom = new Comment();

                        $publicCom->setCreateAt(new \DateTime());
                        $publicCom->setContent($val['comment_public']);
                        $bill->setPublicComment($publicCom);

                        $this->manager->persist($publicCom);
                    }

                    $this->manager->persist($bill);
                }
            }
        }

        $order->setPrivateComment($privateComment);
        $order->setPublicComment($publicComment);
        $order->setAdminComment($adminComment);
        $order->setContact($contact);

        return $order;
    }

    public function hydrateQuoteOrderDetailRelationFromForm(QuoteOrderDetail $orderDetail, $form)
    {
        
        $item = $orderDetail->getItem();
        $comment = $item->getComment();

        if (!empty($form['comment'])) {
            if (!$comment) {
                $comment = new Comment();
                $comment->setCreateAt(new \DateTime());
            }
            $comment->setContent($form['comment']);
            $item->setComment($comment);
        }

        $orderDetail->setItemSellPrice($form['sell']);
        $orderDetail->setQuantity($form['quantity']);
        if (!empty($form['quantity_recieved']) &&  $form['quantity_recieved'] > 0){
            //dump($form);
            if(!empty($orderDetail->getQuantityRecieved())){
                // dump("(".$form['quantity_recieved']." + ".$orderDetail->getQuantityRecieved()." <= ".$form['quantity'].")");
                // die();
                if ($form['quantity_recieved'] + $orderDetail->getQuantityRecieved() <= $form['quantity']) {
                    $orderDetail->setQuantityRecieved($form['quantity_recieved']);
                } else {
                    $orderDetail->setQuantityRecieved($form['quantity'] - $orderDetail->getQuantityRecieved());
                }
            }
            elseif($form['quantity_recieved'] <= $form['quantity']){
                $orderDetail->setQuantityRecieved($form['quantity_recieved']);
            }
            else{
                $orderDetail->setQuantityRecieved($form['quantity']);
            }           
            
        }

        //dump($item);
        $this->manager->persist($item);

        $orderDetail->setItem($item);

        return $orderDetail;
    }

}