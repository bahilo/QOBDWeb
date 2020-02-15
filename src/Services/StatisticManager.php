<?php

namespace App\Services;

use App\Entity\QuoteOrder;
use App\Repository\OrderStatusRepository;
use App\Repository\QuoteOrderRepository;
use App\Repository\QuoteOrderDetailRepository;

class StatisticManager{

    protected $utility;
    protected $serializer;
    protected $orderManager;
    protected $orderHydrate;
    protected $orderDetailRepo;
    protected $statusRepo;

    private $moisTraduction;

    public function __construct(OrderManager $orderManager, 
                                Serializer $serializer, 
                                Utility $utility,
                                QuoteOrderRepository $orderRepo,
                                QuoteOrderDetailRepository $orderDetailRepo,
                                OrderHydrate $orderHydrate,
                                OrderStatusRepository $statusRepo)
    {
        $this->orderDetailRepo = $orderDetailRepo;
        $this->orderRepo = $orderRepo;
        $this->utility = $utility;
        $this->serializer = $serializer;
        $this->orderManager = $orderManager;
        $this->orderHydrate = $orderHydrate;
        $this->statusRepo = $statusRepo;

        $this->moisTraduction['January']    = 'Janvier';
        $this->moisTraduction['February']   = 'Février';
        $this->moisTraduction['March']      = 'Mars';
        $this->moisTraduction['April']      = 'Avril';
        $this->moisTraduction['May']        = 'Mai';
        $this->moisTraduction['June']       = 'Juin';
        $this->moisTraduction['July']       = 'Juillet';
        $this->moisTraduction['August']     = 'Août';
        $this->moisTraduction['September']  = 'Septembre';
        $this->moisTraduction['October']    = 'Octobre';
        $this->moisTraduction['November']   = 'Novembre';
        $this->moisTraduction['December']   = 'Décembre';
    }

    public function getOrderBy(string $target){
        $moments = []; 
        $output = [];
        foreach ($this->orderRepo->findBy(['Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER'])]) as $order) {
            $orderDetrail = $this->orderHydrate->hydrateOrderDetail($this->orderDetailRepo->findBy(['QuoteOrder' => $order]), $order);
            $info = $this->orderManager->getCommandeInfo($orderDetrail, $order);
            $moment = $this->getMomentOfYearArray($target, $order);

            if ($this->utility->str_in_array($moments, $moment) == -1) {
                $moments[] = $moment;
                $montant = $info['total_TTC'];
                $output[$moment] = (!empty($montant)) ? $montant : 0;
            }
            else{
                $montant = $info['total_TTC'];
                $output[$moment] += (!empty($montant)) ? $montant : 0;
            }
        }

        return [
            'title' => 'Commande',
            'axisX' => $moments,
            'data' => $output
        ];
    }

   public function getBillBy(string $target, bool $bPayReceived = false){
        $moments = []; 
        $output = [];
        foreach ($this->orderRepo->findAll() as $order) {

            $orderDetails = $this->orderHydrate->hydrateOrderDetail($this->orderDetailRepo->findBy(['QuoteOrder' => $order]), $order);

            foreach ($orderDetails as $orderDetail) {
                foreach ($orderDetail->getQuantityDeliveries() as $qtdel) {
                    $bill = $qtdel->getBill();
                    if (!empty($bill)) {
                        $moment = $this->getMomentOfYearArray($target, $order);

                        if ($this->utility->str_in_array($moments, $moment) == -1) {
                            $moments[] = $moment;
                            $montant = ($bPayReceived) ? $bill->getPayReceived() : $bill->getPay();
                            $output[$moment] = (!empty($montant)) ? $montant: 0;
                        } else {
                            $montant = ($bPayReceived) ? $bill->getPayReceived() : $bill->getPay();
                            $output[$moment] += (!empty($montant)) ? $montant : 0;
                        }
                    }
                }
            }

        }

        return [
            'title' => (!$bPayReceived) ? 'Montant Facture' : 'Facture payée',
            'axisX' => $moments, 
            'data' => $output
        ];
    }

    public function getOrderDataBy($target){
        $output = [];
       
        switch($target) {
            case 'WEEK':
                $output[] = $this->getOrderByWeek();
                $output[] = $this->getBillByWeek();
                $output[] = $this->getBillPayedByWeek();
                break;
            case 'MONTH':
                $output[] = $this->getOrderByMonth();
                $output[] = $this->getBillByMonth();
                $output[] = $this->getBillPayedByMonth();
                break;
            case 'YEAR':
                $output[] = $this->getOrderByYear();
                $output[] = $this->getBillByYear();
                $output[] = $this->getBillPayedByYear();
                break;
        }
        
        return $output;
    }

    private function getMomentOfYearArray(string $target, QuoteOrder $order)
    {
        $moment = null;

        switch ($target) {
            case 'WEEK':
                $moment = 'Sem. ' . $this->utility->getWeekOfYear($order->getCreatedAt()) . ' '. $order->getCreatedAt()->format('Y');
                break;
            case 'MONTH':
                $moment = $this->moisTraduction[$this->utility->getMonthOfYear($order->getCreatedAt())] . ' ' . $order->getCreatedAt()->format('Y');
                break;
            case 'YEAR':
                $moment = $this->utility->getYear($order->getCreatedAt());
                break;
            default:
                $moment = $this->utility->getMonthOfYear($order->getCreatedAt()) . ' ' . $order->getCreatedAt()->format('Y');
        }

        return $moment;
    }

    public function getOrderDataByWeek()
    {
        return $this->getOrderDataBy('WEEK');
    }

    public function getOrderDataByMonth()
    {
        return $this->getOrderDataBy('MONTH');
    }

    public function getOrderDataByYear()
    {
        return $this->getOrderDataBy('YEAR');
    } 

    public function getOrderByWeek(){
        return $this->getOrderBy('WEEK');
    }

    public function getOrderByMonth(){
        return $this->getOrderBy('MONTH');
    }

    public function getOrderByYear(){
        return $this->getOrderBy('YEAR');
    }

   public function getBillByWeek(){
        return $this->getBillBy('WEEK');
    }

   public function getBillPayedByWeek(){
        return $this->getBillBy('WEEK', true);
    }

   public function getBillByMonth(){
        return $this->getBillBy('MONTH');
    }

   public function getBillPayedByMonth(){
        return $this->getBillBy('MONTH', true);
    }

   public function getBillByYear(){
        return $this->getBillBy('YEAR');
    }

   public function getBillPayedByYear(){
        return $this->getBillBy('YEAR', true);
    }

    

}