<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Repository\SettingRepository;
use App\Repository\CurrencyRepository;
use Symfony\Component\HttpClient\HttpClient;

class ApiManager{

    protected $nusoapClient;
    protected $currencyClient;
    protected $settingManager;
    protected $currencyRepo;

    public function __construct(SettingManager $settingManager, CurrencyRepository $currencyRepo){

        $this->settingManager = $settingManager;
        $this->currencyRepo = $currencyRepo;
    }

    private function initNuSopClient(){
        $settingPDF = $this->settingManager->get('WEBSERVICE', 'QOBD_URL');
        $settingUserName = $this->settingManager->get('WEBSERVICE', 'QOBD_LOGIN');
        $settingPassword = $this->settingManager->get('WEBSERVICE', 'QOBD_PASSWORD');

        if(!empty($settingPDF)){
            $this->nusoapClient = new \nusoap_client($settingPDF->getValue(), true);
            if(!empty($settingUserName) && !empty($settingPassword))
                $this->nusoapClient->setCredentials($settingUserName->getValue(), $settingPassword->getValue(), 'basic');
        }
    }

    public function execCurrencyRequest($currencySymbol){
        $currencyURL = $this->settingManager->get('WEBSERVICE', 'CURRENCY_URL');
        $token = $this->settingManager->get('WEBSERVICE', 'CURRENCY_TOKEN');

        $currency = $this->currencyRepo->findOneBy(['IsDefault' => true]);
        $this->currencyClient = new Client();
        $response = $this->currencyClient->request('GET', $currencyURL->getValue() . $token->getValue() . '&base='. $currency->getSymbol() .'&symbols='.$currencySymbol);
        $data = \json_decode($response->getBody());
        //dump($data->rates->{$currencySymbol});die();
        return $data->rates->{$currencySymbol};          
    }

    public function execPdfRequest($method, $params){
        $this->initNuSopClient();
        $settingPDF = $this->settingManager->get('WEBSERVICE', 'QOBD_URL');
        if(!empty($this->nusoapClient)){
            $result = $this->nusoapClient->call($method, $params, $settingPDF->getValue());
            //dump($this->nusoapClient);die();
            return $result;
        }            
    }



}