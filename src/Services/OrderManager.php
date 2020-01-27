<?php

namespace App\Services;

use App\Repository\QuoteOrderRepository;
use App\Repository\QuoteOrderDetailRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OrderManager{

    protected $manager;
    protected $container;
    protected $orderRepo;
    protected $orderDetailRepo;

    public function __construct(QuoteOrderRepository $orderRepo, 
                                QuoteOrderDetailRepository $orderDetailRepo, 
                                ObjectManager $manager,  
                                ContainerInterface $container)
    {
        $this->orderRepo = $orderRepo;
        $this->orderDetailRepo = $orderDetailRepo;
        $this->manager = $manager;
        $this->container = $container;
    }

    function getCommandeInfo($orderDetails){
        $output = [
            'total_HT' => 0,
            'total_TTC' => 0,
            'marge_perc' => 0,
            'marge_amount' => 0,
            'VAT_amount' => 0,
            'VAT' => 0,
        ];
        foreach ($orderDetails as $orderDetail) {

            $output['total_HT'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity();
            $output['marge_perc'] += (($orderDetail->getItemSellPrice() - $orderDetail->getItemPurchasePrice()) / $orderDetail->getItemSellPrice()) * 100;
            $output['marge_amount'] += $orderDetail->getItemSellPrice() - $orderDetail->getItemPurchasePrice();
            
            $tax = $orderDetail->getTax();
            if(!empty($tax)){
                $output['VAT'] = $tax->getValue();
                $output['VAT_amount'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity() *  $tax->getValue() / 100;
                $output['total_TTC'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity()  * (1 + $tax->getValue() / 100);
            }
            else{
                $output['total_TTC'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity();
            }            
        }
        return $output;
    }

}