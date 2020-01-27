<?php

namespace App\Services;

class Mailer{

    protected $custMailer;
    protected $setting;

    public function __construct(\Swift_Mailer $custMailer, SettingManager $setting)
    {
        $this->custMailer = $custMailer;
        $this->setting = $setting;
    }

    private function getMessage($to, $subject, $body, $contentType = 'text/html'){
        $message = (new \Swift_Message($subject))
            ->setFrom($this->setting->get('SOCIETE', 'email_validation')->getValue())
            ->setTo($to)
            ->setBody($body, $contentType);
        return $message;    
    }

    private function getMessageAttachedFile($message, $fileFullPath, $FileType = 'application/pdf'){
        $attachment = \Swift_Attachment::fromPath($fileFullPath, $FileType);            
        $message->attach($attachment);
        return $message;
    }

    public function send($to, $subject, $body){
        $message = $this->getMessage($to, $subject, $body);
        $this->custMailer->send($message);
    }

    public function sendAttachedFile($to, $subject, $body, $fileFullPath){
        $message = $this->getMessageAttachedFile($this->getMessage($to, $subject, $body), $fileFullPath);
        $this->custMailer->send($message);
    }
}