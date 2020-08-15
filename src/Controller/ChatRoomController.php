<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Message;
use App\Services\Utility;
use App\Entity\Discussion;
use App\Services\ChatToView;
use App\Services\Serializer;
use App\Services\ChatManager;
use App\Services\SearchToView;
use App\Entity\AgentDiscussion;
use App\Repository\AgentRepository;
use App\Repository\MessageRepository;
use App\Repository\DiscussionRepository;
use App\Repository\AgentDiscussionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChatRoomController extends Controller
{

    protected $serializer;
    protected $chatManager;
    protected $chatToView;
    protected $adRepo;
    protected $search;

    public function __construct(Serializer $serializer, 
                                ChatManager $chatManager, 
                                ChatToView $chatToView,
                                AgentDiscussionRepository $adRepo,
                                SearchToView $search)
    {
        $this->serializer = $serializer;
        $this->chatManager = $chatManager;
        $this->chatToView = $chatToView;
        $this->adRepo = $adRepo;
        $this->search = $search;
    }


    /**
     * @Route("/admin/chat", options={"expose"=true}, name="chat_home")
     */
    public function home()
    {
        return $this->render('chat_room/index.html.twig', [
            'controller_name' => 'ChatRoomController',
        ]);
    }

    /**
     * @Route("/admin/chat/commerciaux", options={"expose"=true}, name="chat_agents")
     */
    public function agents(AgentRepository $agentRepo, Utility $utility)
    {
        return new Response($this->serializer->serialize([
            'object_array' => ['PathAvatarDir' => $this->getParameter('resource.download_dir') . '/agent/avatars', 'agents' => $utility->removeFromArray( $agentRepo->findAll(), $this->getUser())],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/chat/discussion/{id}/messages", options={"expose"=true}, name="chat_discussion_message")
     */
    public function discussionMessage(Discussion $discussion)
    {
        $agent = $this->getUser();

        //reset discussion
        $this->chatManager->setAsCurrentDiscussion($agent, $discussion);
        $this->chatManager->setread($discussion, $this->getUser());
        return new Response($this->serializer->serialize([
            'object_array' => [ 'discussion' => [$discussion], 'messages' => $this->chatManager->hydrateMessage($this->chatToView->messages($discussion))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/chat/discussion/{id}/messages/{nbSkip}/plus/{nbTake}", options={"expose"=true}, name="chat_load_message")
     */
    public function loadMoreMessages(Discussion $discussion, int $nbSkip, int $nbTake)
    {
        $this->chatManager->setread($discussion, $this->getUser());
        return new Response($this->serializer->serialize([
            'object_array' => [                
                'discussion' => [$discussion], 
                'messages' => [
                    'total' => count($this->chatToView->messages($discussion)),
                    'result' => $this->chatManager->hydrateMessage($this->chatToView->messages($discussion, $nbSkip, $nbTake))
                    ]
                ],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/chat/discussion/inscription", options={"expose"=true}, name="chat_discussion_register")
     * @Route("/admin/chat/discussion/{id}/inscription", options={"expose"=true}, name="chat_discussion_edit")
     */
    public function discussionRegister(Discussion $discussion = null, Request $request, ObjectManager $manager, AgentRepository $agentRepo)
    {
        $agents = json_decode($request->query->get('agents'));
        $agents[] = $this->getUser()->getid();

        if(empty($discussion)){
            $discussion = new Discussion();
            $discussion->setCreatedAt(new \DateTime());
        }

        $discussion->setName($request->query->get('name'));
        $manager->persist($discussion);

        //dump($request); die();
        foreach($agents as $id){

            $mAgent = $agentRepo->find($id);

            $ad = $this->adRepo->findOneBy(['discussion' => $discussion, 'agent' => $mAgent]);
            if(empty($ad)){
                $ad = new AgentDiscussion();
            }

            if($mAgent->getId() == $this->getUser()->getId())
                $ad->setIsOwner(true);
            else
                $ad->setIsOwner(false);

            $ad->setDiscussion($discussion);
            $ad->setAgent($mAgent);
            $ad->setIsCurrent(false);
            
            $manager->persist($ad);
        }
        $manager->flush();

        $this->chatManager->setAsCurrentDiscussion($this->getUser(),$discussion);

        //return $this->RedirectToRoute('home');
        
        return new Response($this->serializer->serialize([
            'object_array' => $this->chatManager->hydrateDiscussion([$discussion]),
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/chat/message/{message}/{id}/inscription", options={"expose"=true}, name="chat_message_register")
     */
    public function messageRegister(Discussion $discussion, $message,
                                    DiscussionRepository $discussionRepo,
                                    ObjectManager $manager)
    {
        $msg = new Message();
        if(!empty($message) && !empty($discussion)){
            $msg->setCreatedAt(new \DateTime());
            $msg->setContent($message);
            $msg->setDiscussion($discussion);
            $msg->setAgent($this->getUser());

            $manager->persist($msg);
            $manager->flush();

            $this->chatManager->setUnread($discussion);
        }

        return new Response($this->serializer->serialize([
            'object_array' => $this->chatManager->hydrateMessage([$msg]),
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/chat/discussion/{id}/agent", options={"expose"=true}, name="chat_agent_discusion")
     */
    public function lastDiscussionRegister(Discussion $discussion,
                                    AgentDiscussionRepository $AgentDiscussionRepo,
                                    ChatManager $chatManager,
                                    ObjectManager $manager)
    {
        $agent = $this->getUser();

        //reset discussion
        $chatManager->setAsCurrentDiscussion($agent, $discussion);

        return new Response($this->serializer->serialize([
            'object_array' => $this->chatManager->hydrateDiscussion([$discussion]),
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/chat/agent/{id}/discussion/{id_discussion}", options={"expose"=true}, name="chat_add_agent")
     */
    public function addAgent(Agent $agent,
                                    $id_discussion,
                                    DiscussionRepository $discussionRepo,
                                    AgentDiscussionRepository $AgentDiscussionRepo,
                                    ObjectManager $manager)
    {
        $discussion = $discussionRepo->find($id_discussion);

        $ad = $AgentDiscussionRepo->findOneBy(['agent' => $agent, 'discussion' => $discussion]);
        if(empty($ad)){
            $ad = new AgentDiscussion();            
            $ad->setAgent($agent);
            $ad->setDiscussion($discussion);
            $manager->persist($ad);
            $manager->flush();
        }

        return new Response($this->serializer->serialize([
            'object_array' => [$discussion],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/chat/delete/Discussion/{id}", options={"expose"=true}, name="chat_delete_discussion")
     */
    public function deleteDiscussion(
        Discussion $discussion,
        MessageRepository $messageRepo,
        AgentDiscussionRepository $adRepo,
        ObjectManager $manager
    ) {
        $ad = $adRepo->findOneBy(['agent' => $this->getUser(), 'discussion' => $discussion]);
        $ads = $adRepo->findBy(['discussion' => $discussion]);
        //dump($ads);die();
        if(!empty($ad) && $ad->getIsOwner()){

            // supp. messages
            foreach ($ad->getDiscussion()->getMessages() as $message) {
                $manager->remove($message);
            }
            
            // supp. les liens avec la discussion
            foreach($ads as $agentD){                
                $manager->remove($agentD);
            }

            $manager->remove($ad->getDiscussion());            
            $manager->flush();        
        }

        return $this->redirectToRoute('home');
        //return new Response(json_encode(['status' => '200']));
    }


}
