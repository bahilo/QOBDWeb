<?php

namespace App\Services;

use App\Entity\Bill;
use App\Entity\Delivery;
use App\Services\Utility;
use App\Entity\QuoteOrder;
use App\Services\SettingManager;
use App\Repository\BillRepository;
use App\Repository\DeliveryRepository;
use App\Repository\QuoteOrderRepository;
use App\Repository\DeliveryStatusRepository;
use App\Services\ObjectToWebserviceConverter;

class PdfWebService{

    protected $apiManager;
    protected $settingManager;
    protected $orderRepo;
    protected $utility;
    protected $wsConverter;
    protected $billRepo;
    protected $statusRepo;
    protected $deliveryRepo;
    protected $baseDir;

    public function __construct(SettingManager $settingManager,
                                QuoteOrderRepository $orderRepo,
                                ApiManager $apiManager,
                                DeliveryStatusRepository $statusRepo,
                                DeliveryRepository $deliveryRepo,
                                BillRepository $billRepo,
                                ObjectToWebserviceConverter $wsConverter,
                                Utility $utility)
    {
        $this->settingManager = $settingManager;
        $this->orderRepo = $orderRepo;
        $this->utility = $utility;
        $this->wsConverter = $wsConverter;
        $this->billRepo = $billRepo;
        $this->deliveryRepo = $deliveryRepo;
        $this->statusRepo = $statusRepo;
        $this->apiManager = $apiManager;
        $dir = dirname(__DIR__);
        $this->baseDir = dirname($dir) . '/public/';

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

        //dump($param); die();
        $response = $this->apiManager->execPdfRequest($param['ws_method'], $param['ws_params']);
        $res = $this->utility->restoreSpecialChars($response);

        $fileName = 'Facture00' . $param['bill']->getId() . '.pdf';

        $setting = $this->settingManager->get('PDF', 'FACTURE_PREFIX');
        if (!empty($setting) && !empty($setting->getValue())) {
            $fileName = $setting->getValue() . $param['bill']->getId() . '.pdf';
        }

        file_put_contents($this->baseDir . $param['download_dir'] .'/'. $fileName, $res[0]['Value']);

        return $this->baseDir . $param['download_dir'] . '/' . $fileName;
    }

    public function downloadQuote(QuoteOrder $order, $downloadDir, int $target)
    {       

        $param = [
            'ws_method' => 'download_quote',
            'ws_params' => ['company_name' => 'BNOME', 'source' => $this->getSource($order, null, $target)],
            'download_dir' => $downloadDir,
            'order' => $order
        ];

        $response = $this->apiManager->execPdfRequest($param['ws_method'], $param['ws_params']);
        $res = $this->utility->restoreSpecialChars($response);

        $fileName = 'Devis00' . $param['order']->getId() . '.pdf';

        $setting = $this->settingManager->get('PDF', 'DEVIS_PREFIX');
        if (!empty($setting)) {
            $fileName = $setting->getValue() . $param['order']->getId() . '.pdf';
        }
        // dump($res[0]['Name']);
        // dump($param['download_dir']);
        // die();
        file_put_contents($this->baseDir . $param['download_dir'] . '/' . $fileName, $res[0]['Value']);

        return $this->baseDir . $param['download_dir'] . '/' . $fileName;
    }

    public function downloadDelivery(Delivery $delivery, $downloadDir)
    {

        $order = $this->orderRepo->findOneByDelivery($delivery);
        $bill = $this->billRepo->findOneByDelivery($delivery);
        $param = [
            'ws_method' => 'download_delivery',
            'ws_params' => ['company_name' => strtoupper($this->settingManager->get("SOCIETE", "SOCIETE_NOM")->getValue()), 'source' => $this->getSource($order, $bill, 0)],
            'download_dir' => $downloadDir,
            'delivery' => $delivery
        ];
        
        $response = $this->apiManager->execPdfRequest($param['ws_method'], $param['ws_params']);
        $res = $this->utility->restoreSpecialChars($response);

        $fileName = 'BL00' . $param['delivery']->getId() . '.pdf';

        $setting = $this->settingManager->get('PDF', 'LIVRAISON_PREFIX');
        if (!empty($setting)) {
            $fileName = $setting->getValue() . $param['delivery']->getId() . '.pdf';
        }
        file_put_contents($this->baseDir . $param['download_dir'] . '/' . $fileName, $res[0]['Value']);

        return $this->baseDir . $param['download_dir'] . '/' . $fileName;
    }

    private function getSource(QuoteOrder $order, ?Bill $bill, $target){
        $objArray = $this->wsConverter->getObjectFromOrder($order, $bill);
        //dump($objArray);die();
        $source = array(
            "Lang"              => "fr",
            "TaxType"           => $this->getTaxType($order),
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
            "Tax"               => $this->utility->replaceSpecialChars($objArray['tax_order'])
        );
        //dump($objArray['item']);
        //dump($source);
        //die();
        return $source;
    }

    private function getTaxType(QuoteOrder $order){
        $tax = $order->getTax();
        if($tax->getIsTVAMarge())
            return 2;
        elseif($tax->getValue() == 0)
            return 0;
        else   
            return 1;
    }
}