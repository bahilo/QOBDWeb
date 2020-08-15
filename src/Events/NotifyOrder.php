<?php

namespace App\Events;

use App\Services\Mailer;
use App\Services\OrderManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotifyOrder implements EventSubscriberInterface
{
    private $mailer;
    private $manager;
    private $orderManager;

    public function __construct(Mailer $mailer, OrderManager $orderManager, ObjectManager $manager)
    {
        $this->mailer = $mailer;
        $this->orderManager = $orderManager;
        $this->manager = $manager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MyEvents::ORDER_EMAIL_VALIDATION => 'onOrderValidated',
            MyEvents::ORDER_EMAIL_BILL => 'onOrderEmailBill',
            MyEvents::ORDER_CHECK_STOCK => 'onRegisterStockCheck',
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

    public function onRegisterStockCheck(GenericEvent $event): void
    {
        $result = $event->getSubject();
        $class = "\App\Services\\". $result['code']."\StockManager";
        $stockManager = new $class($this->manager, $this->orderManager);
        $stockManager->checkOrderStock($result['order_details']);
    }
}
