<?php

namespace App\Services;

use App\Entity\Agent;
use App\Entity\Message;
use App\Entity\Discussion;
use App\Services\ChatManager;
use App\Repository\AgentRepository;
use App\Repository\MessageRepository;
use App\Repository\DiscussionRepository;
use App\Repository\AgentDiscussionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChatToView{

    protected $agentRepo;
    protected $discussionRepo;
    protected $messageRepo;
    protected $token;
    protected $adRepo;
    protected $manager;
    protected $securityManager;

    public function __construct(AgentRepository $agentRepo,  
                                DiscussionRepository $discussionRepo,
                                MessageRepository $messageRepo,
                                SecurityManager $securityManager,
                                ObjectManager $manager,
                                AgentDiscussionRepository $adRepo,
                                TokenStorageInterface $tokenStorage)
    {
        $this->manager = $manager;
        $this->agentRepo = $agentRepo;
        $this->discussionRepo = $discussionRepo;
        $this->messageRepo = $messageRepo;
        $this->token = $tokenStorage->getToken();
        $this->adRepo = $adRepo;
        $this->securityManager = $securityManager;
    }

    public function discussions()
    { 
        return $this->discussionRepo->findByAgent($this->token->getUser());
    }

    public function discussion_owner($discussion)
    { 
        $ad = $this->adRepo->findOneBy(['discussion' => $discussion, 'isOwner' => true]);
        if(!empty($ad)){
            return $ad->getAgent();
        }
        return null;
    }

    public function discussion_users(Discussion $discussion)
    { 
        if(!empty($discussion))
            return $this->agentRepo->findByDiscussion($discussion);
        return [];
    }

    public function message_discussion(Message $message)
    { 
        if(!empty($message))
            return $this->discussionRepo->findByMessageAgent($this->token->getUser(), $message);
        return [];
    }

    public function last_discussion() : ?Discussion
    { 
        $ad = $this->adRepo->findOneBy(['agent' => $this->token->getUser(), 'isCurrent' => true]);
        if(!empty($ad)){
            return $ad->getDiscussion();
        }
        return null;
    }

    public function messages($discussion, int $nbSkip = 0, int $nbTake = 0)
    {
        if(!empty($discussion))
            return $this->messageRepo->findByDiscussion( $discussion, $nbSkip, $nbTake);
        return [];
    }

    public function current_discussion_messages()
    {
        $discussion = $this->last_discussion();

        if(!empty($discussion)){            
            $this->setread($discussion, $this->token->getUser());
            return $this->messageRepo->findByDiscussion($discussion);
        }
        return [];
    }

    public function unread_messages()
    {
        $agent = $this->securityManager->hydrateAgent([$this->token->getUser()])[0];

        return $agent->getTotalUnRead();
    }

    private function setread(Discussion $discussion, Agent $agent)
    {
        if(!empty($discussion)){
            $ad = $this->adRepo->findOneBy(['discussion' => $discussion, 'agent' => $agent]);
            $ad->setUnread(0);
            $this->manager->persist($ad);
            $this->manager->flush();
        }
    }
    
}