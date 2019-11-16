<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AlerteController extends Controller
{
    /**
     * @Route("/admin/alerte", name="alerte")
     */
    public function index()
    {
        return $this->render('alerte/index.html.twig', [
            'controller_name' => 'AlerteController',
        ]);
    }
}
