<?php

namespace App\Events;

use App\Entity\Agent;
use App\Services\Mailer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotifySecurity implements EventSubscriberInterface
{
    private $mailer;
    protected $securityToken;
    private $manager;
    private $maxIdleTime;
    protected $session;
    protected $router;
    protected $eventDispatcher;

    public function __construct(Mailer $mailer, 
                                 $maxIdleTime = 0,
                                ObjectManager $manager,
                                TokenStorageInterface $securityToken, 
                                SessionInterface $session,
                                RouterInterface $router,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->manager = $manager;
        $this->session = $session;
        $this->maxIdleTime = $maxIdleTime;
        $this->eventDispatcher = $eventDispatcher;
        $this->securityToken = $securityToken;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MyEvents::USER_LOGGEDIN => 'onAgentLoggedIn',
            MyEvents::USER_LOGGEDOUT => 'onAgentLoggedOut',
            KernelEvents::REQUEST =>  'onKernelRequest',
        ];
    }

    public function onAgentLoggedIn(GenericEvent $event): void
    {
        /** @var Agent $user */
        $agent = $event->getSubject()['agent'];

        $agent->setIsOnline(true);
        $agent->setLoggedAt(new \DateTime());        

        $this->manager->persist($agent);
        $this->manager->flush();

    }

    public function onAgentLoggedOut(GenericEvent $event): void
    {
        /** @var Agent $user */
        $agent = $event->getSubject()['agent'];

        $agent->setIsOnline(false);
        $this->manager->persist($agent);
        $this->manager->flush();

    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {

            return;
        }

        if ($this->maxIdleTime > 0) {

            $this->session->start();
            $lapse = time() - $this->session->getMetadataBag()->getLastUsed();

            if ($lapse > $this->maxIdleTime) {

                $event = new GenericEvent(['agent' => $this->securityToken->getToken()->getUser()]);
                $this->eventDispatcher->dispatch(MyEvents::USER_LOGGEDOUT, $event);

                $this->securityToken->setToken(null);
                $this->session->getFlashBag()->add('info', 'You have been logged out due to inactivity.');
                
                return new RedirectResponse($this->router->generate('security_login'));
            }
        }
    }
}
