<?php 

namespace App\Events;

final class MyEvents
{
    /**
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const USER_REGISTERED = 'agent.registered';
    const USER_LOGGEDIN = 'agent.loggedin';
    const USER_LOGGEDOUT = 'agent.loggedout';
    const ORDER_VALIDATION = 'order.validation';
    const ORDER_EMAIL_BILL = 'order.email.bill';
}