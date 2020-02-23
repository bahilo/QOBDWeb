<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    /**
     * @Route("/", name="blog")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/blog/article", name="blog_article_home")
     */
    public function indexArticle()
    {
        return $this->render('blog/articles.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/blog/article/show/{id}", name="blog_article_show")
     */
    public function showArticle(Article $article)
    {
        return $this->render('blog/articles.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/blog/team", name="blog_team")
     */
    public function team()
    {
        return $this->render('blog/articles.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/blog/products", name="blog_product")
     */
    public function products()
    {
        return $this->render('blog/articles.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
    
}
