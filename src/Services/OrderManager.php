<?php

namespace App\Services;

use App\Entity\QuoteOrder;
use App\Repository\QuoteOrderRepository;
use App\Repository\QuoteOrderDetailRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OrderManager{

    protected $manager;
    protected $container;
    protected $orderRepo;
    protected $orderDetailRepo;
    protected $webApi;

    public function __construct(QuoteOrderRepository $orderRepo, 
                                QuoteOrderDetailRepository $orderDetailRepo,  
                                ApiManager $webApi,
                                ObjectManager $manager,  
                                ContainerInterface $container)
    {
        $this->orderRepo = $orderRepo;
        $this->webApi = $webApi;
        $this->orderDetailRepo = $orderDetailRepo;
        $this->manager = $manager;
        $this->container = $container;
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

        foreach ($orderDetails as $orderDetail) {

            
            $output['total_HT'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity() * $currencyValue ;
            $output['marge_perc'] += (($orderDetail->getItemSellPrice() - $orderDetail->getItemPurchasePrice()) / $orderDetail->getItemSellPrice()) * 100;
            $output['marge_amount'] += ($orderDetail->getItemSellPrice() - $orderDetail->getItemPurchasePrice()) * $currencyValue;
            
            $tax = empty($orderDetail->getTax()) ? $order->getTax() : $orderDetail->getTax();

            if(!empty($tax)){
                $output['VAT'] = $tax->getValue();
                $output['VAT_amount'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity() * $currencyValue *  $tax->getValue() / 100;
                $output['total_TTC'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity() * $currencyValue  * (1 + $tax->getValue() / 100);
            }
            else{
                $output['total_TTC'] += $orderDetail->getItemSellPrice() * $orderDetail->getQuantity() * $currencyValue;
            }            
        }
        $output['total_HT'] = round($output['total_HT'] , 2);
        $output['total_TTC'] = round($output['total_TTC'] , 2);
        $output['marge_perc'] = round($output['marge_perc'] , 2);
        $output['marge_amount'] = round($output['marge_amount'] , 2);
        $output['VAT_amount'] = round($output['VAT_amount'] , 2);
        return $output;
    }

}