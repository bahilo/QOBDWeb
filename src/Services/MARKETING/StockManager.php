<?php

namespace App\Services\MARKETING;

use App\Services\OrderManager;
use Doctrine\Common\Persistence\ObjectManager;


class StockManager{

    protected $manager;
    protected $orderManager;

    public function __construct(ObjectManager $manager, OrderManager $orderManager)
    {
        $this->manager = $manager;
        $this->orderManager = $orderManager;
    }

    /**
     * Vérification du stock, afin de déterminer si le produit peut-être vendu
     */
    public function checkOrderStock($orderDetails)
    {
        /** @var QuoteOrderDetail */
        foreach ($orderDetails as $orderDetail) {
            if ($orderDetail->getQuantity() + $orderDetail->getQuantityRecieved() <= $orderDetail->getQuantity()) {
                $orderDetail->setQuantityRecieved($orderDetail->getQuantity());
            } else {
                $orderDetail->setQuantityRecieved($orderDetail->getQuantity() - $orderDetail->getQuantityRecieved());
            }
            $this->manager->persist($orderDetail);
        }
        $this->manager->flush();
    }
}