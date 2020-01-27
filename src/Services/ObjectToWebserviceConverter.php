<?php

namespace App\Services;

use App\Entity\Tax;
use App\Entity\Bill;
use App\Entity\Item;
use App\Entity\Agent;
use App\Entity\Client;
use App\Entity\Address;
use App\Entity\Contact;
use App\Entity\Setting;
use App\Entity\Currency;
use App\Entity\Delivery;
use App\Entity\QuoteOrder;
use App\Services\OrderHydrate;
use App\Entity\QuantityDelivery;
use App\Entity\QuoteOrderDetail;
use App\Repository\TaxRepository;
use App\Repository\ItemRepository;
use App\Repository\SettingRepository;
use App\Repository\CurrencyRepository;
use App\Repository\DeliveryRepository;
use App\Repository\QuantityDeliveryRepository;

class ObjectToWebserviceConverter{

    protected $qtDeliveryRepo;
    protected $deliveryRepo;
    protected $settingRepo;
    protected $currencyRepo;
    protected $taxRepo;
    protected $orderHydrate;
    protected $itemRepo;

    public function __construct(DeliveryRepository $deliveryRepo, 
                                QuantityDeliveryRepository $qtDelivery,
                                SettingRepository $settingRepo,
                                TaxRepository $taxRepo,
                                CurrencyRepository $currencyRepo,
                                OrderHydrate $orderHydrate,
                                ItemRepository $itemRepo)
    {
        $this->qtDeliveryRepo = $qtDelivery;
        $this->deliveryRepo = $deliveryRepo;
        $this->settingRepo = $settingRepo;
        $this->currencyRepo = $currencyRepo;
        $this->taxRepo = $taxRepo;
        $this->orderHydrate = $orderHydrate;
        $this->itemRepo = $itemRepo;
    }

    public function getObjectFromOrder(QuoteOrder $order, ?Bill $bill){
        $output = [];
        $output['delivery'] = [];
        $output['item_delivery'] = [];
        $output['item'] = [];
        $output['info'] = [];
        $output['order_item'] = [];
        $output['order'] = [];
        $output['client'] = [];
        $output['currency'] = [];
        $output['bill'] = [];
        $output['delivery_address'] = [];
        $output['bill_address'] = [];
        $output['agent'] = [];
        $output['tax'] = [];
        $output['tax_order'] = [];

        if(!empty($bill))
            $output['delivery'] = $this->convertDeliveryToObject($this->deliveryRepo->findOneByBill($bill), $order, $bill);
        else
            $output['delivery'] = $this->convertDeliveryToObject(null, $order, $bill);

        if (!empty($bill)){
            foreach ($this->qtDeliveryRepo->findByBill($bill) as $qtDelivery) {
                array_push($output['item_delivery'], $this->convertQuantityDeliveyToObject($qtDelivery));
                array_push($output['item'], $this->convertItemToObject($qtDelivery->getOrderDetail()->getItem()));
            }
        }
        else{
            foreach ($this->itemRepo->findByOrder($order) as  $item) {               
                array_push($output['item'], $this->convertItemToObject($item));
            }
        }
        
       
        foreach ($this->settingRepo->findBy(['Code' => 'SOCIETE']) as $setting) {
            array_push($output['info'], $this->convertSettingToObject($setting));
        }
        
        foreach ($order->getQuoteOrderDetails() as $orderDetail) {
            array_push($output['order_item'], $this->convertOrderDetailToObject($orderDetail));
        }

        //dump($order); die();
        if(!empty($bill))
            $output['bill'] = $this->convertBillToObject($bill, $order, $order->getClient());
        else
            $output['bill']=$this->convertBillToObject(null, $order, $order->getClient());
        $output['order']=$this->convertOrderToObject($order);
        $output['client']=$this->convertClientContactToObject($order->getContact(), $order->getAgent());
        $output['currency']=$this->convertCurrencyToObject($this->currencyRepo->findOneBy(['IsDefault' => true]));
        $output['delivery_address']=$this->convertAddressToObject($order->getContact()->getAddress(), $order->getClient());
        $output['bill_address']=$this->convertAddressToObject($order->getContact()->getAddress(), $order->getClient());
        $output['agent']=$this->convertAgentToObject($order->getAgent());
        $output['tax']=$this->convertTaxToObject($this->taxRepo->findOneBy(['IsCurrent' => true]));
        $output['tax_order']=$this->convertTaxToObject($this->taxRepo->findOneBy(['IsCurrent' => true]));

        return $output;
    }

    /*______________________________________________________________________________________________________ 
    */

    public function convertOrderToObject(?QuoteOrder $order){
        $obj = [];
        if (!empty($order)) {
            
            $agent = $order->getAgent();
            $client = $order->getClient();
            $currency = $order->getCurrency();
            $privateCom = $order->getPrivateComment();
            $publicCom = $order->getPublicComment();
            $adminCom = $order->getAdminComment();
            $agent = $order->getAgent();
            $contat = $order->getContact();
            $status = $order->getStatus();

            $obj['ID'] = $order->getId();

            if(!empty($agent))
                $obj['AgentId'] = $agent->getId();
            else
                $obj['AgentId'] = 0;

            if (!empty($client))
                $obj['ClientId'] = $client->getId();
            else
                $obj['ClientId'] = 0;

            if (!empty($currency))
                $obj['CurrencyId'] = $currency->getId();
            else
                $obj['CurrencyId'] = 0;

            if (!empty($privateCom))
                $obj['Comment1'] = $privateCom->getContent();
            else
                $obj['Comment1'] = '';

            if (!empty($publicCom))
                $obj['Comment2'] = $publicCom->getContent();
            else
                $obj['Comment2'] = '';

            if (!empty($adminCom))
                $obj['Comment3'] = $adminCom->getContent();
            else
                $obj['Comment3'] = '';

            if (!empty($contact))
                $obj['BillAddress'] = $contat->getAddress()->getId();
            else
                $obj['BillAddress'] = 0;

            if (!empty($contact))
                $obj['BillAddress'] = $contat->getAddress()->getId();
            else
                $obj['BillAddress'] = 0;

            if (!empty($contact))
                $obj['DeliveryAddress'] = $contat->getAddress()->getId();
            else
                $obj['DeliveryAddress'] = 0;

            if (!empty($status))
                $obj['Status'] = $this->convertStatusToObject($status->getName());
            else
                $obj['Status'] = 0;

            $obj['Date'] = $order->getCreatedAt();
            if (!empty($obj['Date']))
                $obj['Date'] = $obj['Date']->format('y-m-d');

            $obj['Tax']  = 0;
        }
        else{
            $obj['ID'] = 0;
            $obj['AgentId'] = 0;
            $obj['ClientId'] = 0;
            $obj['CurrencyId'] = 0;
            $obj['Comment1'] = '';
            $obj['Comment2'] = '';
            $obj['Comment3'] = '';
            $obj['BillAddress'] = 0;
            $obj['BillAddress'] = 0;
            $obj['DeliveryAddress'] = 0;
            $obj['Status'] = 0;
            $obj['Date'] = '';
            $obj['Tax']  = 0;            
        }
        return $obj;
    }

    public function convertDeliveryToObject(?Delivery $delivery, QuoteOrder $order, ?Bill $bill)
    {
        $obj = [];
        if (!empty($delivery)) {

            $status = $delivery->getStatus();
            
            $obj['ID'] = $delivery->getId();

            if (!empty($order))
                $obj['OrderId'] = $order->getId();
            else
                $obj['OrderId'] = 0;

            if (!empty($bill))
                $obj['BillId'] = $bill->getId();
            else
                $obj['BillId'] = 0;

            if (!empty($status))
                $obj['Status'] = $this->convertDeliveryStatusToObject($status->getName());
            else
                $obj['Status'] = 0;

           
            $obj['Date'] = $delivery->getCreatedAt();
            if (!empty($obj['Date']))
                $obj['Date'] = $obj['Date']->format('y-m-d');

            $obj['Package']  = $delivery->getPackage();
        }
        else{
            $obj['ID'] = 0;
            $obj['OrderId'] = 0;
            $obj['BillId'] = 0;
            $obj['Status'] = 0;
            $obj['Date'] = '';
            $obj['Package']  = 0;
        }
        return $obj;
    }

    public function convertItemToObject(?Item $item)
    {
        $obj = [];
        if (!empty($item)) {

            $group = $item->getItemGroupe();
            $brand = $item->getItemBrand();
            $comment = $item->getComment();
            
            $obj['ID'] = $item->getId();

            if (!empty($group))
                $obj['Type'] = $group->getName();
            else
                $obj['Type'] = '';

            if (!empty($brand))
                $obj['Type_sub'] = $brand->getName();
            else
                $obj['Type_sub'] = '';

            if (!empty($comment))
                $obj['Comment'] = $comment->getContent();
            else
                $obj['Comment'] = '';

            $obj['Name'] = $item->getName();
            $obj['Ref'] = $item->getRef();
            $obj['Price_purchase'] = $item->getPurchasePrice();
            $obj['Price_sell'] = $item->getSellPrice();
            $obj['Stock']  = $item->getStock();
            $obj['Erasable']  = $item->getIsErasable();
            $obj['Source']  = 0;
            $obj['Picture']  = $item->getPicture();
        }
        else{
            $obj['ID'] = 0;
            $obj['Type'] = '';
            $obj['Type_sub'] = '';
            $obj['Comment'] = '';       
            $obj['Name'] = '';
            $obj['Ref'] =  '';
            $obj['Price_purchase'] =  0;
            $obj['Price_sell'] =  0;
            $obj['Stock']  = 0;
            $obj['Erasable']  = true;
            $obj['Source']  = 0;
            $obj['Picture']  = '';
        }
        return $obj;
    }

    public function convertAgentToObject(?Agent $agent)
    {
        $obj = [];
        if (!empty($agent)) {

            $obj['ID'] = $agent->getId();
            $obj['FirstName'] = $agent->getFirstName();
            $obj['LastName'] = $agent->getLastName();
            $obj['Phone'] = $agent->getPhone();
            $obj['Fax'] = $agent->getFax();
            $obj['Email']  = $agent->getEmail();
            $obj['UserName']  = $agent->getUserName();
            $obj['Password']  = $agent->getPassword();
            $obj['Picture']  = $agent->getPicture();
            $obj['Admin']  = $agent->getIsAdmin();
            $obj['Status']  = $agent->getIsActivated();
            $obj['IsOnline']  = $agent->getIsOnline();
            $obj['ListSize']  = $agent->getListSize();
        }
        else{
            $obj['ID'] = 0;
            $obj['FirstName'] =  '';
            $obj['LastName'] =  '';
            $obj['Phone'] =  '';
            $obj['Fax'] =  '';
            $obj['Email']  =  '';
            $obj['UserName']  =  '';
            $obj['Password']  =  '';
            $obj['Picture']  =  '';
            $obj['Admin']  =  false;
            $obj['Status']  =  '';
            $obj['IsOnline']  =  false;
            $obj['ListSize']  =  0;
        }
        return $obj;
    }

    public function convertAddressToObject(?Address $address, Client $client)
    {
        $obj = [];
        if (!empty($address)) {

            $obj['ID'] = $address->getId();
            $obj['ClientId'] = $client->getId();
            $obj['Name'] = $address->getName();
            $obj['Name2'] = $address->getName();
            $obj['CityName'] = $address->getCity();
            $obj['Address']  = $address->getStreet();
            $obj['Postcode']  = $address->getZipCode();
            $obj['Country']  = $address->getCountry();
            $obj['Comment']  = '';
            $obj['FirstName']  = '';
            $obj['LastName']  = '';
            $obj['Phone']  = '';
            $obj['Email']  = '';
        }
        else{
            $obj['ID'] = 0;
            $obj['ClientId'] = 0;
            $obj['Name'] =  '';
            $obj['Name2'] =  '';
            $obj['CityName'] = '';
            $obj['Address']  =  '';
            $obj['Postcode']  =  '';
            $obj['Country']  =  '';
            $obj['Comment']  = '';
            $obj['FirstName']  = '';
            $obj['LastName']  = '';
            $obj['Phone']  = '';
            $obj['Email']  = '';
        }
        return $obj;
    }

    public function convertClientContactToObject(?Contact $contact, Agent $agent)
    {
        $obj = [];
        if (!empty($contact)) {

            $obj['ID'] = $contact->getId();
            $obj['AgentId'] = $agent->getId();
            $obj['FirstName'] = $contact->getFirstname();
            $obj['LastName'] = $contact->getLastName();
            $obj['Company'] = $contact->getPosition();
            $obj['Email']  = $contact->getEmail();
            $obj['Phone']  = $contact->getPhone();
            $obj['Fax']  = $contact->getFax();
            $obj['Rib']  = '';
            $obj['CRN']  = '';
            $obj['PayDelay']  = '';
            $obj['Comment']  = '';
            $obj['MaxCredit']  = '';
            $obj['Status']  = '';
            $obj['CompanyName']  = '';
        }
        else{
            $obj['ID'] = 0;
            $obj['AgentId'] = 0;
            $obj['FirstName'] =  '';
            $obj['LastName'] = '';
            $obj['Company'] =  '';
            $obj['Email']  =  '';
            $obj['Phone']  = '';
            $obj['Fax']  = '';
            $obj['Rib']  = '';
            $obj['CRN']  = '';
            $obj['PayDelay']  = '';
            $obj['Comment']  = '';
            $obj['MaxCredit']  = '';
            $obj['Status']  = '';
            $obj['CompanyName']  = '';
        }
        return $obj;
    }

    public function convertSettingToObject(?Setting $setting)
    {
        $obj = [];
        if (!empty($setting)) {

            $obj['ID'] = $setting->getId();
            $obj['Name'] = $setting->getName();
            $obj['Value'] = $setting->getValue();
        }
        else{
            $obj['ID'] = 0;
            $obj['Name'] =  '';
            $obj['Value'] = '';
        }
        return $obj;
    }

    public function convertTaxToObject(?Tax $tax)
    {
        $obj = [];
        if (!empty($tax)) {

            $obj['ID'] = $tax->getId();
            $obj['Type'] = $tax->getType();

            $obj['Date_insert'] = $tax->getCreateAt();
            if (!empty($obj['Date_insert']))
                $obj['Date_insert'] = $obj['Date_insert']->format('y-m-d');

            $obj['Value'] = $tax->getValue();
            $obj['Comment'] = '';
            $obj['Tax_current'] = $tax->getIsCurrent();
        }
        else{
            $obj['ID'] = 0;
            $obj['Type'] =  '';
            $obj['Date_insert'] =  '';
            $obj['Value'] =  '';
            $obj['Comment'] = '';
            $obj['Tax_current'] =  '';
        }
        return $obj;
    }

    public function convertTaxOrderToObject(?Tax $tax, QuoteOrder $order)
    {
        $obj = [];
        if (!empty($tax)) {

            $obj['ID'] = $tax->getId();
            $obj['OrderId'] = $order->getId();
            $obj['TaxId'] = $tax->getId();

            $obj['Date_insert'] = $tax->getCreateAt();
            if (!empty($obj['Date_insert']))
                $obj['Date_insert'] = $obj['Date_insert']->format('y-m-d');

            $obj['Tax_value'] = $tax->getValue();
            $obj['Target'] = 'commande';
        }
        else{
            $obj['ID'] = 0;
            $obj['OrderId'] = 0;
            $obj['TaxId'] = 0;
            $obj['Date_insert'] = '';
            $obj['Tax_value'] =  '';
            $obj['Target'] = '';
        }
        return $obj;
    }

    public function convertCurrencyToObject(?Currency $currency)
    {
        $obj = [];
        if (!empty($currency)) {

            $obj['ID'] = $currency->getId();
            $obj['Name'] = $currency->getName();
            $obj['Symbol'] = $currency->getSymbol();
            $obj['Rate'] = $currency->getRate();
            $obj['Country_code'] = $currency->getCountryCode();
            $obj['Currency_code'] = '';
            $obj['Country'] = $currency->getCountry();
            $obj['IsDefault'] = $currency->getIsDefault();

            $obj['Date'] = $currency->getCreatedAt();
            if (!empty($obj['Date']))
                $obj['Date'] = $obj['Date']->format('y-m-d');
        }
        else{
            $obj['ID'] = 0;
            $obj['Name'] =  '';
            $obj['Symbol'] =  '';
            $obj['Rate'] =  '';
            $obj['Country_code'] =  '';
            $obj['Currency_code'] = '';
            $obj['Country'] =  '';
            $obj['IsDefault'] =  '';
            $obj['Date'] =  '';
        }
        return $obj;
    }

    public function convertBillToObject(?Bill $bill, QuoteOrder $order, Client $client)
    {
        $obj = [];
        if (!empty($bill)) {

            $obj['ID'] = $bill->getId();
            $obj['ClientId'] = $client->getId();
            $obj['OrderId'] = $order->getId();
            $obj['PayMod'] = $bill->getPayMode();
            $obj['Pay'] = $bill->getPay();
            $obj['PayReceived'] = $bill->getPayReceived();
            $obj['Comment1'] = $bill->getPrivateComment();
            $obj['Comment2'] = $bill->getPublicComment(); 

            $obj['Date'] = $bill->getCreatedAt();
             if(!empty($obj['Date']))
                $obj['Date'] = $obj['Date']->format('y-m-d');

            $obj['DateLimit'] = $bill->getLimitDateAt();
            if (!empty($obj['DateLimit']))
                $obj['DateLimit'] = $obj['DateLimit']->format('y-m-d');

            $obj['DatePay'] = $bill->getPayedAt();
            if (!empty($obj['DatePay']))
                $obj['DatePay'] = $obj['DatePay']->format('y-m-d');
        }
        else{
            $obj['ID'] = 0;
            $obj['ClientId'] = 0;
            $obj['OrderId'] = 0;
            $obj['PayMod'] =  '';
            $obj['Pay'] =  0;
            $obj['PayReceived'] = 0;
            $obj['Comment1'] =  '';
            $obj['Comment2'] =  '';
            $obj['Date'] =  '';
            $obj['DateLimit'] =  '';
            $obj['DatePay'] =  '';
        }
        return $obj;
    }

    public function convertOrderDetailToObject(?QuoteOrderDetail $orderDetail)
    {
        $obj = [];
        if (!empty($orderDetail)) {
            $orderDetail = $this->orderHydrate->hydrateOrderDetail([$orderDetail])[0];
            $obj['ID'] = $orderDetail->getId();
            $obj['OrderId'] = $orderDetail->getQuoteOrder()->getId();
            $obj['ItemId'] = $orderDetail->getItem()->getId();
            $obj['Item_ref'] = $orderDetail->getItem()->getRef();
            $obj['Quantity'] = $orderDetail->getQuantity();
            $obj['Quantity_delivery'] = $orderDetail->getQuantityDelivery();
            $obj['Quantity_current'] = $orderDetail->getQuantityRecieved();
            $obj['Price'] = $orderDetail->getItemSellPrice();
            $obj['Price_purchase'] = $orderDetail->getItemPurchasePrice();
            $obj['Comment_Purchase_Price'] = '';
            $obj['Rank'] = 1;
        }
        else{
            $obj['ID'] = 0;
            $obj['OrderId'] = 0;
            $obj['ItemId'] = 0;
            $obj['Item_ref'] =  '';
            $obj['Quantity'] = 0;
            $obj['Quantity_delivery'] =0;
            $obj['Quantity_current'] = 0;
            $obj['Price'] = 0;
            $obj['Price_purchase'] = 0;
            $obj['Comment_Purchase_Price'] = '';
            $obj['Rank'] = 0;
        }
        return $obj;
    }

    public function convertQuantityDeliveyToObject(?QuantityDelivery $qtDelivery)
    {
        $obj = [];
        if (!empty($qtDelivery)) {

            $obj['ID'] = $qtDelivery->getId();
            $obj['DeliveryId'] = $qtDelivery->getDelivery()->getId();
            $obj['Item_ref'] = $qtDelivery->getOrderDetail()->getItem()->getRef();
            $obj['Quantity_delivery'] = $qtDelivery->getOrderDetail()->getQuantityDelivery();
        }
        else{
            $obj['ID'] = 0;
            $obj['DeliveryId'] = 0;
            $obj['Item_ref'] =  '';
            $obj['Quantity_delivery'] =  0;
        }
        return $obj;
    }

    /*______________________________________________________________________________________________________ 
    */

    private function convertStatusToObject($status){

        switch($status) {
            case 'STATUS_QUOTE':
                return 'devis';
            case 'STATUS_ORDER':
                return 'commande';
            case 'STATUS_CLOSED':
                return 'close';
            case 'STATUS_BILL':
                return 'facturation';
        }
    }

    private function convertDeliveryStatusToObject($status){

        switch($status) {
            case 'STATUS_QUOTE':
                return 'f';
            // case 'STATUS_ORDER':
            //     return 'commande';
            // case 'STATUS_CLOSED':
            //     return 'close';
            // case 'STATUS_BILL':
            //     return 'facturation';
        }
    }

}