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
            MyEvents::ORDER_VALIDATION => 'onOrderValidated',
            MyEvents::ORDER_EMAIL_BILL => 'onOrderValidated',
        ];
    }

    public function onOrderEmailBill(GenericEvent $event): void
    {
        /** @var User $user */
        $result = $event->getSubject();

        $contact = $result['contact'];
        $form = $result['form'];
        $bill = $result['bil'];
        $file = $result['file'];
        $view = $result['view'];

        $this->mailer->sendAttachedFile($contact->getEmail(), $form['subject'], $view, $file);
    }
}
