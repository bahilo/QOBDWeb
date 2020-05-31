<?php

namespace App\Events;

use App\Services\Mailer;
use App\Services\ApiManager;
use App\Services\ErrorHandler;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotifySetting implements EventSubscriberInterface
{
    private $mailer;
    protected $apiManager;
    protected $errorHandler;

    public function __construct(Mailer $mailer, ApiManager $apiManager, ErrorHandler $errorHandler)
    {
        $this->mailer = $mailer;
        $this->apiManager = $apiManager;
        $this->errorHandler = $errorHandler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MyEvents::FTP_TEXT_SEND => 'onTextValidated',
            MyEvents::FTP_IMAGE_SEND => 'onLogoChanged',
        ];
    }

    public function onTextValidated(GenericEvent $event): void
    {
        $error = [];
        $result = $event->getSubject();
        $this->apiManager->ftpOpen();
        foreach($result['files'] as $file){
            if(!$this->apiManager->ftpTextUpload($file))
                $error[] = 'failed';
        }
        $this->apiManager->ftpClose();
               
        if(count($error) == 0)
            $this->errorHandler->success("Fichier(s) enregistré(s) avec succés!");
    }

    public function onLogoChanged(GenericEvent $event): void
    {
        $error = [];
        $result = $event->getSubject();
        $this->apiManager->ftpOpen();
        foreach ($result['files'] as $file) {
            if (!$this->apiManager->ftpImageUpload($file))
                $error[] = 'failed';
        }
        $this->apiManager->ftpClose();

        if (count($error) == 0)
            $this->errorHandler->success("Fichiers enregistrés avec succés!");
        
    }
}
