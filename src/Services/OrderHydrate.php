<?php

namespace App\Services;

use JMS\Serializer\SerializerInterface;


class OrderHydrate{

    protected $serializer; 

    public function __construct( SerializerInterface $serializer){

        $this->serializer = $serializer;
    }

    public function hydrateOrderDetail($orderDetails){

        $output = [];
        foreach( $orderDetails as $orderDetail){
            
            $item = $orderDetail->getItem();
            $tax = $orderDetail->getTax();

            if($item){
                $orderDetail->setItemRef($item->getRef());
                $orderDetail->setItemName($item->getName());
                $orderDetail->setItemPurchasePrice($item->getPurchasePrice());
                $orderDetail->setItemSellPrice($item->getSellPrice());
                $orderDetail->setItemSellPriceTotal($item->getSellPrice() * $orderDetail->getQuantity());
                if($tax)
                    $orderDetail->setItemSellPriceVATTotal(($item->getSellPrice() * $orderDetail->getQuantity())*(1 + $Tax->getValue()));
                else
                    $orderDetail->setItemSellPriceVATTotal($item->getSellPrice() * $orderDetail->getQuantity());
                $orderDetail->setItemROIPercent((($item->getSellPrice() - $item->getPurchasePrice()) / $item->getSellPrice()) * 100);
                $orderDetail->setItemROICurrency(($item->getSellPrice() - $item->getPurchasePrice()) * $orderDetail->getQuantity() );
                
            }
            
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


}