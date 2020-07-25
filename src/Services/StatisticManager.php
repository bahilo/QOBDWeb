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
        $orders = [];
        $lstStatus = ['STATUS_ORDER', 'STATUS_BILL'];

        foreach ($lstStatus as $status) {
            $orders = array_merge($orders, $this->orderRepo->findBy(['Status' => $this->statusRepo->findOneBy(['Name' => $status])]));
        }
        
        //dump($orders);die();
        foreach ($orders as $order) {
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

    public function getCountBy(array $lstStatus, string $target){
        $moments = []; 
        $output = [];//'STATUS_ORDER'
        $inputs = [];

        foreach($lstStatus as $status){
            $inputs = array_merge($inputs, $this->orderRepo->findBy(['Status' => $this->statusRepo->findOneBy(['Name' => $status])]));
        }

        foreach ($inputs as $order) {
           $moment = $this->getMomentOfYearArray($target, $order);

            if ($this->utility->str_in_array($moments, $moment) == -1) {
                $moments[] = $moment;
                $output[$moment] = 1;
            }
            else{
                $output[$moment] += 1;
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

    public function getCountDataBy($status, $target){
        $output = [];
       
        switch($status) {
            case 'STATUS_ORDER':
                switch ($target) {
                    case 'WEEK':
                        $output[] = $this->getCountOrderByWeek();
                        break;
                    case 'MONTH':
                        $output[] = $this->getCountOrderByMonth();
                        break;
                    case 'YEAR':
                        $output[] = $this->getCountOrderByYear();
                        break;
                }
                break;
            case 'STATUS_QUOTE':
                switch ($target) {
                    case 'WEEK':
                        $output[] = $this->getCountQuoteByWeek();
                        break;
                    case 'MONTH':
                        $output[] = $this->getCountQuoteByMonth();
                        break;
                    case 'YEAR':
                        $output[] = $this->getCountQuoteByYear();
                        break;
                }
                break;
            case 'STATUS_REFUND':
                switch ($target) {
                    case 'WEEK':
                        $output[] = $this->getCountRefundByWeek();
                        break;
                    case 'MONTH':
                        $output[] = $this->getCountRefundByMonth();
                        break;
                    case 'YEAR':
                        $output[] = $this->getCountRefundByYear();
                        break;
                }
                break;
            case 'STATUS_VALID':
                switch ($target) {
                    case 'WEEK':
                        $output[] = $this->getCountValidByWeek();
                        break;
                    case 'MONTH':
                        $output[] = $this->getCountValidByMonth();
                        break;
                    case 'YEAR':
                        $output[] = $this->getCountValidByYear();
                        break;
                }
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

    //________________________________________________[ Orders ]____________________________//
    
    //-------------------------[ Month ]
    public function getOrderDataByMonth()
    {
        return $this->getOrderDataBy('MONTH');
    }

    public function getCountOrderByMonth()
    {
        return $this->getCountBy(['STATUS_ORDER', 'STATUS_BILL'],'MONTH');
    }

    public function getOrderByMonth(){
        return $this->getOrderBy('MONTH');
    }

     //-------------------------[ Year ]
    public function getOrderDataByYear()
    {
        return $this->getOrderDataBy('YEAR');
    }

    public function getCountOrderByYear()
    {
        return $this->getCountBy(['STATUS_ORDER', 'STATUS_BILL'], 'YEAR');
    } 

    public function getOrderByYear(){
        return $this->getOrderBy('YEAR');
    }

    //-------------------------[ Week ]
    public function getOrderDataByWeek()
    {
        return $this->getOrderDataBy('WEEK');
    }

    public function getCountOrderByWeek()
    {
        return $this->getCountBy(['STATUS_ORDER', 'STATUS_BILL'], 'WEEK');
    } 

    public function getOrderByWeek(){
        return $this->getOrderBy('WEEK');
    }

    //________________________________________________[ Bills ]____________________________//
    
    //-------------------------[ Month ] 
   public function getBillByMonth(){
        return $this->getBillBy('MONTH');
    }

   public function getBillPayedByMonth(){
        return $this->getBillBy('MONTH', true);
    }

   //-------------------------[ Week ] 
    public function getBillPayedByWeek(){
        return $this->getBillBy('WEEK', true);
    }  
    public function getBillByWeek(){
        return $this->getBillBy('WEEK');
    }

    //-------------------------[ Year ] 
   public function getBillByYear(){
        return $this->getBillBy('YEAR');
    }

   public function getBillPayedByYear(){
        return $this->getBillBy('YEAR', true);
    }

    //________________________________________________[ Quotes ]____________________________//
   
    //-------------------------[ Month ] 
    public function getCountQuoteByMonth()
    {
        return $this->getCountBy(['STATUS_QUOTE'], 'MONTH');
    }

    //-------------------------[ Week ] 
    public function getCountQuoteByWeek()
    {
        return $this->getCountBy(['STATUS_QUOTE'], 'WEEK');
    }

    //-------------------------[ Year ] 
    public function getCountQuoteByYear()
    {
        return $this->getCountBy(['STATUS_QUOTE'], 'YEAR');
    }

    //________________________________________________[ Refunds ]____________________________//

    //-------------------------[ Month ] 
    public function getCountValidByMonth()
    {
        return $this->getCountBy(['STATUS_VALID'], 'MONTH');
    }

    //-------------------------[ Week ] 
    public function getCountValidByWeek()
    {
        return $this->getCountBy(['STATUS_VALID'], 'WEEK');
    }

    //-------------------------[ Year ] 
    public function getCountValidByYear()
    {
        return $this->getCountBy(['STATUS_VALID'], 'YEAR');
    }

    //________________________________________________[ Customer validation ]____________________________//

    //-------------------------[ Month ] 
    public function getCountRefundByMonth()
    {
        return $this->getCountBy(['STATUS_REFUND'], 'MONTH');
    }

    //-------------------------[ Week ] 
    public function getCountRefundByWeek()
    {
        return $this->getCountBy(['STATUS_REFUND'], 'WEEK');
    }

    //-------------------------[ Year ] 
    public function getCountRefundByYear()
    {
        return $this->getCountBy(['STATUS_REFUND'], 'YEAR');
    } 
}