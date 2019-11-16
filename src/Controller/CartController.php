<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends Controller
{
    /**
     * @Route("/admin/cart", options={"expose"=true}, name="cart_home")
     * @Route("/admin/cart/error/{message}", options={"expose"=true}, name="cart_home_error")
     */
    public function home($message = null, ItemRepository $itemRepo, SessionInterface $session)
    {
         $panier = $session->get('panier');
         $items = [];
         $totalG = 0;
         
         if(!empty($panier)){
             
             foreach($panier as $id => $quantity){
                $items[] = [
                    'product' => $itemRepo->find($id),
                    'quantity' => $quantity
                ];
             }
    
             foreach($items as $item){
                $totalG += $item['product']->getSellPrice() * $item['quantity'];
             }
        }

        return $this->render('cart/index.html.twig', [
            'items' => $items,
            'total' => $totalG,
            'error_message' => $message
        ]);
    }

    /**
     * @Route("/admin/cart/add/{id}/{quantity}", options={"expose"=true}, name="cart_add")
     */
    public function add( $id, $quantity, SessionInterface $session )
    {
        $panier = $session->get('panier',[]);

        // dump($panier);
        // dump($quantity);
        // die();
        if(!empty($panier[$id]))
            $panier[$id] = $panier[$id] + $quantity;
        else
            $panier[$id] = $quantity;
        
        $session->set('panier', $panier);
        
        return $this->RedirectToRoute('catalogue_home');
    }

    /**
     * @Route("/admin/cart/delete/{id}", options={"expose"=true}, name="cart_delete")
     */
    public function delete( $id, SessionInterface $session )
    {
        $panier = $session->get('panier',[]);

        if(!empty($panier[$id]))
            unset($panier[$id] );

        $session->set('panier', $panier);
        
        return $this->RedirectToRoute('cart_home');
    }
    
}
