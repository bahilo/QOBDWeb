<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\QuoteOrder;
use App\Services\Serializer;
use App\Services\SearchToView;
use App\Repository\ItemRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends Controller
{
    protected $serializer;
    protected $search;

    public function __construct(
        Serializer $serializer,
        SearchToView $search
    ) {
        $this->serializer = $serializer;
        $this->search = $search;
    }


    /**
     * @Route("/admin/panier", options={"expose"=true}, name="cart_home")
     */
    public function home(ItemRepository $itemRepo, SessionInterface $session)
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

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/cart/index.html.twig', [
            'items' => $items,
            'total' => $totalG,
        ]);
    }

    /**
     * @Route("/admin/panier/ajout/{id}/{quantity}", options={"expose"=true}, name="cart_add")
     */
    public function add( $id, $quantity, SessionInterface $session )
    {        
        $this->addToCart($id, $quantity, $session);
        return $this->RedirectToRoute('catalogue_home');
    }

    /**
     * @Route("/admin/panier/devis/ajout/{id}/{quantity}", options={"expose"=true}, name="cart_quote_add")
     */
    public function addToQuote($id, $quantity, SessionInterface $session )
    {        
        $panier = $this->addToCart($id, $quantity, $session);
        return new Response($this->serializer->serialize([
            'object_array' => ['code' => 200, 'total' => count($panier)],
            'format' => 'json'
        ]));
    }

    /**
     * @Route("/admin/panier/vider", options={"expose"=true}, name="cart_reset")
     */
    public function clear(SessionInterface $session )
    {
        $panier = $session->get('panier',[]);

        if(!empty($panier))
            $session->set('panier', []);

        return new Response($this->serializer->serialize([
            'object_array' => ['code' => 200],
            'format' => 'json'
        ]));
    }

    /**
     * @Route("/admin/panier/delete/{id}", options={"expose"=true}, name="cart_delete")
     */
    public function delete( $id, SessionInterface $session )
    {
        $panier = $session->get('panier',[]);

        if(!empty($panier[$id]))
            unset($panier[$id] );

        $session->set('panier', $panier);
        
        return $this->RedirectToRoute('cart_home');
    }



    /*___________________________________________________________*/

    private function addToCart($id, $quantity, SessionInterface $session){
        $panier = $session->get('panier', []);

        if (!empty($panier[$id]))
            $panier[$id] = $panier[$id] + $quantity;
        else
            $panier[$id] = $quantity;

        $session->set('panier', $panier);
        return $panier;
    }
    
}
