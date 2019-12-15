<?php

namespace App\Services;

use App\Entity\Bill;
use App\Entity\Delivery;
use App\Entity\QuoteOrder;
use App\Services\Utility;
use App\Repository\BillRepository;
use App\Repository\SettingRepository;
use App\Repository\DeliveryRepository;
use App\Repository\QuoteOrderRepository;
use App\Repository\DeliveryStatusRepository;
use App\Services\ObjectToWebserviceConverter;

class PdfWebService{

    protected $client;
    protected $settingRepo;
    protected $orderRepo;
    protected $utility;
    protected $wsConverter;
    protected $billRepo;
    protected $statusRepo;
    protected $deliveryRepo;

    public function __construct(SettingRepository $settingRepo,
                                QuoteOrderRepository $orderRepo,
                                DeliveryStatusRepository $statusRepo,
                                DeliveryRepository $deliveryRepo,
                                BillRepository $billRepo,
                                ObjectToWebserviceConverter $wsConverter,
                                Utility $utility)
    {
        $this->settingRepo = $settingRepo;
        $this->orderRepo = $orderRepo;
        $this->utility = $utility;
        $this->wsConverter = $wsConverter;
        $this->billRepo = $billRepo;
        $this->deliveryRepo = $deliveryRepo;
        $this->statusRepo = $statusRepo;

        $settingPDF = $this->settingRepo->findOneBy(['Code' => 'WEBSERVICE', 'Name' => 'PDF']);
        $settingUserName = $this->settingRepo->findOneBy(['Code' => 'WEBSERVICE', 'Name' => 'Username']);
        $settingPassword = $this->settingRepo->findOneBy(['Code' => 'WEBSERVICE', 'Name' => 'Password']);

        if(!empty($settingPDF)){
            $this->client = new \nusoap_client($settingPDF->getValue(), true);
            if(!empty($settingUserName) && !empty($settingPassword))
                $this->client->setCredentials($settingUserName->getValue(), $settingPassword->getValue(), 'basic');
        }
    }

    public function call($method, $params){
        if(!empty($this->client)){
            $result = $this->client->call($method, $params, 'http://localhost/QOBD/server.php');
            //dump($this->client);die();
            return $result;
        }            
    }

    public function downloadBill(Bill $bill, $downloadDir, int $target, int $refundTarget){

        $order = $this->orderRepo->findOneByBill($bill);
        
        if($order->getStatus()->getName() == 'STATUS_REFUND')
            $target = $refundTarget;
            
        $param = [
            'ws_method' => 'download_invoice',
            'ws_params' => ['company_name' => 'BNOME', 'source' => $this->getSource($order, $bill, $target)],
            'download_dir' => $downloadDir,
            'bill' => $bill
        ];

        $response = $this->call($param['ws_method'], $param['ws_params']);
        $res = $this->utility->restoreSpecialChars($response);

        $fileName = 'Facture00' . $param['bill']->getId() . '.pdf';

        $setting = $this->settingRepo->findOneBy(['Code' => 'FACTURE', 'Name' => 'prefix']);
        if (!empty($setting)) {
            $fileName = $setting->getValue() . $param['bill']->getId() . '.pdf';
        }
        file_put_contents($param['download_dir'] .'/'. $fileName, $res[0]['Value']);

        return $param['download_dir'] . '/' . $fileName;
    }

    public function downloadQuote(QuoteOrder $order, $downloadDir, int $target)
    {       

        $param = [
            'ws_method' => 'download_quote',
            'ws_params' => ['company_name' => 'BNOME', 'source' => $this->getSource($order, null, $target)],
            'download_dir' => $downloadDir,
            'order' => $order
        ];

        $response = $this->call($param['ws_method'], $param['ws_params']);
        $res = $this->utility->restoreSpecialChars($response);

        $fileName = 'Devis00' . $param['order']->getId() . '.pdf';

        $setting = $this->settingRepo->findOneBy(['Code' => 'DEVIS', 'Name' => 'prefix']);
        if (!empty($setting)) {
            $fileName = $setting->getValue() . $param['order']->getId() . '.pdf';
        }
        file_put_contents($param['download_dir'] . '/' . $fileName, $res[0]['Value']);

        return $param['download_dir'] . '/' . $fileName;
    }

    public function downloadDelivery(Delivery $delivery, $downloadDir)
    {

        $order = $this->orderRepo->findOneByDelivery($delivery);
        $bill = $this->billRepo->findOneByDelivery($delivery);
        $param = [
            'ws_method' => 'download_delivery',
            'ws_params' => ['company_name' => 'BNOME', 'source' => $this->getSource($order, $bill, 0)],
            'download_dir' => $downloadDir,
            'delivery' => $delivery
        ];

        $response = $this->call($param['ws_method'], $param['ws_params']);
        $res = $this->utility->restoreSpecialChars($response);

        $fileName = 'BL00' . $param['delivery']->getId() . '.pdf';

        $setting = $this->settingRepo->findOneBy(['Code' => 'LIVRAISON', 'Name' => 'prefix']);
        if (!empty($setting)) {
            $fileName = $setting->getValue() . $param['delivery']->getId() . '.pdf';
        }
        file_put_contents($param['download_dir'] . '/' . $fileName, $res[0]['Value']);

        return $param['download_dir'] . '/' . $fileName;
    }

    private function getSource(QuoteOrder $order, ?Bill $bill, $target){
        $objArray = $this->wsConverter->getObjectFromOrder($order, $bill);

        $source = array(
            "Lang"              => "fr",
            "TaxType"           => 'TTC',
            "Delay"             => $order->getValidityPeriode(),
            "Status"            => $target,
            "refv"              => $order->getIsRefVisible() ? 1 : 0,
            "Order"             => $this->utility->replaceSpecialChars($objArray['order']),
            "Deliveries"        => $this->utility->replaceSpecialChars($objArray['delivery']),
            "Item_deliveries"   => $this->utility->replaceSpecialChars($objArray['item_delivery']),
            "Items"             => $this->utility->replaceSpecialChars($objArray['item']),
            "Client"            => $this->utility->replaceSpecialChars($objArray['client']),
            "Infos"             => $this->utility->replaceSpecialChars($objArray['info']),
            "Currency"          => $this->utility->replaceSpecialChars($objArray['currency']),
            "Bills"             => $this->utility->replaceSpecialChars($objArray['bill']),
            "DeliveryAddress"   => $this->utility->replaceSpecialChars($objArray['delivery_address']),
            "BillAddress"       => $this->utility->replaceSpecialChars($objArray['bill_address']),
            "Agent"             => $this->utility->replaceSpecialChars($objArray['agent']),
            "Order_items"       => $this->utility->replaceSpecialChars($objArray['order_item']),
            "Tax"               => $this->utility->replaceSpecialChars($objArray['tax'])
        );
        return $source;
    }
}