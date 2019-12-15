<?php

namespace App\Services;

use App\Entity\Message;
use App\Entity\Discussion;
use App\Repository\AgentRepository;
use App\Repository\MessageRepository;
use App\Repository\DiscussionRepository;
use App\Repository\AgentDiscussionRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChatToView{

    protected $agentRepo;
    protected $discussionRepo;
    protected $messageRepo;
    protected $token;
    protected $adRepo;

    public function __construct(AgentRepository $agentRepo,  
                                DiscussionRepository $discussionRepo,
                                MessageRepository $messageRepo,
                                AgentDiscussionRepository $adRepo,
                                TokenStorageInterface $tokenStorage)
    {
        $this->agentRepo = $agentRepo;
        $this->discussionRepo = $discussionRepo;
        $this->messageRepo = $messageRepo;
        $this->token = $tokenStorage->getToken();
        $this->adRepo = $adRepo;
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

    public function discussion_message(Message $message)
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

    public function messages($discussion)
    {
        if(!empty($discussion))
            return $this->messageRepo->findByDiscussion( $discussion);
        return [];
    }

    public function current_discussion_messages()
    {
        $discussion = $this->last_discussion();
        if(!empty($discussion))
            return $this->messageRepo->findByDiscussion($discussion);
        return [];
    }

    public function unread_messages()
    {
        return $this->messageRepo->findUnReadByAgent($this->token->getUser());
    }
    
}