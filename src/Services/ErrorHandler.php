<?php


namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorHandler{

    protected $logger;
    protected $session;

    public function __construct(LoggerInterface $logger, SessionInterface $session)
    {
        $this->logger = $logger;
        $this->session = $session;
    }

    function registerError(ConstraintViolationListInterface $errors)
    {
        //dump($errors);die();
        foreach ($errors as $error) {
            $this->error($error->getMessage());
            //$this->logger->error($error->getMessage());
        }
    }

    function success(string $message)
    {
        $this->session->getFlashBag()->add("success", $message);
    }

    function error(string $message)
    {
        $this->session->getFlashBag()->add("danger", $message);
        $this->logger->error($message);
    }

    function warning(string $message)
    {
        $this->session->getFlashBag()->add("warning", $message);
        $this->logger->warning($message);
    }

    function info(string $message)
    {
        $this->session->getFlashBag()->add("info", $message);
        $this->logger->info($message);
    }
}