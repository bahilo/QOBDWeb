<?php

namespace App\Services;

use App\Entity\Comment;
use App\Services\CatalogueHydrate;
use App\Repository\ContactRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\Common\Persistence\ObjectManager;


class OrderHydrate{

    protected $serializer; 
    protected $contactRepo;
    protected $catHydrate;
    protected $manager;

    public function __construct( SerializerInterface $serializer,
                                ContactRepository $contactRepo,
                                CatalogueHydrate $catHydrate,
                                ObjectManager $manager){

        $this->serializer = $serializer;
        $this->contactRepo = $contactRepo;
        $this->catHydrate = $catHydrate;
        $this->manager = $manager;
    }

    public function hydrateOrderDetail($orderDetails){

        $output = [];
        foreach( $orderDetails as $orderDetail){
            
            $item = $orderDetail->getItem();
            $tax = $orderDetail->getTax();

            if($item){
                $item = $this->catHydrate->hydrateItem([$item])[0];
                //dump($item); die();
                $orderDetail->setContentComment($item->getContentComment());
                $orderDetail->setItemRef($item->getRef());
                $orderDetail->setItemName($item->getName());
                $orderDetail->setItemPurchasePrice($item->getPurchasePrice());
                $sellPrice = $orderDetail->getItemSellPrice();
                if(empty($sellPrice) || $sellPrice == 0){
                    $orderDetail->setItemSellPrice($item->getSellPrice());
                    $this->manager->persist($orderDetail);                    
                }
                $orderDetail->setItemSellPriceTotal($item->getSellPrice() * $orderDetail->getQuantity());
                if($tax)
                    $orderDetail->setItemSellPriceVATTotal(($item->getSellPrice() * $orderDetail->getQuantity())*(1 + $Tax->getValue()));
                else
                    $orderDetail->setItemSellPriceVATTotal($item->getSellPrice() * $orderDetail->getQuantity());
                $orderDetail->setItemROIPercent((($item->getSellPrice() - $item->getPurchasePrice()) / $item->getSellPrice()) * 100);
                $orderDetail->setItemROICurrency(($item->getSellPrice() - $item->getPurchasePrice()) * $orderDetail->getQuantity() );
                
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

            array_push($output, $order);
        }

        return $output;
    }

    public function hydrateQuoteOrderRelationFromForm($order, $form)
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
        $item->setSellPrice($form['sell']);

        $orderDetail->setQuantity($form['quantity']);
        if (!empty($form['quantity_recieved']))
            $orderDetail->setQuantityRecieved($form['quantity_recieved']);

        return $orderDetail;
    }

}