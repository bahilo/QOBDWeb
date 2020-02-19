<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class BlogAdminController extends Controller
{
    /**
     * @Route("/blog/admin", name="blog_admin")
     */
    public function index()
    {
        return $this->render('blog_admin/index.html.twig', [
            'controller_name' => 'BlogAdminController',
        ]);
    }
}
