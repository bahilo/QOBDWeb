<?php

namespace App\Services;

use App\Entity\Agent;
use App\Entity\Action;
use App\Events\MyEvents;
use App\Repository\AgentDiscussionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class SecurityManager implements LogoutHandlerInterface, AuthenticationSuccessHandlerInterface {

    protected $token;
    protected $manager;
    protected $container;
    protected $adRepo;
    protected $eventDispatcher;

    public function __construct(AgentDiscussionRepository $adRepo,
                                ObjectManager $manager, 
                                Security $tokenStorage, 
                                ContainerInterface $container,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->adRepo = $adRepo;
        $this->token = $tokenStorage;
        $this->manager = $manager;
        $this->container = $container;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {        
        $this->token = $token;
        $event = new GenericEvent(['agent' => $this->token->getUser() ]);
        $this->eventDispatcher->dispatch(MyEvents::USER_LOGGEDIN, $event);
        return new RedirectResponse($this->container->get('router')->generate('home'));
    }

    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $this->token = $token;
        $event = new GenericEvent(['agent' => $this->token->getUser()]);
        $this->eventDispatcher->dispatch(MyEvents::USER_LOGGEDOUT, $event);
    }

    public function isAccessGranted()
    {
        $agent = $this->token->getUser();
        if (empty($agent) || !$agent->getIsActivated()) {
            return false;
        }
        return true;
    }

    public function checkHas(Action $action, $privilege)
    {
        $agent = $this->token->getUser();
        if ($this->isAccessGranted($agent)) {
            foreach ($agent->getObjectRoles() as $role) {
                foreach ($role->getActionRoles() as $actionRole) {
                    $arAction = $actionRole->getAction();
                    if (!empty($arAction) && !empty($action) && $arAction->getId() == $action->getId()) {
                        if ($privilege == 'READ' && $actionRole->getPrivilege()->getIsRead())
                            return true;
                        elseif ($privilege == 'UPDATE' && $actionRole->getPrivilege()->getIsUpdate())
                            return true;
                        elseif ($privilege == 'DELETE' && $actionRole->getPrivilege()->getIsDelete())
                            return true;
                        elseif ($privilege == 'WRITE' && $actionRole->getPrivilege()->getIsWrite())
                            return true;
                        elseif ($privilege == 'MAIL' && $actionRole->getPrivilege()->getIsSendMail())
                            return true;
                    }
                }
            }
        }
        return false;
    }

    public function checkHasRead(Action $action)
    {
        return $this->checkHas($action, 'READ');
    }

    public function checkHasWrite(Action $action)
    {
        return $this->checkHas($action, 'WRITE');
    }

    public function checkHasUpdate(Action $action)
    {
        return $this->checkHas($action, 'UPDATE');
    }

    public function checkHasDelete(Action $action)
    {
        return $this->checkHas($action, 'DELETE');
    }

    public function checkHasEmail(Action $action)
    {
        return $this->checkHas($action, 'MAIL');
    }

    public function checkIsAdmin()
    {
        return $this->token->getUser()->getIsAdmin();
    }

    public function hydrateAgent(array $agents)
    {
        $result = [];
        foreach ($agents as $agent) {
            //dump($discussion);die();
            
            $unread = 0;
            $ads = $this->adRepo->findBy(['agent' => $agent]);
            foreach ($ads as $ad) {
                $ur =  $ad->getUnread();
                if(!empty($ur) && $ur > 0)
                    $unread += $ur;
            }
            //dump($agent); die();
            $agent->setTotalUnRead($unread);

            if(empty($agent->getPicture()))
                $agent->setPicture('default.png');

            $result[] = $agent;
        }
        return $result;
    }

}