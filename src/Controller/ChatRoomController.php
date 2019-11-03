<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ChatRoomController extends Controller
{
    /**
     * @Route("/chat/room", name="chat_room")
     */
    public function index()
    {
        return $this->render('chat_room/index.html.twig', [
            'controller_name' => 'ChatRoomController',
        ]);
    }
}
