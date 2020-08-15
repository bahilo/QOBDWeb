<?php

namespace App\Services\IMMOBILIER;

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
        $this->orderManager->checkOrderStock($orderDetails);
    }
}