<?php

namespace App\Events;

use App\Services\Mailer;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotifySubscriber implements EventSubscriberInterface{
    private $mailer;
    private $sender;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
        //$this->sender = $sender;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // le nom de l'event et le nom de la fonction qui sera déclenché
            MyEvents::USER_REGISTERED => 'onUserRegistrated',
        ];
    }

    public function onUserRegistrated(GenericEvent $event): void
    {
        /** @var User $user */
        $user = $event->getSubject();

        $subject = "Bienvenue";
        $body = "Bienvenue mon ami.e sur ce tutorial";

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($user->getEmail())
            ->setFrom($this->sender)
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}