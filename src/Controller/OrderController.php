<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Comment;
use App\Entity\Delivery;
use App\Entity\QuoteOrder;
use App\Services\Serializer;
use App\Services\OrderHydrate;
use App\Entity\QuantityDelivery;
use App\Entity\QuoteOrderDetail;
use App\Repository\TaxRepository;
use App\Repository\BillRepository;
use App\Repository\ItemRepository;
use App\Repository\ClientRepository;
use App\Repository\DeliveryRepository;
use JMS\Serializer\SerializerInterface;
use App\Repository\QuoteOrderRepository;
use JMS\Serializer\SerializationContext;
use App\Repository\OrderStatusRepository;
use App\Repository\DeliveryStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\QuantityDeliveryRepository;
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
                         Serializer $serializer,
                         OrderHydrate $orderHydrate)
    {
        return $this->render('order/home.html.twig', [
            'order_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_ORDER'])])), 'format' => 'json', 'group' => 'class_property']),
            'message' => $message,
        ]);
    }

    /**
     * @Route("/admin/commande/{id}/detail/sauvegarde", options={"expose"=true}, name="order_detail_save")
     * @Route("/admin/commande/{id}/detail/sauvegarde/error/{message}", options={"expose"=true}, name="order_detail_save_error")
     */
    public function save($message = null,
                         QuoteOrderRepository $orderRepo,
                         QuoteOrderDetailRepository $orderDetailRepo,
                         Request $request,
                         OrderHydrate $orderHydrate,
                         ObjectManager $manager)
    {
        $form = $request->request->get('order_detail_form');
        $order = $orderHydrate->hydrateQuoteOrderRelationFromForm($orderRepo->find($request->request->get('order')), $form);
        $manager->persist($order);

        foreach($form['tab'] as $key => $val){
            $orderDetail = $orderHydrate->hydrateQuoteOrderDetailRelationFromForm($orderDetailRepo->find($key), $val);

            $manager->persist($orderDetail->getItem());
            $manager->persist($orderDetail);
        }
        $manager->flush();
       return $this->RedirectToRoute("order_show", [
           'id' => $order->getId()
       ]);
    }

    /**
     * @Route("/admin/commande/{id}/livraison/sauvegarde", options={"expose"=true}, name="order_delivery_save")
     */
    public function deliverySave(QuoteOrder $order,
                         QuoteOrderDetailRepository $orderDetailRepo,
                         DeliveryStatusRepository $delStatusRepo,
                         QuantityDeliveryRepository $qtDelRepo,
                         Request $request,
                         ObjectManager $manager)
    {
        //dump($request->request); die();
        $form = $request->request->get('order_detail_form');
        $delivery = new Delivery();
       
        $delStatus = $delStatusRepo->findOneBy(['Name' => 'STATUS_NOT_BILLED']);
        
        $delivery->setPackage($form['delivery']['package']);
        $delivery->setCreatedAt(new \DateTime());        
        $delivery->setStatus($delStatus);        
        
        $manager->persist($delivery);  

        foreach($orderDetailRepo->findByQuantityRecieved() as $orderDetail){

            $qtDelivery = new QuantityDelivery();
            $qtDelivery->setDelivery($delivery); 

            $qtDel = $orderDetail->getQuantityDelivery();
            $qtRecieved = $orderDetail->getQuantityRecieved();

            if(!$qtDel)
                $qtDel = 0;

            if($qtRecieved)
                $qtDel += $orderDetail->getQuantityRecieved();

            $orderDetail->setQuantityDelivery($qtDel);
            $orderDetail->setQuantityRecieved(0);
            $qtDelivery->setQuantity($qtRecieved);

            $orderDetail->addQuantityDelivery($qtDelivery);

            $manager->persist($qtDelivery);
            $manager->persist($orderDetail);
        }
        $manager->flush();

       return $this->RedirectToRoute("order_show", [
           'id' => $order->getId()
       ]);
    }

    /**
     * @Route("/admin/commande/{id}/facturation/sauvegarde", options={"expose"=true}, name="order_bill_save")
     */
    public function billSave(
        QuoteOrder $order,
        Request $request,
        DeliveryRepository $deliveryRepo,
        QuantityDeliveryRepository $qtDelRepo,
        QuoteOrderDetailRepository $orderDetailRepo,
        DeliveryStatusRepository $delStatusRepo,
        ObjectManager $manager
    ) {
        //dump($request->request); die();
        $form = $request->request->get('order_detail_form');
        $bill = new Bill();

        $bill->setClient($order->getClient());
        $bill->setCreatedAt(new \DateTime());
        $bill->setContact($order->getContact());
        
        $amount = 0;
        foreach ($form['bill']['delivery'] as $val) {

            $delivery = $deliveryRepo->find($val);
            $d = $orderDetailRepo->findByDelivery($delivery);
            //dump($d); die();
            foreach($d as $orderDetail){
                $qtDelivery = $qtDelRepo->findOneByOrderDetail($orderDetail);
                if($orderDetail){
                    $amount += $qtDelivery->getQuantity() * $orderDetail->getItem()->getSellPrice();
                }
                
                $qtDelivery->setBill($bill);
            }
            $delivery->setStatus($delStatusRepo->findOneBy(['Name' => 'STATUS_BILLED']));
            
            $manager->persist($delivery);
            $manager->persist($qtDelivery);
        }
        $bill->setPay($amount);
        $manager->persist($bill);

        $manager->flush();

        return $this->RedirectToRoute("order_show", [
            'id' => $order->getId()
        ]);
    }

    /**
     * @Route("/admin/commande/devis", options={"expose"=true}, name="order_quote")
     */
    public function quote(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          Serializer $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'quote_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_QUOTE'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/precommande", options={"expose"=true}, name="order_preorder")
     */
    public function preorder(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          Serializer $serializer,
                           OrderHydrate $orderHydrate)
    {         
        
       return $this->render('order/home.html.twig', [
            'preorder_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_PREORDER'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    } 

    /**
     * @Route("/admin/commande/preavoir", options={"expose"=true}, name="order_prerefund")
     */
    public function prerefund(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          Serializer $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'prerefund_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/avoir", options={"expose"=true}, name="order_refund")
     */
    public function refund(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          Serializer $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'refund_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUND'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/facturation", options={"expose"=true}, name="order_bill")
     */
    public function bill(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          Serializer $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'bill_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_BILL'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/facturation/avoir", options={"expose"=true}, name="order_bill_refund")
     */
    public function billRefund(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          Serializer $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'bill_refund_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUNDBILL'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/validation/client", options={"expose"=true}, name="order_customer_valid")
     */
    public function customerValid(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          Serializer $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'customer_valid_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_VALID'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/cloture", options={"expose"=true}, name="order_closed")
     */
    public function closed(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          Serializer $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'closed_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_CLOSED'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/avoir/cloture", options={"expose"=true}, name="order_refund_closed")
     */
    public function refundClosed(QuoteOrderRepository $orderRepo,
                          OrderStatusRepository $statusRepo,
                          Serializer $serializer,
                           OrderHydrate $orderHydrate)
    {         
       return $this->render('order/home.html.twig', [
            'refund_closed_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/detail/{id}", options={"expose"=true}, name="order_show")
     */
    public function show(QuoteOrder $order, 
                        QuoteOrderDetailRepository $orderDetailRepo,
                        DeliveryRepository $deliveryRepo,
                        Serializer $serializer,
                        OrderHydrate $orderHydrate,
                        BillRepository $billRepo)
    {
        $orderDetails = $orderDetailRepo->findBy(['QuoteOrder' => $order]);
        
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])),'format' => 'json', 'group' => 'class_property']),
            'order_detail_delivery_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findByQuantityRecieved()), 'format' => 'json', 'group' => 'class_property']),
            'order_detail_bill_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findByBillStatus()), 'format' => 'json', 'group' => 'class_property']),
            'bill_data_source' => $serializer->serialize(['object_array' => $billRepo->findByOrder(['orderDetails' => $orderDetails, 'status' => 'STATUS_BILLED']), 'format' => 'json', 'group' => 'class_property']),
            'order' => $order,
            'deliveries' => $deliveryRepo->findByBillStatus(['order' => $order, 'status' => 'STATUS_NOT_BILLED']),
            //'bills' => $billRepo->findByOrder(['orderDetails' => $orderDetails, 'status' => 'STATUS_BILLED']),
        ]);
    }

    /**
     * @Route("/admin/commande/devis/detail/{id}", options={"expose"=true}, name="order_show_quote")
     */
    public function showQuote($id, 
                        QuoteOrderDetailRepository $orderDetailRepo,
                        QuoteOrderRepository $orderRepo,
                        Serializer $serializer,
                        OrderHydrate $orderHydrate,
                        OrderStatusRepository $statusRepo)
    {
        $order = $orderRepo->find($id);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])), 'format' => 'json', 'group' => 'class_property']),
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
                        Serializer $serializer,
                        OrderHydrate $orderHydrate,
                        OrderStatusRepository $statusRepo)
    {
        $order = $orderRepo->find($id);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])), 'format' => 'json', 'group' => 'class_property']),
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
            $order->setIsRefVisible(false);
            $order->setStatus($status);
            $order->setClient($client); 
                        
            foreach($panier as $id => $quantity){
                $orderDetail = new QuoteOrderDetail();
                $item = $itemRepo->find($id);
                $orderDetail->setItem($item);
                $orderDetail->setQuantity($quantity);
                $orderDetail->setItemSellPrice(0);
                
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
            $qtDeliveries = $orderDetail->getQuantityDeliveries();
            //dump($qtDeliveries); die();
            if($qtDeliveries){
                foreach ($qtDeliveries as $qtDelivery) {
                    $bill = $qtDelivery->getBill();
                    if ($bill)
                        $manager->remove($bill);

                    $delivery = $qtDelivery->getDelivery();
                    if ($delivery)
                        $manager->remove($delivery);

                    $manager->remove($qtDelivery);
                }
            }
            $manager->remove($orderDetail);
        }
        $manager->remove($order);
        $manager->flush();
        return $this->RedirectToRoute('order_home');
    }
}
