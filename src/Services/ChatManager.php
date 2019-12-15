<?php 

namespace App\Services;

use App\Entity\Agent;
use App\Services\Utility;
use App\Entity\Discussion;
use App\Services\ChatToView;
use App\Repository\AgentRepository;
use App\Repository\MessageRepository;
use App\Repository\DiscussionRepository;
use App\Repository\AgentDiscussionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChatManager{

    protected $adRepo;
    protected $discussionRepo;
    protected $messageRepo;
    protected $agentRepo;
    protected $manager;
    protected $token;
    protected $chatUtility;
    protected $avatar_dir;
    protected $utility;

    public function __construct(AgentDiscussionRepository $adRepo,
                                DiscussionRepository $discussionRepo,
                                MessageRepository $messageRepo,
                                AgentRepository $agentRepo,
                                ObjectManager $manager,
                                TokenStorageInterface $tokenStorage,
                                ChatToView $chatUtility,
                                Utility $utility,
                                $avatar_dir)
    {
        $this->adRepo = $adRepo;
        $this->discussionRepo = $discussionRepo;
        $this->messageRepo = $messageRepo;
        $this->agentRepo = $agentRepo;
        $this->manager = $manager;
        $this->token = $tokenStorage->getToken();
        $this->chatUtility = $chatUtility;
        $this->avatar_dir = $avatar_dir;
        $this->utility = $utility;
    }

    public function setAsCurrentDiscussion(Agent $agent, Discussion $discussion ){
        foreach ($this->adRepo->findBy(['agent' => $agent, 'isCurrent' => true]) as $ad) {
            $ad->setIsCurrent(false);
            $this->manager->persist($ad);
        }

        $ad = $this->adRepo->findOneBy(['agent' => $agent, 'discussion' => $discussion]);
        $ad->setIsCurrent(true);

        $this->manager->persist($ad);
        $this->manager->flush();
    }

    public function hydrateDiscussion(array $discussions){
        $result = [];
        foreach($discussions as $discussion){
            //dump($discussion);die();
            $discussion->setOwner($this->chatUtility->discussion_owner($discussion));
            foreach ($this->chatUtility->discussion_users($discussion) as $da) {
                $discussion->addRecipient($da);
            }
            $discussion->setPathAvatarDir($this->avatar_dir);
            $discussion->setCreatedAtShort($this->utility->getWhenFromToday($discussion->getCreatedAt()));
            $result[] = $discussion;
        }
        return $result;
    }




}