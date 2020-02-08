<?php

namespace App\Services;

use App\Entity\Comment;
use App\Entity\QuoteOrder;
use App\Repository\TaxRepository;
use App\Services\CatalogueHydrate;
use App\Repository\ContactRepository;
use App\Repository\CurrencyRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\Common\Persistence\ObjectManager;


class OrderHydrate{

    protected $serializer; 
    protected $contactRepo;
    protected $currencyRepo;
    protected $taxRepo;
    protected $catHydrate;
    protected $manager;

    public function __construct( SerializerInterface $serializer,
                                ContactRepository $contactRepo,
                                CurrencyRepository $currencyRepo,
                                TaxRepository $taxRepo,
                                CatalogueHydrate $catHydrate,
                                ObjectManager $manager){

        $this->serializer = $serializer;
        $this->contactRepo = $contactRepo;
        $this->currencyRepo = $currencyRepo;
        $this->taxRepo = $taxRepo;
        $this->catHydrate = $catHydrate;
        $this->manager = $manager;
    }

    public function hydrateOrderDetail($orderDetails, QuoteOrder $order){

        $output = [];
        foreach( $orderDetails as $orderDetail){
            
            $item = $orderDetail->getItem();
            $tax = empty($orderDetail->getTax()) || empty($orderDetail->getTax()->getValue()) ? $order->getTax() : $orderDetail->getTax();
            // dump($orderDetail->getTax()); 
            // dump($order->getTax()); 
            // die();
            if($item){
                $item = $this->catHydrate->hydrateItem([$item])[0];
                
                $orderDetail->setContentComment($item->getContentComment());
                $orderDetail->setItemRef($item->getRef());
                $orderDetail->setItemName($item->getName());
                $orderDetail->setItemPurchasePrice($item->getPurchasePrice());

                $sellPrice = $orderDetail->getItemSellPrice();
                $purchasePrice = $orderDetail->getItemPurchasePrice();

                if(empty($sellPrice) || $sellPrice == 0){
                    $orderDetail->setItemSellPrice($item->getSellPrice());
                    $this->manager->persist($orderDetail);                    
                }

                if(empty($purchasePrice) || $purchasePrice == 0){
                    $orderDetail->setItemPurchasePrice($item->getPurchasePrice());
                    $this->manager->persist($orderDetail);                    
                }

                //dump($orderDetail->getItemSellPrice() * $orderDetail->getQuantity());
                //dump($orderDetail);
                //die();
                $orderDetail->setItemSellPriceTotal($orderDetail->getItemSellPrice() * $orderDetail->getQuantity());
                if($tax)
                    $orderDetail->setItemSellPriceVATTotal(($orderDetail->getItemSellPrice() * $orderDetail->getQuantity())*(1 + $tax->getValue() / 100));
                else
                    $orderDetail->setItemSellPriceVATTotal($orderDetail->getItemSellPrice() * $orderDetail->getQuantity());
                $orderDetail->setItemROIPercent((($orderDetail->getItemSellPrice() - $orderDetail->getItemPurchasePrice()) / $orderDetail->getItemSellPrice()) * 100);
                $orderDetail->setItemROICurrency(($orderDetail->getItemSellPrice() - $orderDetail->getItemPurchasePrice()) * $orderDetail->getQuantity() );
                
            }
            $this->manager->flush();
            array_push($output, $orderDetail);
        }
        //dump($output[0]->getTax()); 
        //dump($output); 
        //die();
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

    public function hydrateQuoteOrderRelationFromForm(QuoteOrder $order, $form)
    {

        $agent = $order->getAgent();
        $client = $order->getClient();
        $privateComment = $order->getPrivateComment();
        $adminComment = $order->getAdminComment();
        $publicComment = $order->getPublicComment();
        $contact = $this->contactRepo->find($form['contact']);

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

        $order->setPrivateComment($privateComment);
        $order->setPublicComment($publicComment);
        $order->setAdminComment($adminComment);
        $order->setContact($contact);

        return $order;
    }

    public function hydrateQuoteOrderDetailRelationFromForm($orderDetail, $form)
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

        $item->setPurchasePrice($form['purchase']);

        $orderDetail->setItemSellPrice($form['sell']);
        $orderDetail->setQuantity($form['quantity']);
        if (!empty($form['quantity_recieved']) && $form['quantity_recieved'] <= $form['quantity'])
            $orderDetail->setQuantityRecieved($form['quantity_recieved']);

        return $orderDetail;
    }

}