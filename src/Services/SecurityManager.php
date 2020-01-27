<?php

namespace App\Services;

use App\Entity\Agent;
use App\Entity\Action;
use App\Repository\AgentDiscussionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class SecurityManager implements LogoutHandlerInterface, AuthenticationSuccessHandlerInterface {

    protected $token;
    protected $manager;
    protected $container;
    protected $adRepo;

    public function __construct(AgentDiscussionRepository $adRepo,ObjectManager $manager, TokenStorageInterface $tokenStorage, ContainerInterface $container)
    {
        $this->adRepo = $adRepo;
        $this->token = $tokenStorage->getToken();
        $this->manager = $manager;
        $this->container = $container;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {        
        $this->token = $token;
        $this->connectUser(true);
        return new RedirectResponse($this->container->get('router')->generate('home'));
    }

    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $this->token = $token;
        $this->connectUser(false); 
    } 
    
    public function connectUser(bool $isConnected)
    {
        $agent = $this->token->getUser();
        if($isConnected){
            $agent->setIsOnline(true);        
            $agent->setLoggedAt(new \DateTime());
        }
        else
            $agent->setIsOnline(false);

        $this->manager->persist($agent);
        $this->manager->flush();
    }

    public function isAccessGranted(Agent $agent)
    {
        if (empty($agent) || !$agent->getIsActivated()) {
            return false;
        }
        return true;
    }

    public function checkHas(Agent $agent, Action $action, $privilege)
    {
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

    public function checkHasRead(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'READ');
    }

    public function checkHasWrite(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'WRITE');
    }

    public function checkHasUpdate(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'UPDATE');
    }

    public function checkHasDelete(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'DELETE');
    }

    public function checkHasEmail(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'MAIL');
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