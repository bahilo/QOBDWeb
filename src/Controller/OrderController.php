<?php

namespace App\Controller;

use App\Entity\QuoteOrder;
use App\Entity\OrderStatus;
use App\Services\OrderHydrate;
use App\Entity\QuoteOrderDetail;
use App\Services\QOBDSerializer;
use App\Repository\TaxRepository;
use App\Repository\ItemRepository;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializerInterface;
use App\Repository\QuoteOrderRepository;
use JMS\Serializer\SerializationContext;
use App\Form\OrderStatusRegistrationType;
use App\Repository\OrderStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\QuoteOrderDetailRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends Controller
{
    /**
     * @Route("/admin/commande", options={"expose"=true}, name="order_home")
     * @Route("/admin/commande/error/{message}", options={"expose"=true}, name="order_home_error")
     */
    public function home($message = null,
                         QuoteOrderRepository $orderRepo,
                         OrderStatusRepository $statusRepo,
                         SerializerInterface $serializer,
                         OrderHydrate $orderHydrate)
    {
        return $this->render('order/home.html.twig', [
            'order_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_ORDER'])])), 'json', SerializationContext::create()->setGroups(array('class_property'))),
            'message' => $message,
        ]);
    }

    /**
     * @Route("/admin/commande/{id}/detail/save", options={"expose"=true}, name="order_detail_save")
     * @Route("/admin/commande/{id}/detail/save/error/{message}", options={"expose"=true}, name="order_detail_save_error")
     */
    public function save($message = null,
                         QuoteOrderRepository $orderRepo,
                         OrderStatusRepository $statusRepo,
                         Request $request,
                         OrderHydrate $orderHydrate)
    {
        
       return $this->RedirectToRoute("order_home");
    }

    /**
     * @Route("/admin/commande/devis", options={"expose"=true}, name="order_quote")
     */
    public function quote(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          SerializerInterface $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'quote_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_QUOTE'])])), 'json', SerializationContext::create()->setGroups(array('class_property')))
        ]);
    }

    /**
     * @Route("/admin/commande/precommande", options={"expose"=true}, name="order_preorder")
     */
    public function preorder(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          SerializerInterface $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'preorder_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_PREORDER'])])), 'json', SerializationContext::create()->setGroups(array('class_property')))
        ]);
    }

    /**
     * @Route("/admin/commande/preavoir", options={"expose"=true}, name="order_prerefund")
     */
    public function prerefund(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          SerializerInterface $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'prerefund_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND'])])), 'json', SerializationContext::create()->setGroups(array('class_property')))
        ]);
    }

    /**
     * @Route("/admin/commande/avoir", options={"expose"=true}, name="order_refund")
     */
    public function refund(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          SerializerInterface $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'refund_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUND'])])), 'json', SerializationContext::create()->setGroups(array('class_property')))
        ]);
    }

    /**
     * @Route("/admin/commande/facturation", options={"expose"=true}, name="order_bill")
     */
    public function bill(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          SerializerInterface $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'bill_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_BILL'])])), 'json', SerializationContext::create()->setGroups(array('class_property')))
        ]);
    }

    /**
     * @Route("/admin/commande/facturation/avoir", options={"expose"=true}, name="order_bill_refund")
     */
    public function billRefund(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          SerializerInterface $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'bill_refund_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUNDBILL'])])), 'json', SerializationContext::create()->setGroups(array('class_property')))
        ]);
    }

    /**
     * @Route("/admin/commande/validation/client", options={"expose"=true}, name="order_customer_valid")
     */
    public function customerValid(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          SerializerInterface $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'customer_valid_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_VALID'])])), 'json', SerializationContext::create()->setGroups(array('class_property')))
        ]);
    }

    /**
     * @Route("/admin/commande/cloture", options={"expose"=true}, name="order_closed")
     */
    public function closed(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          SerializerInterface $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'closed_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_CLOSED'])])), 'json', SerializationContext::create()->setGroups(array('class_property')))
        ]);
    }

    /**
     * @Route("/admin/commande/avoir/cloture", options={"expose"=true}, name="order_refund_closed")
     */
    public function refundClosed(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          SerializerInterface $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'refund_closed_data_source' => $serializer->serialize($orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED'])])), 'json', SerializationContext::create()->setGroups(array('class_property')))
        ]);
    }

    /**
     * @Route("/admin/commande/detail/{id}", options={"expose"=true}, name="order_show")
     */
    public function show($id, 
                        QuoteOrderDetailRepository $orderDetailRepo,
                        QuoteOrderRepository $orderRepo,
                        SerializerInterface $serializer,
                        OrderHydrate $orderHydrate)
    {
        $order = $orderRepo->find($id);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize($orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])), 'json', SerializationContext::create()->setGroups(array('class_property'))),
            'order' => $order,
        ]);
    }

    /**
     * @Route("/admin/commande/devis/detail/{id}", options={"expose"=true}, name="order_show_quote")
     */
    public function showQuote($id, 
                        QuoteOrderDetailRepository $orderDetailRepo,
                        QuoteOrderRepository $orderRepo,
                        SerializerInterface $serializer,
                        OrderHydrate $orderHydrate,
                        OrderStatusRepository $statusRepo)
    {
        $order = $orderRepo->find($id);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize($orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])), 'json', SerializationContext::create()->setGroups(array('class_property'))),
            'status_prerefund' => $statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND' ]),
            'status_preorder' => $statusRepo->findOneBy(['Name' => 'STATUS_PREORDER' ]),
            'order' => $order
        ]);
    }

    /**
     * @Route("/admin/commande/precommande/detail/{id}", options={"expose"=true}, name="order_show_preorder")
     */
    public function showPreOrder($id, 
                        QuoteOrderDetailRepository $orderDetailRepo,
                        QuoteOrderRepository $orderRepo,
                        SerializerInterface $serializer,
                        OrderHydrate $orderHydrate,
                        OrderStatusRepository $statusRepo)
    {
        $order = $orderRepo->find($id);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize($orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])), 'json', SerializationContext::create()->setGroups(array('class_property'))),
            'status_order' => $statusRepo->findOneBy(['Name' => 'STATUS_ORDER' ]),
            'status_valid' => $statusRepo->findOneBy(['Name' => 'STATUS_VALID' ]),
            'order' => $order
        ]);
    }
  
    /**
     * @Route("/admin/commande/{id}/validation/{idStatus}", options={"expose"=true}, name="order_validation")
     */
    public function validation(int $idStatus,
                               QuoteOrder $order,
                               QuoteOrderRepository $orderRepo,
                               OrderStatusRepository $statusRepo,
                               ObjectManager $manager)
    {         
        $status = $statusRepo->find($idStatus);
        $order->setStatus($status);

        $manager->persist($order);
        $manager->flush();

        return $this->redirectToRoute('order_home');

    }
    
    /**
     * @Route("/admin/commande/statut/inscription", options={"expose"=true}, name="order_status_registration")
     * @Route("/admin/commande/statut/{id}/edit", options={"expose"=true}, name="order_status_edit")
     * 
     */
    public function statusRegistration(OrderStatus $status = null, Request $request, ObjectManager $manager)
    {
        if(!$status)
            $status = new OrderStatus();
             
        $form = $this->createForm(OrderStatusRegistrationType::class, $status);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $manager->persist($status);
            $manager->flush();

            return $this->redirectToRoute('order_home');
        }        

        return $this->render('order/status_registration.html.twig', [
            'formStatus' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/commande/inscription", options={"expose"=true}, name="order_registration")
     */
    public function registration(ClientRepository $clientRepo,
                                 ItemRepository $itemRepo,
                                 OrderStatusRepository $statusRepo, 
                                 SessionInterface $session,
                                 ObjectManager $manager,
                                 Security $security,
                                 TaxRepository $taxRepo)
    {
        $clientSession = $session->get('client', []);
        $panier = $session->get('panier', []);
        $user = $security->getUser();

        if(count($clientSession) > 0 && count($panier) > 0 && $user){
            
            $client = $clientRepo->find($clientSession['id']);
            $status = $statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']);
            $tax = $taxRepo->findOneBy(['IsCurrent' => true]);
            $order = new QuoteOrder();
            
            $order->setCreatedAt(new \DateTime());
            $order->setStatus($status);
            $order->setClient($client); 
                        
            foreach($panier as $id => $quantity){
                $orderDetail = new QuoteOrderDetail();
                $item = $itemRepo->find($id);
                $orderDetail->setItem($item);
                $orderDetail->setQuantity($quantity);
                
                $orderDetail->setQuoteOrder($order);
                
                if($tax)
                $orderDetail->setTax($tax);
                
            
                $manager->persist($order);
                $manager->persist($orderDetail);                
                $manager->flush();
            }

            $user->addOrder($order);
            $manager->persist($user);
            $manager->flush();

            $session->set('panier', []);
            $session->set('client', []);
            
            return $this->RedirectToRoute('order_quote');
        }
        return $this->RedirectToRoute('cart_home_error', ['message'=> "Veuillez renseigner le client !" ]);
    }

    /**
     * @Route("/admin/commande/{id}/delete", options={"expose"=true}, name="order_delete")
     */
    public function delete(QuoteOrder $order, QuoteOrderDetailRepository $orderDetailRepo, ObjectManager $manager)
    {

        foreach($orderDetailRepo->findBy(['QuoteOrder' => $order]) as $orderDetail){
            $manager->remove($orderDetail);
            $manager->flush();
        }
        $manager->remove($order);
        $manager->flush();
        return $this->RedirectToRoute('order_home');
    }

    /**
     * @Route("/admin/commande/statut/{id}/delete", options={"expose"=true}, name="order_status_delete")
     */
    public function statusDelete(OrderStatus $status, QuoteOrderRepository $orderRepo, ObjectManager $manager)
    {

        if(count($orderRepo->findBy(['Status' => $status])) == 0){
            $manager->remove($status);
            $manager->flush();
            return $this->RedirectToRoute('order_home');
        }
        return $this->RedirectToRoute('order_home', ['message' => 'Le status ne peut pas être supprimé. Il est en cours d\'utilisation pour au moins une commande!']);

    }
}
