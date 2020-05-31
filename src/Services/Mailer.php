<?php

namespace App\Services;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Mailer{

    protected $custMailer;
    protected $phpMailer;
    protected $setting;
    protected $session;
    protected $isWitfmailer;
    protected $url;

    public function __construct(\Swift_Mailer $custMailer, SettingManager $setting,
                                SessionInterface $session,
                                $email_swiftmailer,
                                $email_url)
    {

        $this->custMailer = $custMailer;
        $this->setting = $setting;
        $this->session = $session;
        $this->url = $email_url;
        $this->isWitfmailer = $email_swiftmailer;

        $this->init();
    }

    private function init(){
        $this->phpMailer = new PHPMailer();
        $this->phpMailer->IsHTML(true);
        $this->phpMailer->SMTPAuth = true;
        $this->phpMailer->isSMTP();
        //$this->phpMailer->SMTPDebug = SMTP::DEBUG_CONNECTION; 
        $this->phpMailer->CharSet = PHPMailer::CHARSET_UTF8;

        $urlTab = explode('://',$this->url);
        if($urlTab[0] == "gmail"){
            $this->phpMailer->Host = "smtp.gmail.com";            
            $this->phpMailer->Port = 587;
            $this->phpMailer->SMTPSecure = "tls";            

            $urlSubTab1 = explode(':', $urlTab[1]);
            $this->phpMailer->Username = $urlSubTab1[0];
            $this->phpMailer->Password = explode('@', $urlSubTab1[1])[0];            
        }
        else{
            $urlSubTab1 = explode('?', $urlTab[1]);
            $this->phpMailer->Host = explode(':', $urlSubTab1[0])[0];
            $this->phpMailer->Port = explode(':', $urlSubTab1[0])[1];

            $urlSubTab2 = explode('&', $urlSubTab1[1]);
            foreach($urlSubTab2 as $param){
                if(explode('=', $param)[0] == "encryption")
                    $this->phpMailer->SMTPSecure = explode('=', $param)[1];                
                elseif(explode('=', $param)[0] == "auth_mode")
                    $this->phpMailer->AuthType = explode('=', $param)[1];
                elseif (explode('=', $param)[0] == "username")
                    $this->phpMailer->Username = explode('=', $param)[1];
                elseif (explode('=', $param)[0] == "password")
                    $this->phpMailer->Password = explode('=', $param)[1];
            }
        }
    }

    public function send($Recipients, $subject, $body, Array $files = null, $contentType = 'text/html'){
        
        $isSuccess = true;

        if ($this->isWitfmailer) {
            $isSuccess = $this->sendSwiftMailer($Recipients, $subject, $body, $files, $contentType);
        }
        else{
            $isSuccess = $this->sendPhpMailer($Recipients, $subject, $body, $files, $contentType);
        }

        if (!$isSuccess){
            $this->session->getFlashBag()->add("danger", "Attention: une erreur s'est produite lors de l'envoi du mail!");
            //dump("Mailer Error: " . $this->phpMailer->ErrorInfo);
            //die();
        }
    }

    public function sendSwiftMailer($Recipients, $subject, $body, Array $files = null, $contentType = 'text/html'){
        $message = (new \Swift_Message($subject))
            ->setFrom($this->setting->get('SOCIETE', 'email_validation')->getValue(), $this->setting->get('SOCIETE', 'SOCIETE_NOM')->getValue())
            ->setTo($Recipients['to'])
            ->setBody($body, $contentType);

        if (isset($Recipients['cc'])) {
            foreach ($Recipients['cc'] as $value) {
                if (!empty($value))
                    $message->addCc($value);
            }
        }
        if (!empty($files)) {
            foreach ($files as $fileFullPath) {
                $attachment = \Swift_Attachment::fromPath($fileFullPath, $contentType);
                $message->attach($attachment);
            }
            
        }
        return $this->custMailer->send($message);
    }

    public function sendPhpMailer($Recipients, $subject, $body, Array $files = null, $contentType = 'text/html'){
        //dump($Recipients);die();
        $this->phpMailer->AddAddress($Recipients['to']);
        $this->phpMailer->Subject = $subject;
        $this->phpMailer->Body = $body;
        $this->phpMailer->From = $this->setting->get('SOCIETE', 'email_validation')->getValue();
        $this->phpMailer->FromName = $this->setting->get('SOCIETE', 'SOCIETE_NOM')->getValue();
        $this->phpMailer->addCustomHeader("content-type", $contentType);
        if (isset($Recipients['cc'])) {
            foreach ($Recipients['cc'] as $value) {
                if (!empty($value))
                    $this->phpMailer->addCc($value);
            }
        }

        if (!empty($files)) {
            foreach ($files as $fileFullPath) {
                $this->phpMailer->addAttachment($fileFullPath);
            }
            
        }

        return $this->phpMailer->send();
    }
}