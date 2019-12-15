<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Delivery;
use App\Entity\QuoteOrder;
use App\Services\Serializer;
use App\Services\OrderHydrate;
use App\Services\PdfWebService;
use App\Entity\QuantityDelivery;
use App\Entity\QuoteOrderDetail;
use App\Repository\TaxRepository;
use App\Services\SecurityManager;
use App\Repository\BillRepository;
use App\Repository\ItemRepository;
use App\Repository\ActionRepository;
use App\Repository\ClientRepository;
use App\Repository\DeliveryRepository;
use App\Repository\QuoteOrderRepository;
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
    protected $securityUtility;
    protected $actionRepo;


    public function __construct(SecurityManager $securityUtility, ActionRepository $actionRepo)
    {
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Views ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande", options={"expose"=true}, name="order_home")
     * @Route("/admin/commande/error/{message}", options={"expose"=true}, name="order_home_error")
     */
    public function home($message = null,
                         QuoteOrderRepository $orderRepo,
                         OrderStatusRepository $statusRepo,
                         Serializer $serializer,
                         OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('order/home.html.twig', [
            'order_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Status' => $statusRepo->findOneBy(['Name' => 'STATUS_ORDER'])])), 'format' => 'json', 'group' => 'class_property']),
            'message' => $message,
        ]);
    }

    /**
     * @Route("/admin/commande/devis", options={"expose"=true}, name="order_quote")
     */
    public function quote(
        QuoteOrderRepository $orderRepo,
        OrderStatusRepository $statusRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_QUOTE']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'quote_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Agent' => $this->getUser() ,'Status' => $statusRepo->findOneBy(['Name' => 'STATUS_QUOTE'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/precommande", options={"expose"=true}, name="order_preorder")
     */
    public function preorder(
        QuoteOrderRepository $orderRepo,
        OrderStatusRepository $statusRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PREORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'preorder_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $statusRepo->findOneBy(['Name' => 'STATUS_PREORDER'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/preavoir", options={"expose"=true}, name="order_prerefund")
     */
    public function prerefund(
        QuoteOrderRepository $orderRepo,
        OrderStatusRepository $statusRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PREREFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'prerefund_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/avoir", options={"expose"=true}, name="order_refund")
     */
    public function refund(
        QuoteOrderRepository $orderRepo,
        OrderStatusRepository $statusRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'refund_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUND'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/facturation", options={"expose"=true}, name="order_bill")
     */
    public function bill(
        QuoteOrderRepository $orderRepo,
        OrderStatusRepository $statusRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'bill_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $statusRepo->findOneBy(['Name' => 'STATUS_BILL'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/facturation/avoir", options={"expose"=true}, name="order_bill_refund")
     */
    public function billRefund(
        QuoteOrderRepository $orderRepo,
        OrderStatusRepository $statusRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'bill_refund_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUNDBILL'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/validation/client", options={"expose"=true}, name="order_customer_valid")
     */
    public function customerValid(
        QuoteOrderRepository $orderRepo,
        OrderStatusRepository $statusRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_VALID']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'customer_valid_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $statusRepo->findOneBy(['Name' => 'STATUS_VALID'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/cloture", options={"expose"=true}, name="order_closed")
     */
    public function closed(
        QuoteOrderRepository $orderRepo,
        OrderStatusRepository $statusRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER_CLOSED']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('order/home.html.twig', [
            'closed_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $statusRepo->findOneBy(['Name' => 'STATUS_CLOSED'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/avoir/cloture", options={"expose"=true}, name="order_refund_closed")
     */
    public function refundClosed(
        QuoteOrderRepository $orderRepo,
        OrderStatusRepository $statusRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND_CLOSED']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('order/home.html.twig', [
            'refund_closed_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateQuoteOrder($orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/detail/{id}", options={"expose"=true}, name="order_show")
     */
    public function show(
        QuoteOrder $order,
        QuoteOrderDetailRepository $orderDetailRepo,
        DeliveryRepository $deliveryRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate,
        BillRepository $billRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $orderDetails = $orderDetailRepo->findBy(['QuoteOrder' => $order]);

        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])), 'format' => 'json', 'group' => 'class_property']),
            'order_detail_delivery_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findByQuantityRecieved()), 'format' => 'json', 'group' => 'class_property']),
            'order_detail_bill_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findByBillStatus()), 'format' => 'json', 'group' => 'class_property']),
            'bill_data_source' => $serializer->serialize(['object_array' => $billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), 'format' => 'json', 'group' => 'class_property']),
            'delivery_data_source' => $serializer->serialize(['object_array' => $deliveryRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), 'format' => 'json', 'group' => 'class_property']),
            'status' => $order->getStatus(),
            'order' => $order,
            'deliveries' => $deliveryRepo->findByBillStatus(['order' => $order, 'status' => 'STATUS_NOT_BILLED']),
        ]);
    }

    /**
     * @Route("/admin/commande/devis/detail/{id}", options={"expose"=true}, name="order_show_quote")
     */
    public function showQuote(
        $id,
        QuoteOrderDetailRepository $orderDetailRepo,
        QuoteOrderRepository $orderRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate,
        OrderStatusRepository $statusRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_QUOTE']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $orderRepo->find($id);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])), 'format' => 'json', 'group' => 'class_property']),
            'status_prerefund' => $statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND']),
            'status_preorder' => $statusRepo->findOneBy(['Name' => 'STATUS_PREORDER']),
            'order' => $order,
            'status' => $order->getStatus(),
        ]);
    }

    /**
     * @Route("/admin/commande/precommande/detail/{id}", options={"expose"=true}, name="order_show_preorder")
     */
    public function showPreOrder(
        $id,
        QuoteOrderDetailRepository $orderDetailRepo,
        QuoteOrderRepository $orderRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate,
        OrderStatusRepository $statusRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PREORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $orderRepo->find($id);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])), 'format' => 'json', 'group' => 'class_property']),
            'status_order' => $statusRepo->findOneBy(['Name' => 'STATUS_ORDER']),
            'status_valid' => $statusRepo->findOneBy(['Name' => 'STATUS_VALID']),
            'order' => $order,
            'status' => $order->getStatus(),
        ]);
    }

    /**
     * @Route("/admin/commande/preavoir/detail/{id}", options={"expose"=true}, name="order_show_prerefund")
     */
    public function showPreRefund(
        $id,
        QuoteOrderDetailRepository $orderDetailRepo,
        QuoteOrderRepository $orderRepo,
        Serializer $serializer,
        OrderHydrate $orderHydrate,
        OrderStatusRepository $statusRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $orderRepo->find($id);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $serializer->serialize(['object_array' => $orderHydrate->hydrateOrderDetail($orderDetailRepo->findBy(['QuoteOrder' => $order])), 'format' => 'json', 'group' => 'class_property']),
            'status_refund' => $statusRepo->findOneBy(['Name' => 'STATUS_REFUND']),
            'order' => $order,
            'status' => $order->getStatus(),
        ]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Validations ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/{id}/validation/{idStatus}", options={"expose"=true}, name="order_validation")
     */
    public function validation(
        int $idStatus,
        QuoteOrder $order,
        OrderStatusRepository $statusRepo,
        ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        //$this->denyAccessUnlessGranted('ROLE_VALIDATOR');

        $status = $statusRepo->find($idStatus);
        $order->setStatus($status);

        $manager->persist($order);
        $manager->flush();

        switch($status->getName()) {
            case 'STATUS_REFUND':
                return $this->redirectToRoute('order_refund');
            case 'STATUS_PREREFUND':
                return $this->redirectToRoute('order_prerefund');
            case 'STATUS_VALID':
                return $this->redirectToRoute('order_customer_valid');
            case 'STATUS_PREORDER':
                return $this->redirectToRoute('order_preorder');
        }

        return $this->redirectToRoute('order_home');
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Registrations ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/inscription", options={"expose"=true}, name="order_registration")
     */
    public function registration(
        ClientRepository $clientRepo,
        ItemRepository $itemRepo,
        OrderStatusRepository $statusRepo,
        SessionInterface $session,
        ObjectManager $manager,
        Security $security,
        TaxRepository $taxRepo) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $clientSession = $session->get('client', []);
        $panier = $session->get('panier', []);
        $user = $security->getUser();

        if (count($clientSession) > 0 && count($panier) > 0 && $user) {

            $client = $clientRepo->find($clientSession['id']);
            $status = $statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']);
            $tax = $taxRepo->findOneBy(['IsCurrent' => true]);
            $order = new QuoteOrder();

            $order->setCreatedAt(new \DateTime());
            $order->setIsRefVisible(false);
            $order->setStatus($status);
            $order->setClient($client);
            $order->setAgent($user);

            foreach ($panier as $id => $quantity) {
                $orderDetail = new QuoteOrderDetail();
                $item = $itemRepo->find($id);
                $orderDetail->setItem($item);
                $orderDetail->setQuantity($quantity);
                $orderDetail->setItemSellPrice(0);

                $orderDetail->setQuoteOrder($order);

                if ($tax)
                    $orderDetail->setTax($tax);

                $manager->persist($order);
                $manager->persist($orderDetail);
                
            }

            $manager->flush();

            $session->set('panier', []);
            $session->set('client', []);

            return $this->RedirectToRoute('order_quote');
        }
        return $this->RedirectToRoute('cart_home_error', ['message' => "Veuillez renseigner le client !"]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Savings / Cancels / PDF ]--------------------------------------------------------*/


    /**
     * @Route("/admin/commande/{id}/detail/sauvegarde", options={"expose"=true}, name="order_detail_save")
     * @Route("/admin/commande/{id}/detail/sauvegarde/error/{message}", options={"expose"=true}, name="order_detail_save_error")
     */
    public function save($message = null,
                         QuoteOrderRepository $orderRepo,
                         QuoteOrderDetailRepository $orderDetailRepo,
                         Request $request,
                         OrderHydrate $orderHydrate,
                         ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

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
                         ItemRepository $itemRepo,
                         Request $request,
                         ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

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
                $qtDel += $qtRecieved;

            $orderDetail->setQuantityDelivery($qtDel); 
            $orderDetail->setQuantityRecieved(0);
            $qtDelivery->setQuantity($qtRecieved);

            $orderDetail->addQuantityDelivery($qtDelivery);

            $item = $itemRepo->findOneByOrderDetail($orderDetail);
            $stock = $item->getStock();
            if (!empty($stock))
                $item->setStock($stock - $qtRecieved);            

            $manager->persist($item);
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
        ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        //dump($request->request); die();
        $form = $request->request->get('order_detail_form');
        $bill = new Bill();

        $status = $delStatusRepo->findOneBy(['Name' => 'STATUS_BILLED']);
        $bill->setClient($order->getClient());
        $bill->setCreatedAt(new \DateTime());
        $bill->setContact($order->getContact());
        
        $amount = 0;
        foreach ($form['bill']['delivery'] as $val) {

            $delivery = $deliveryRepo->find($val);
            
            foreach($orderDetailRepo->findByDelivery($delivery) as $orderDetail){
                $qtDelivery = $qtDelRepo->findOneByOrderDetailNotBilled($orderDetail);
                if($orderDetail){
                    $amount += $qtDelivery->getQuantity() * $orderDetail->getItem()->getSellPrice();
                }
                
                $qtDelivery->setBill($bill);
            }
            $delivery->setStatus($status);
            
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
     * @Route("/admin/commande/facturation/{id}/annulation", options={"expose"=true}, name="order_bill_cancel")
     */
    public function billCancel(
        Bill $bill,
        DeliveryStatusRepository $delStatusRepo,
        DeliveryRepository $deliveryRepo,
        QuantityDeliveryRepository $qtDeliveryRepo,
        QuoteOrderDetailRepository $orderDetailRepo,
        QuoteOrderRepository $orderRepo,
        ItemRepository $itemRepo,
        ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        //dump($request->request); die();
        $status = $delStatusRepo->findOneBy(['Name' => 'STATUS_CANCELED']);

        foreach ($deliveryRepo->findByBill($bill) as $delivery) {
            
            $delivery->setStatus($status);
            $manager->persist($delivery);
        }

        foreach ($orderDetailRepo->findByBill($bill) as $orderDetail) {

            $qtDelivery = $qtDeliveryRepo->findOneByOrderDetailAndBill(['order_detail' => $orderDetail, 'bill' => $bill]);
           
            $quantity = $qtDelivery->getQuantity();
            $qtDel = $orderDetail->getQuantityDelivery();
            if(!empty($qtDel))
                $orderDetail->setQuantityDelivery($qtDel - $quantity);
            
            $item = $itemRepo->findOneByOrderDetail($orderDetail);
            $stock = $item->getStock();
            if (!empty($stock))
                $item->setStock($stock + $quantity);
            
            $manager->persist($item);
            $manager->persist($orderDetail);
        }

        $manager->flush();

        return $this->RedirectToRoute("order_show", [
            'id' => $orderRepo->findOneByBill($bill)->getId()
        ]);
    }

    /**
     * @Route("/admin/commande/pdf/facture/{id}", options={"expose"=true}, name="order_pdf_bill")
     */
    public function pdfBill(Bill $bill, 
                            PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->file($webservice->downloadBill($bill, $this->getParameter('file.download_dir'), $this->getParameter('file.type.download_order'), $this->getParameter('file.type.download_refund')));   
    }

    /**
     * @Route("/admin/commande/pdf/devis/{id}", options={"expose"=true}, name="order_pdf_quote")
     */
    public function pdfQuote(QuoteOrder $order, 
                            PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->file($webservice->downloadQuote($order, $this->getParameter('file.download_dir'), $this->getParameter('file.type.download_quote')));   
    }

    /**
     * @Route("/admin/commande/pdf/bl/{id}", options={"expose"=true}, name="order_pdf_delivery")
     */
    public function pdfDelivery(Delivery $delivery, 
                            PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->file($webservice->downloadDelivery($delivery, $this->getParameter('file.download_dir')));   
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Deletes ]--------------------------------------------------------*/


    /**
     * @Route("/admin/commande/{id}/delete", options={"expose"=true}, name="order_delete")
     */
    public function delete(QuoteOrder $order, 
                           QuoteOrderDetailRepository $orderDetailRepo, 
                           ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

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
