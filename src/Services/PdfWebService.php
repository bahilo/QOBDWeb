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
    protected $taxTypeHT;
    protected $taxTypeTTC;
    protected $taxTypeTvaMarge;

    public function __construct(SettingManager $settingManager,
                                QuoteOrderRepository $orderRepo,
                                ApiManager $apiManager,
                                DeliveryStatusRepository $statusRepo,
                                DeliveryRepository $deliveryRepo,
                                BillRepository $billRepo,
                                ObjectToWebserviceConverter $wsConverter,
                                Utility $utility,
                                $tax_type_ht,
                                $tax_type_ttc,
                                $tax_type_tva_marge)
    {
        $this->settingManager = $settingManager;
        $this->orderRepo = $orderRepo;
        $this->utility = $utility;
        $this->wsConverter = $wsConverter;
        $this->billRepo = $billRepo;
        $this->deliveryRepo = $deliveryRepo;
        $this->statusRepo = $statusRepo;
        $this->apiManager = $apiManager;
        $this->taxTypeHT = $tax_type_ht;
        $this->taxTypeTTC = $tax_type_ttc;
        $this->taxTypeTvaMarge = $tax_type_tva_marge;

    }

    public function downloadOrder(QuoteOrder $order, Bill $bill, $downloadDir){
        //dump($order->getStatus()->getName());die();
        $param = [
            'ws_method' => 'download_invoice',
            'ws_params' => ['company_name' => 'BNOME', 'source' => $this->getSource($order, $bill)],
            'download_dir' => $downloadDir,
            'bill' => $bill,
            'setting' => $this->settingManager->get('PDF', 'FACTURE_PREFIX')
        ];
        if($order->getStatus()->getName() == 'STATUS_REFUND' || $order->getStatus()->getName() == 'STATUS_REFUNDCLOSED'){
            $param['setting'] = $this->settingManager->get('PDF', 'AVOIR_PREFIX');
            $param['ws_method'] = 'download_refund';            
        }
        return $this->downloadBill($param, $order, $bill, $downloadDir);
    }

    public function downloadQuotation(QuoteOrder $order, $downloadDir){
        //dump($this->apiManager->getDirListing());die();
        $param = [
            'ws_method' => 'download_quote',
            'ws_params' => ['company_name' => 'BNOME', 'source' => $this->getSource($order, null)],
            'download_dir' => $downloadDir,
            'order' => $order,
            'setting' => $this->settingManager->get('PDF', 'DEVIS_PREFIX')
        ];
        if(!$order->getIsQuote()){
            $param['setting'] = $this->settingManager->get('PDF', 'PROFORMA_PREFIX');
            $param['ws_method'] = 'download_proforma';            
        }
        return $this->downloadQuote($param, $order, $downloadDir);
    }

    public function downloadBill($param, QuoteOrder $order, Bill $bill, $downloadDir){       
        
        $response = $this->apiManager->execPdfRequest($param['ws_method'], $param['ws_params']);
        $res = $this->utility->restoreSpecialChars($response);
        $fileName = 'Facture00' . $param['bill']->getId() . '.pdf';        
        if (!empty($param['setting']) && !empty($param['setting']->getValue())) {
            $fileName = $param['setting']->getValue() . $param['bill']->getId() . '.pdf';
        }
        file_put_contents($this->utility->getAbsoluteRootPath() . $param['download_dir'] .'/'. $fileName, $res[0]['Value']);
        return $this->utility->getAbsoluteRootPath() . $param['download_dir'] . '/' . $fileName;
    }

    public function downloadQuote($param, QuoteOrder $order, $downloadDir)
    {       
        $response = $this->apiManager->execPdfRequest($param['ws_method'], $param['ws_params']);
        $res = $this->utility->restoreSpecialChars($response);
        $fileName = 'Devis00' . $param['order']->getId() . '.pdf';
        if (!empty($param['setting'])) {
            $fileName = $param['setting']->getValue() . $param['order']->getId() . '.pdf';
        }
        file_put_contents($this->utility->getAbsoluteRootPath() . $param['download_dir'] . '/' . $fileName, $res[0]['Value']);
        return $this->utility->getAbsoluteRootPath() . $param['download_dir'] . '/' . $fileName;
    }

    public function downloadDelivery(Delivery $delivery, $downloadDir)
    {
        $setting = $this->settingManager->get('PDF', 'LIVRAISON_PREFIX');
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
        if (!empty($setting)) {
            $fileName = $setting->getValue() . $param['delivery']->getId() . '.pdf';
        }
        file_put_contents($this->utility->getAbsoluteRootPath() . $param['download_dir'] . '/' . $fileName, $res[0]['Value']);        return $this->utility->getAbsoluteRootPath() . $param['download_dir'] . '/' . $fileName;
    }

    public function downloadCGV($downloadDir)
    {
        $param = [
            'ws_method' => 'download_cgv',
            'ws_params' => ['company_name' => strtoupper($this->settingManager->get("SOCIETE", "SOCIETE_NOM")->getValue()), 'source' => $this->getCGVSource()],
            'download_dir' => $downloadDir,
        ];        
        $response = $this->apiManager->execPdfRequest($param['ws_method'], $param['ws_params']);
        $res = $this->utility->restoreSpecialChars($response);
        $fileName = 'CGV.pdf';
        file_put_contents($this->utility->getAbsoluteRootPath() . $param['download_dir'] . '/' . $fileName, $res[0]['Value']);        return $this->utility->getAbsoluteRootPath() . $param['download_dir'] . '/' . $fileName;
    }

    private function getSource(QuoteOrder $order, ?Bill $bill){
        $objArray = $this->wsConverter->getObjectFromOrder($order, $bill);
        // dump($objArray);        
        $source = array(
            "Lang"              => "fr",
            "TaxType"           => $this->getTaxType($order),
            "Delay"             => $order->getValidityPeriode(),
            "refv"              => $order->getIsRefVisible() ? 1 : 0,
            "Order"             => $objArray['order'],
            "Deliveries"        => $objArray['delivery'],
            "Item_deliveries"   => $objArray['item_delivery'],
            "Items"             => $objArray['item'],
            "Client"            => $objArray['client'],
            "Infos"             => $objArray['info'],
            "Currency"          => $objArray['currency'],
            "Bills"             => $objArray['bill'],
            "DeliveryAddress"   => $objArray['delivery_address'],
            "BillAddress"       => $objArray['bill_address'],
            "Agent"             => $objArray['agent'],
            "Order_items"       => $objArray['order_item'],
            "Tax"               => $objArray['tax_order']
        );
        $source = $this->utility->replaceSpecialChars($source);
        //dump($objArray['item']);
        // dump($source);
        // die();
        return $source;
    }

    private function getCGVSource(){
        //dump($objArray);die();
        $source = array(
            "Lang"              => "fr",
            "Infos"             => $this->utility->replaceSpecialChars($this->wsConverter->convertAllInfoToObject()),
          );
        //dump($objArray['item']);
        //dump($source);
        //die();
        return $source;
    }

    private function getTaxType(QuoteOrder $order){
        $tax = $order->getTax();
        if($tax->getIsTVAMarge())
            return $this->taxTypeTvaMarge;
        elseif($tax->getValue() == 0)
            return $this->taxTypeHT;
        else   
            return $this->taxTypeTTC;
    }
}