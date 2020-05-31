<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Repository\SettingRepository;
use App\Repository\CurrencyRepository;
use Exception;
use Symfony\Component\HttpClient\HttpClient;

class ApiManager{

    protected $connectionId;
    protected $loginOK;
    protected $messageArray;
    protected $nusoapClient;
    protected $currencyClient;
    protected $settingManager;
    protected $currencyRepo;
    protected $ErrorHandler;
    protected $extensions;
    protected $code;
    protected $ftpPathDirLogo;
    protected $ftpPathDirText;
    protected $ftpPathDirMail;

    public function __construct(SettingManager $settingManager, 
                                CurrencyRepository $currencyRepo,
                                ErrorHandler $ErrorHandler){

        $this->loginOK = false;
        $this->messageArray = [];
        $this->code = 'WEBSERVICE';
        $this->ftpPathDirLogo = 'Ressources\Image';
        $this->ftpPathDirText = 'Language\fr\Files\texts';
        $this->ftpPathDirMail = 'Language\fr\Files\mails';
        $this->extensions = ['txt','csv'];
        $this->settingManager = $settingManager;
        $this->currencyRepo = $currencyRepo;
        $this->ErrorHandler = $ErrorHandler;
    }

    private function initNuSopClient(){
        $settingPDF = $this->settingManager->get($this->code, 'QOBD_URL');
        $settingUserName = $this->settingManager->get($this->code, 'QOBD_LOGIN');
        $settingPassword = $this->settingManager->get($this->code, 'QOBD_PASSWORD');

        if(!empty($settingPDF)){
            $this->nusoapClient = new \nusoap_client($settingPDF->getValue(), true);
            if(!empty($settingUserName) && !empty($settingPassword))
                $this->nusoapClient->setCredentials($settingUserName->getValue(), $settingPassword->getValue(), 'basic');
        }
    }

    public function execCurrencyRequest($currencySymbol){
        $currencyURL = $this->settingManager->get($this->code, 'CURRENCY_URL');
        $token = $this->settingManager->get($this->code, 'CURRENCY_TOKEN');

        $currency = $this->currencyRepo->findOneBy(['IsDefault' => true]);
        $this->currencyClient = new Client();
        $response = $this->currencyClient->request('GET', $currencyURL->getValue() . $token->getValue() . '&base='. $currency->getSymbol() .'&symbols='.$currencySymbol);
        $data = \json_decode($response->getBody());
        //dump($data->rates->{$currencySymbol});die();
        return $data->rates->{$currencySymbol};          
    }

    public function execPdfRequest($method, $params){
        $this->initNuSopClient();
        $settingPDF = $this->settingManager->get($this->code, 'QOBD_URL');
        if(!empty($this->nusoapClient)){
            //dump($params);
            $result = $this->nusoapClient->call($method, $params, $settingPDF->getValue());
            //dump($this->nusoapClient);die();
            return $result;
        }            
    }

#region [ FTP ]

    /**
     * FTP connection to the remote server
     */
    private function connect($server, $ftpUser, $ftpPassword, $isPassive = false){
        $this->connectionId = ftp_connect($server);
        $loginResult = ftp_login($this->connectionId, $ftpUser, $ftpPassword);
        ftp_pasv($this->connectionId, $isPassive);
        if(empty($this->connectionId) || empty($loginResult)){
            $this->ErrorHandler->error("FTP: Echec de la connection vers le server de fichier!");
            return false;
        }
        $this->loginOK = true;
        return true;
    }

    /**
     * FTP directory creation
     */
    public function makeDir($directory){        
        $result = \ftp_mkdir($this->connectionId, $directory);
        if(!empty($result)){
            $this->ErrorHandler->error("FTP: Echec de l'action de création sur le server de fichier!");
            return false;
        }
        return true;
    }

    /**
     * FTP listing remote directory
     */
    public function getDirListing($directory = '.', $parameters = '-a'){        
        $result = ftp_nlist($this->connectionId,$parameters.' '. $directory);
        return $result;
    }

    /**
     * FTP upload texts
     */
    public function ftpEmailUpload($file, $IsAutoOpenCloseConnection = false){    
        if($IsAutoOpenCloseConnection)
            $this->ftpOpen();
        $result = $this->uploadFile($file, $this->ftpPathDirMail.'/'.basename($file));
        if ($IsAutoOpenCloseConnection)
            $this->ftpClose();   
        return $result;
    }

    /**
     * FTP upload texts
     */
    public function ftpTextUpload($file, $IsAutoOpenCloseConnection = false){
        if ($IsAutoOpenCloseConnection)
            $this->ftpOpen();
        $result = $this->uploadFile($file, $this->ftpPathDirText.'/'.basename($file));
        if ($IsAutoOpenCloseConnection)
            $this->ftpClose();
        return $result;
    }

    /**
     * FTP upload images
     */
    public function ftpImageUpload($file, $IsAutoOpenCloseConnection = false){
        if ($IsAutoOpenCloseConnection)
            $this->ftpOpen();
        $result = $this->uploadFile($file, $this->ftpPathDirLogo . '/' . basename($file));
        if ($IsAutoOpenCloseConnection)
            $this->ftpClose();
        return $result;
    }

    /**
     * FTP connection close
     */
    public function ftpClose()
    {
        if ($this->connectionId)
            ftp_close($this->connectionId);
    }

    /**
     * FTP connection open
     */
    public function ftpOpen()
    {
        if (empty($this->connectionId))
            $this->initFTPConnection(true);
    }

    /*===================================================================================== 
    ====================================[ Private ]======================================*/

    /**
     * FTP connection init
     */
    private function initFTPConnection($passiveMode = false)
    {
        $this->connect(
            $this->settingManager->get($this->code, 'FTP_SERVER')->getValue(),
            $this->settingManager->get($this->code, 'FTP_USER')->getValue(),
            $this->settingManager->get($this->code, 'FTP_PASSWORD')->getValue(),
            $passiveMode
        );
    }

    /**
     * FTP file upload
     */
    private function uploadFile($fileFrom, $fileTo)
    {
        try{
            $fileTab = explode('.', $fileFrom);
            $ext = end($fileTab);
            $mode = FTP_BINARY;
            if (in_array($ext, $this->extensions))
                $mode = FTP_ASCII;
            $upload = \ftp_put($this->connectionId, $fileTo, $fileFrom, $mode);
            if (empty($upload)) {
                $this->ErrorHandler->error("FTP: Echec du tranfert du fichier sur le server de fichier!");
                return false;
            }
        }catch(Exception $ex){
            $this->ErrorHandler->error("FTP: Echec du tranfert du fichier sur le server de fichier!");
        }
        return true;
    }

    /**
     * FTP file download
     */
    private function downloadFile($fileFrom, $fileTo)
    {
        if (!$this->loginOK)
            $this->initFTPConnection(true);

        $fileTab = explode('.', $fileFrom);
        $ext = end($fileTab);
        $mode = FTP_BINARY;
        if (in_array($ext, $this->extensions))
            $mode = FTP_ASCII;

        $upload = \ftp_get($this->connectionId, $fileTo, $fileFrom, $mode, 0);
        $this->ftpClose();
        if (empty($upload)) {
            $this->ErrorHandler->error("FTP: Echec téléchargement du fichier du server de fichier!");
            return false;
        }
        return true;
    }


#endregion

}