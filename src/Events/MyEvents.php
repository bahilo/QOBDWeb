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
    const USER_REGISTRATION_SEND_EMAIL = 'agent.email.register';
    const ORDER_EMAIL_VALIDATION = 'order.email.validation';
    const ORDER_EMAIL_BILL = 'order.email.bill';
    const ORDER_CHECK_STOCK = 'order.stock.check';
    const FTP_TEXT_SEND = 'ftp.text';
    const FTP_EMAIL_SEND = 'ftp.email';
    const FTP_IMAGE_SEND = 'ftp.image';
}