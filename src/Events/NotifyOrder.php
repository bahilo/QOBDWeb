<?php

namespace App\Events;

use App\Services\Mailer;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotifyOrder implements EventSubscriberInterface
{
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
            MyEvents::ORDER_EMAIL_VALIDATION => 'onOrderValidated',
            MyEvents::ORDER_EMAIL_BILL => 'onOrderEmailBill',
        ];
    }

    public function onOrderEmailBill(GenericEvent $event): void
    {
        $result = $event->getSubject();
        $form = $result['form'];

        $this->mailer->send(['to' => $result['to']], $form['subject'], $result['view'], $result['files']);
    }

    public function onOrderValidated(GenericEvent $event): void
    {
        $result = $event->getSubject();
        $this->mailer->send(['to' => $result['to']], $result['subject'], $result['view']);
    }
}
