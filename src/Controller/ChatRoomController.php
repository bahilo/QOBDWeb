<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Message;
use App\Entity\Discussion;
use App\Services\Serializer;
use App\Services\ChatManager;
use App\Entity\AgentDiscussion;
use App\Repository\MessageRepository;
use App\Repository\DiscussionRepository;
use App\Repository\AgentDiscussionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;

class ChatRoomController extends Controller
{

    protected $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;

    }

    /**
     * @Route("/admin/chat", name="chat_home")
     */
    public function home()
    {
        return $this->render('chat_room/index.html.twig', [
            'controller_name' => 'ChatRoomController',
        ]);
    }

    /**
     * @Route("/admin/chat/discussion/${name}/inscription", options={"expose"=true}, name="chat_discussion_register")
     */
    public function discussionRegister(?string $name, ObjectManager $manager, ChatManager $chatManager)
    {
        $agent = $this->getUser();
        $ad = new AgentDiscussion();
        $discussion = new Discussion();
        $discussion->setCreatedAt(new \DateTime());
        $discussion->setName($name);

        $ad->setIsOwner(true);
        $ad->setDiscussion($discussion);
        $ad->setAgent($agent);
        $ad->setIsCurrent(false);

        $manager->persist($discussion);
        $manager->persist($ad);
        $manager->flush();

        $chatManager->setAsCurrentDiscussion($agent, $discussion);

        //return $this->RedirectToRoute('home');
        
        return new Response($this->serializer->serialize([
            'object_array' => $chatManager->hydrateDiscussion([$discussion]),
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/chat/message/inscription", options={"expose"=true}, name="chat_message_register")
     */
    public function messageRegister(Request $request,
                                    DiscussionRepository $discussionRepo,
                                    ObjectManager $manager)
    {
        $form = $request->request->get('chat_room');
        if(!empty($form)){
            $discussion = $discussionRepo->find($form["discussion"]);
            $message = new Message();
            $message->setIsRed(false);
            $message->setCreatedAt(new \DateTime());
            $message->setContent($form['message']);
            $message->setDiscussion($discussion);
            $message->setAgent($this->getUser());

            $manager->persist($message);
            $manager->flush();
        }

        return new Response($this->serializer->serialize([
            'object_array' => [$message],
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
            'object_array' => [$discussion],
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
