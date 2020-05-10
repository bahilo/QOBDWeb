<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Delivery;
use App\Events\MyEvents;
use App\Services\Mailer;
use App\Entity\QuoteOrder;
use App\Entity\OrderStatus;
use App\Services\Serializer;
use App\Services\ErrorHandler;
use App\Services\OrderManager;
use App\Services\PdfWebService;
use App\Entity\QuantityDelivery;
use App\Entity\QuoteOrderDetail;
use App\Services\SettingManager;
use App\Repository\TaxRepository;
use App\Services\SecurityManager;
use App\Repository\BillRepository;
use App\Repository\ItemRepository;
use App\Repository\AgentRepository;
use App\Repository\ActionRepository;
use App\Repository\ClientRepository;
use App\Repository\CurrencyRepository;
use App\Repository\DeliveryRepository;
use App\Repository\QuoteOrderRepository;
use App\Repository\OrderStatusRepository;
use App\Repository\DeliveryStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\QuantityDeliveryRepository;
use App\Repository\QuoteOrderDetailRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderController extends Controller
{
    protected $orderRepo;
    protected $orderDetailRepo;
    protected $statusRepo;
    protected $serializer;
    protected $securityUtility;
    protected $actionRepo;
    protected $orderManager;
    protected $deliveryRepo;
    protected $billRepo;
    protected $tvaRepo;
    protected $quantityDelRepo;
    protected $eventDispatcher;
    protected $ErrorHandler;


    public function __construct(Serializer $serializer, 
                                SecurityManager $securityUtility, 
                                ActionRepository $actionRepo,
                                QuoteOrderRepository $orderRepo,
                                OrderStatusRepository $statusRepo,
                                OrderManager $orderManager,
                                QuantityDeliveryRepository $quantityDelRepo,
                                QuoteOrderDetailRepository $orderDetailRepo,
                                DeliveryRepository $deliveryRepo,
                                BillRepository $billRepo,
                                EventDispatcherInterface $eventDispatcher,
                                TaxRepository $tvaRepo,
                                ErrorHandler $ErrorHandler)
    {
        $this->orderDetailRepo = $orderDetailRepo;
        $this->orderRepo = $orderRepo;
        $this->statusRepo = $statusRepo;
        $this->orderManager = $orderManager;
        $this->serializer = $serializer;
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
        $this->deliveryRepo = $deliveryRepo;
        $this->eventDispatcher = $eventDispatcher;
        $this->billRepo = $billRepo;
        $this->tvaRepo = $tvaRepo;
        $this->quantityDelRepo = $quantityDelRepo;
        $this->ErrorHandler = $ErrorHandler;
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Views ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/accueil", options={"expose"=true}, name="order")
     */
    public function index(AgentRepository $agentRepo, ClientRepository $clientRepo)
    {
        return $this->render('order/index.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER']),
            'agents' => $agentRepo->findAll(),
            'clients' => $clientRepo->findAll(),
          ]);
    }

    /**
     * @Route("/admin/commande", options={"expose"=true}, name="order_home")
     */
    public function home() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('order/home/home_order.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER']),
        ]);
    }

    /**
     * @Route("/admin/commande/devis", options={"expose"=true}, name="order_quote")
     */
    public function quote() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_QUOTE']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home/home_quote.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
        ]);
    }

    /**
     * @Route("/admin/commande/precommande", options={"expose"=true}, name="order_preorder")
     */
    public function preorder() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_PREORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home/home_preorder.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREORDER']),
        ]);
    }

    /**
     * @Route("/admin/commande/preavoir", options={"expose"=true}, name="order_prerefund")
     */
    public function prerefund() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_PREREFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home/home_prerefund.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND']),
        ]);
    }

    /**
     * @Route("/admin/commande/avoir", options={"expose"=true}, name="order_refund")
     */
    public function refund() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home/home_refund.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUND']),
        ]);
    }

    /**
     * @Route("/admin/commande/facturation", options={"expose"=true}, name="order_bill")
     */
    public function bill() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home/home_order_sent.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_BILL']),
        ]);
    }

    /**
     * @Route("/admin/commande/facturation/avoir", options={"expose"=true}, name="order_bill_refund")
     */
    public function billRefund() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home/home_refund_sent.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDBILL']),
        ]);
        
    }

    /**
     * @Route("/admin/commande/validation/client", options={"expose"=true}, name="order_customer_valid")
     */
    public function customerValid() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_VALID']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home/home_valid.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_VALID']),
        ]);
    }

    /**
     * @Route("/admin/commande/cloture", options={"expose"=true}, name="order_closed")
     */
    public function closed() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER_CLOSED']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('order/home/home_order_closed.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_CLOSED']),
        ]);
    }

    /**
     * @Route("/admin/commande/avoir/cloture", options={"expose"=true}, name="order_refund_closed")
     */
    public function refundClosed() {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND_CLOSED']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('order/home/home_refund_closed.html.twig', [
            'status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED']),
         ]);
    }

    /**
     * @Route("/admin/commande/detail/{id}", options={"expose"=true}, requirements={"id"="\d+"}, name="order_show")
     */
    public function show(
        QuoteOrder $order,
        CurrencyRepository $currRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $bills = $this->orderManager->getHydrater()->hydrateBill($this->billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), $order);
        $orderDetail = $this->orderManager->getHydrater()->hydrateOrderDetail($this->orderDetailRepo->findBy(['QuoteOrder' => $order]), $order);
        $orderDetrailQtReceived = $this->orderManager->getHydrater()->hydrateOrderDetail($this->orderDetailRepo->findByQuantityRecieved($order), $order);
        $roderDeliveries = $this->orderManager->getHydrater()->hydrateQuantityDelivery($this->quantityDelRepo->findByBillStatus($order), $order);
        $CreateDeliveries = $this->deliveryRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']);
        $infos = $this->orderManager->getCommandeInfo($orderDetail, $order);
        
        return $this->render('order/show/show_order.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $orderDetail, 'format' => 'json', 'group' => 'class_property']),
            'order_detail_delivery_data_source' => $this->serializer->serialize(['object_array' => $orderDetrailQtReceived, 'format' => 'json', 'group' => 'class_property']),
            'order_detail_bill_data_source' => $this->serializer->serialize(['object_array' => $roderDeliveries, 'format' => 'json', 'group' => 'class_property']),
            'bill_data_source' => $this->serializer->serialize(['object_array' => $bills, 'format' => 'json', 'group' => 'class_property']),
            'delivery_data_source' => $this->serializer->serialize(['object_array' => $CreateDeliveries, 'format' => 'json', 'group' => 'class_property']),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $this->tvaRepo->findAll(),
            'status_close' => $this->statusRepo->findOneBy(['Name' => 'STATUS_CLOSED']),
            'bills' => $bills,
            'order_status_valid' => $this->statusRepo->findOneBy(['Name' => 'STATUS_VALID']),
            'order_status_refund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUND']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'deliveries' => $this->deliveryRepo->findByBillStatus(['order' => $order, 'status' => 'STATUS_NOT_BILLED']),
            'info' => $infos,
            'can_open_row' => true,
            'email_content' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'facture.txt'),
        ]);
    }

    /**
     * @Route("/admin/commande/devis/detail/{id}", options={"expose"=true}, name="order_show_quote", requirements={"id"="\d+"})
     */
    public function showQuote(int $id,  
                              CurrencyRepository $currRepo,
                              TaxRepository $tvaRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_QUOTE']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        $order = $this->orderRepo->find($id);
        $orderDetails = $this->orderDetailRepo->findBy(['QuoteOrder' => $order]);

        return $this->render('order/show/show_quote.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $this->orderManager->getHydrater()->hydrateOrderDetail($orderDetails, $order), 'format' => 'json', 'group' => 'class_property']),
            'status_prerefund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND']),
            'status_preorder' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREORDER']),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $tvaRepo->findAll(),
            'info' => $this->orderManager->getCommandeInfo($orderDetails, $order),
            'email_content' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'devis.txt'),
        ]);
    }

    /**
     * @Route("/admin/commande/precommande/detail/{id}", options={"expose"=true}, name="order_show_preorder", requirements={"id"="\d+"})
     */
    public function showPreOrder(int $id, 
                                CurrencyRepository $currRepo,
                                TaxRepository $tvaRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_PREORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $this->orderRepo->find($id);
        $orderDetails = $this->orderDetailRepo->findBy(['QuoteOrder' => $order]);
        return $this->render('order/show/show_preorder.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $this->orderManager->getHydrater()->hydrateOrderDetail($orderDetails, $order), 'format' => 'json', 'group' => 'class_property']),
            'status_order' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $tvaRepo->findAll(),
            'info' => $this->orderManager->getCommandeInfo($orderDetails, $order),
        ]);
    }

    /**
     * @Route("/admin/commande/revalidation/detail/{id}", options={"expose"=true}, name="order_show_valid", requirements={"id"="\d+"})
     */
    public function showValid(int $id, 
                                CurrencyRepository $currRepo,
                                TaxRepository $tvaRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_PREORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $this->orderRepo->find($id);
        $orderDetails = $this->orderDetailRepo->findBy(['QuoteOrder' => $order]);
        return $this->render('order/show/show_valid.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $this->orderManager->getHydrater()->hydrateOrderDetail($orderDetails, $order), 'format' => 'json', 'group' => 'class_property']),
            'status_order' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $tvaRepo->findAll(),
            'info' => $this->orderManager->getCommandeInfo($orderDetails, $order),
        ]);
    }

    /**
     * @Route("/admin/commande/avoir/detail/{id}", options={"expose"=true}, requirements={"id"="\d+"}, name="order_show_refund")
     */
    public function showRefund(
        QuoteOrder $order,
        CurrencyRepository $currRepo
    ) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $bills = $this->orderManager->getHydrater()->hydrateBill($this->billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), $order);
        $orderDetail = $this->orderManager->getHydrater()->hydrateOrderDetail($this->orderDetailRepo->findBy(['QuoteOrder' => $order]), $order);
        $orderDetrailQtReceived = $this->orderManager->getHydrater()->hydrateOrderDetail($this->orderDetailRepo->findByQuantityRecieved($order), $order);
        $roderDeliveries = $this->orderManager->getHydrater()->hydrateQuantityDelivery($this->quantityDelRepo->findByBillStatus($order), $order);
        $CreateDeliveries = $this->deliveryRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']);
        $infos = $this->orderManager->getCommandeInfo($orderDetail, $order);

        return $this->render('order/show/show_refund.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $orderDetail, 'format' => 'json', 'group' => 'class_property']),
            'order_detail_delivery_data_source' => $this->serializer->serialize(['object_array' => $orderDetrailQtReceived, 'format' => 'json', 'group' => 'class_property']),
            'order_detail_bill_data_source' => $this->serializer->serialize(['object_array' => $roderDeliveries, 'format' => 'json', 'group' => 'class_property']),
            'bill_data_source' => $this->serializer->serialize(['object_array' => $bills, 'format' => 'json', 'group' => 'class_property']),
            'delivery_data_source' => $this->serializer->serialize(['object_array' => $CreateDeliveries, 'format' => 'json', 'group' => 'class_property']),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $this->tvaRepo->findAll(),
            'status_prerefund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND']),
            'refund_status_close' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'bills' => $bills,
            'deliveries' => $this->deliveryRepo->findByBillStatus(['order' => $order, 'status' => 'STATUS_NOT_BILLED']),
            'info' => $infos,
            'can_open_row' => true,
            'email_content' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'facture.txt'),
        ]);
    }

    /**
     * @Route("/admin/commande/preavoir/detail/{id}", options={"expose"=true}, name="order_show_prerefund", requirements={"id"="\d+"})
    */
    public function showPreRefund(int $id, 
                                  CurrencyRepository $currRepo,
                                  TaxRepository $tvaRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $this->orderRepo->find($id);
        $orderDetails = $this->orderDetailRepo->findBy(['QuoteOrder' => $order]);
        return $this->render('order/show/show_prerefund.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $this->orderManager->getHydrater()->hydrateOrderDetail($orderDetails, $order), 'format' => 'json', 'group' => 'class_property']),
            'status_refund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUND']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $tvaRepo->findAll(),
            'info' => $this->orderManager->getCommandeInfo($orderDetails, $order),
        ]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Validations ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/validation/{id}/{idStatus}", options={"expose"=true}, name="order_validation", requirements={"id"="\d+"})
     */
    public function validation(
        int $idStatus,
        QuoteOrder $order,
        SettingManager $settingManager,
        ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $status = $this->statusRepo->find($idStatus);
        $order->setStatus($status);

        $manager->persist($order);
        $manager->flush();

        $result = $this->checkContact($order);
        if ($result !== true)
            return $result;

        // envoi d'un mail de prise en compte de la commande
        if(!empty($order->getStatus()) && $order->getStatus()->getName() == 'STATUS_ORDER'){
            $societe = $settingManager->get('SOCIETE', 'SOCIETE_NOM');            
            
            $event = new GenericEvent([
                'subject' => "Validation de votre commande",
                'to' => $order->getContact()->getEmail(),
                'view' => $this->renderView('email/_partials/validation.html', [
                    'contact_name' => $order->getContact()->getLastName(),
                    'company' => $societe->getValue(),
                    'order' => $order
                ]),
            ]);
            $this->eventDispatcher->dispatch(MyEvents::ORDER_EMAIL_VALIDATION, $event);

        }

        return $this->getRouteFromStatus($status, $order);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Registrations ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/inscription", options={"expose"=true}, name="order_registration")
     */
    public function registration(
        ClientRepository $clientRepo,
        ItemRepository $itemRepo,
        CurrencyRepository $currencyRepo,
        SessionInterface $session,
        ObjectManager $manager,
        Security $security,
        TaxRepository $taxRepo) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $clientSession = $session->get('client', []);
        $panier = $session->get('panier', []);
        $user = $security->getUser();

        if (count($clientSession) > 0 && count($panier) > 0 && $user) {

            $client = $clientRepo->find($clientSession['id']);
            $status = $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']);
            $tax = $taxRepo->findOneBy(['IsCurrent' => true]);
            $currency = $currencyRepo->findOneBy(['IsDefault' => true]);
            $order = new QuoteOrder();

            if(!empty($tax))
                $order->setTax($tax);

            if(!empty($currency))
                $order->setCurrency($currency);

            $order->setCreatedAt(new \DateTime());
            $order->setIsRefVisible(false);
            $order->setStatus($status);
            $order->setClient($client);
            $order->setAgent($user);

            foreach ($panier as $id => $quantity) {
                $orderDetail = new QuoteOrderDetail();
                $item = $itemRepo->find($id);
                $item->setIsErasable(false);
                $orderDetail->setItem($item);
                $orderDetail->setQuantity($quantity);
                $orderDetail->setItemSellPrice(0);

                $orderDetail->setQuoteOrder($order);

                $manager->persist($order);
                $manager->persist($item);
                $manager->persist($orderDetail);
                
            }

            $manager->flush();

            $session->set('panier', []);
            $session->set('client', []);

            $this->orderManager->loggCommandeRegisterInfo($order);

            return $this->RedirectToRoute('order_show_quote', ['id' => $order->getId()]);
        }
        $this->ErrorHandler->error("Veuillez renseigner un client !");
        return $this->RedirectToRoute('cart_home');
    }

    /**
     * @Route("/admin/commande/devis/ajout/{id}/{status}", options={"expose"=true}, name="order_quote_add")
     */
    public function addItemToQuote(QuoteOrder $order, string $status,
                                   ItemRepository $itemRepo, 
                                   SessionInterface $session,
                                   QuoteOrderDetailRepository $orderDetailRepo,
                                   ObjectManager $manager){

        $panier = $session->get('panier', []);
        if(count($panier) > 0){
            
            foreach ($panier as $id => $quantity) {
                $item = $itemRepo->find($id);
                $orderDetail = $orderDetailRepo->findOneBy(['QuoteOrder' => $order, 'Item' => $item]);
                if(empty($orderDetail)){
                    $orderDetail = new QuoteOrderDetail();
                    $item->setIsErasable(false);
                    $orderDetail->setItem($item);
                    $orderDetail->setQuantity($quantity);
                    $orderDetail->setItemSellPrice(0);
    
                    $orderDetail->setQuoteOrder($order);
    
                    $manager->persist($order);
                    $manager->persist($item);
                    $manager->persist($orderDetail);
                }
            }   
            $manager->flush();         
        }
        else{
            $this->ErrorHandler->info("Votre panier est vide");
        }

        $session->set('panier', []);
        if($status == 'STATUS_ORDER')
            return $this->RedirectToRoute('order_show', ['id' => $order->getId()]);
        else
            return $this->RedirectToRoute('order_show_quote', ['id' => $order->getId()]);

    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ email ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/email/devis/{id}", options={"expose"=true}, name="order_email_quote")
     */
    public function emailQuote(QuoteOrder $order,
                          Request $request, 
                          Mailer $mailer, 
                          SettingManager $settingManager, 
                          PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasEmail($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $contact = $order->getContact();

        if (!empty($contact) && !empty($contact->getEmail())) {

            $form = $request->request->get('order_detail_form')['setting']['email'];
            $file = $webservice->downloadQuote($order, $this->getParameter('file.pdf.quote.download_dir'), $this->getParameter('file.type.download_quote'));

            // $mailer->sendAttachedFile(['to' => $contact->getEmail()], $form['subject'], $this->renderView('email/_partials/quote.html', [
            //     'contact_name' => $contact->getLastName(),
            // ]), $file);

            $view = $this->renderView('email/_partials/quote.html', [
                'contact_name' => $contact->getLastName(),
            ]);

            $event = new GenericEvent([
                'to' => $contact->getEmail(),
                'form' => $form,
                'file' => $file,
                'view' => $view,
            ]);
            $this->eventDispatcher->dispatch(MyEvents::ORDER_EMAIL_BILL, $event);

            $this->ErrorHandler->success("Le devis devis " . basename($file) . " a été envoyé au client avec succès!");
        } else {
            $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");
        }

        return $this->redirectToRoute('order_show', [
            'id' => $order->getId()
        ]);
    }


    /**
     * @Route("/admin/commande/email/facture/{id}", options={"expose"=true}, name="order_email_bill")
     */
    public function emailBill(
        QuoteOrder $order,
        Request $request,
        Mailer $mailer,
        SettingManager $setManager,
        BillRepository $billRepo,
        SettingManager $settingManager,
        PdfWebService $webservice
    ) {

        if (!$this->securityUtility->checkHasEmail($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $contact = $order->getContact();

        if(!empty($contact) && !empty($contact->getEmail())){

            $form = $request->request->get('order_detail_form')['setting']['email'];
            $bill = $billRepo->find($form['bill']);
            $file = $webservice->downloadBill($bill, $this->getParameter('file.pdf.bill.download_dir'), $this->getParameter('file.type.download_order'), $this->getParameter('file.type.download_refund'));
            $view = $this->renderView('email/_partials/bill.html', [
                'contact_name' => $contact->getLastName(),
                'bill_id' => $setManager->get("PDF", "FACTURE_PREFIX")->getValue() . $bill->getId(),
            ]);

            $event = new GenericEvent([
                'to' => $contact->getEmail(),
                'form' => $form,
                'file' => $file,
                'view' => $view,
            ]);
            $this->eventDispatcher->dispatch(MyEvents::ORDER_EMAIL_BILL, $event);

            $this->ErrorHandler->success("La facture " . basename($file) . " a été envoyé au client avec succès!");
           
        }
        else{
            $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");            
        }

        return $this->redirectToRoute('order_show', [
            'id' => $order->getId()
        ]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Ajax calls ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/recherche", options={"expose"=true}, name="order_search")
     */
    public function search(Request $request,
                           ClientRepository $clientRepo,
                           BillRepository $billRepo,
                           AgentRepository $agentRepo)
    {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/
        
        $data = $this->serializer->serialize([
            'object_array' => [
                'data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findCustomBy($request->request->get('search'), $this->getUser()))],
            'format' => 'json',
            'group' => 'class_property'
        ]);        

        return new Response($data);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Savings / Cancels / PDF ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/detail/sauvegarde/{id}", options={"expose"=true}, name="order_detail_save", requirements={"id"="\d+"})
     */
    public function save(QuoteOrder $order,
                         BillRepository $billRepo,
                         OrderStatusRepository $statusRepo,
                         Request $request,
                         ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        $form = $request->request->get('order_detail_form');
        $this->orderManager->loggCommandeSaveInfo($order, $form);
        $order = $this->orderManager->getHydrater()->hydrateQuoteOrderRelationFromForm($order, $form);
        $manager->persist($order);        
        $manager->flush();


        $TotoalOrderBill = $this->orderRepo->findScalarOrderBill($order);
        $totalBilled = $billRepo->findScalarBilledOrder($order);

        dump($TotoalOrderBill);
        dump($totalBilled);die();

        if($TotoalOrderBill == $totalBilled){
            dump($TotoalOrderBill);
            dump($totalBilled);die();
            $status = null;
            if($order->getStatus()->getName() == "STATUS_ORDER")
                $status = $statusRepo->findOneBy(['Name' => 'STATUS_BILL']);
            else if($order->getStatus()->getName() == "STATUS_REFUND")
                $status = $statusRepo->findOneBy(['Name' => 'STATUS_REFUNDBILL']);

            if(!empty($status)){
                $order->setStatus($status);
                $manager->persist($order);
                $manager->flush();
            }
        }
        
        return $this->getRouteFromStatus($order->getStatus(), $order);
    }

    /**
     * @Route("/admin/commande/livraison/sauvegarde/{id}", options={"expose"=true}, name="order_delivery_save", requirements={"id"="\d+"})
     */
    public function deliverySave(QuoteOrder $order,
                         DeliveryStatusRepository $delStatusRepo,
                         ItemRepository $itemRepo,
                         Request $request,
                         ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        
        $form = $request->request->get('order_detail_form');
        $delivery = new Delivery();
       
        $delStatus = $delStatusRepo->findOneBy(['Name' => 'STATUS_NOT_BILLED']);
        
        $delivery->setPackage($form['delivery']['package']);
        $delivery->setCreatedAt(new \DateTime());        
        $delivery->setStatus($delStatus);        
        
        $manager->persist($delivery);  

        foreach($this->orderDetailRepo->findByQuantityRecieved($order) as $orderDetail){

            $qtDelivery = new QuantityDelivery();
            $qtDelivery->setDelivery($delivery); 

            $qtDel = $orderDetail->getQuantityDelivery();
            $qtRecieved = $orderDetail->getQuantityRecieved();

            if(empty($qtDel))
                $qtDel = 0;

            if($qtRecieved)
                $qtDel += $qtRecieved;

            $orderDetail->setQuantityDelivery($qtDel);
            $qtDelivery->setQuantity($qtRecieved);
            $orderDetail->setQuantityRecieved(0);
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
     * @Route("/admin/commande/facturation/sauvegarde/{id}", options={"expose"=true}, name="order_bill_save", requirements={"id"="\d+"})
     */
    public function billSave(
        QuoteOrder $order,
        Request $request,
        DeliveryRepository $deliveryRepo,
        QuantityDeliveryRepository $qtDelRepo,
        DeliveryStatusRepository $delStatusRepo,
        ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $form = $request->request->get('order_detail_form');
        $bill = new Bill();
        
        $status = $delStatusRepo->findOneBy(['Name' => 'STATUS_BILLED']);
        $bill->setClient($order->getClient());
        $bill->setCreatedAt(new \DateTime());
        $bill->setContact($order->getContact());
        
        $amount = 0;
        foreach ($form['bill']['delivery'] as $val) {
            
            $delivery = $deliveryRepo->find($val);
            
            foreach($this->orderDetailRepo->findByDelivery($order, $delivery) as $orderDetail){
                $qtDelivery = $qtDelRepo->findOneByOrderDetailNotBilled($orderDetail);
                
                if($orderDetail){
                    $amount += $qtDelivery->getQuantity() * $orderDetail->getItemSellPrice();
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

        return $this->getRouteFromStatus($order->getStatus(), $order);
    }

    /**
     * @Route("/admin/commande/facturation/annulation/{id}", options={"expose"=true}, name="order_bill_cancel", requirements={"id"="\d+"})
     */
    public function billCancel(
        Bill $bill,
        DeliveryStatusRepository $delStatusRepo,
        DeliveryRepository $deliveryRepo,
        QuantityDeliveryRepository $qtDeliveryRepo,
        ItemRepository $itemRepo,
        ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $status = $delStatusRepo->findOneBy(['Name' => 'STATUS_CANCELED']);

        foreach ($deliveryRepo->findByBill($bill) as $delivery) {
            
            $delivery->setStatus($status);
            $manager->persist($delivery);
        }

        foreach ($this->orderDetailRepo->findByBill($bill) as $orderDetail) {

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

        $order = $this->orderRepo->findOneByBill($bill);
        return $this->getRouteFromStatus($order->getStatus(), $order);
    }

    /**
     * @Route("/admin/commande/livraison/annulation/{id}", options={"expose"=true}, name="order_delivery_cancel", requirements={"id"="\d+"})
     */
    public function deliveryCancel(
        QuantityDelivery $qtDelivery,
        DeliveryRepository $deliveryRepo,
        ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $orderDetail = $qtDelivery->getOrderDetail();

        if (!empty($orderDetail->getQuantityDelivery()))
            $orderDetail->setQuantityDelivery($orderDetail->getQuantityDelivery() - $qtDelivery->getQuantity());
        else
            $orderDetail->setQuantityDelivery(0);

        if (!empty($orderDetail->getQuantityRecieved()))
            $orderDetail->setQuantityRecieved($orderDetail->getQuantityRecieved() + $qtDelivery->getQuantity());
        else
            $orderDetail->setQuantityRecieved($qtDelivery->getQuantity());

        $manager->remove($qtDelivery->getDelivery());
        $manager->remove($qtDelivery);
        
        $manager->persist($orderDetail);
        
        $manager->flush();

        $order = $orderDetail->getQuoteOrder();
        return $this->getRouteFromStatus($order->getStatus(), $order);
    }

    /**
     * @Route("/admin/commande/livraison/reinitialisation/{id}", options={"expose"=true}, name="order_delivery_reset", requirements={"id"="\d+"})
     */
    public function deliveryReset(
        QuoteOrderDetail $orderDetail,
        ObjectManager $manager) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $orderDetail->getQuoteOrder();
        $orderDetail->setQuantityRecieved(0);

        $manager->persist($orderDetail);
        $manager->flush();
        
        return $this->getRouteFromStatus($order->getStatus(), $order);
    }

    /**
     * @Route("/admin/commande/pdf/facture/{id}", options={"expose"=true}, name="order_pdf_bill", requirements={"id"="\d+"})
     */
    public function pdfBill(Bill $bill, 
                            PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $this->orderRepo->findOneByBill($bill);
        $contact = $order->getContact();
        if (!empty($contact)) {
            return $this->file($webservice->downloadBill($bill, $this->getParameter('file.pdf.bill.download_dir'), $this->getParameter('file.type.download_order'), $this->getParameter('file.type.download_refund')));
        } else {
            $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");
        }

        return $this->redirectToRoute('order_show', [
            'id' => $order->getId()
        ]);
    }

    /**
     * @Route("/admin/commande/pdf/devis/{id}", options={"expose"=true}, name="order_pdf_quote", requirements={"id"="\d+"})
     */
    public function pdfQuote(QuoteOrder $order,
                            PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $contact =$order->getContact();
        if(!empty($contact)){
            return $this->file($webservice->downloadQuote($order, $this->getParameter('file.pdf.quote.download_dir'), $this->getParameter('file.type.download_quote')));   
        }
        else{
            $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");
        }

        return $this->redirectToRoute('order_show', [
            'id' => $order->getId()
        ]);
    }

    /**
     * @Route("/admin/commande/pdf/bl/{id}", options={"expose"=true}, name="order_pdf_delivery", requirements={"id"="\d+"})
     */
    public function pdfDelivery(Delivery $delivery, 
                            PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $this->orderRepo->findOneByDelivery($delivery);
        $contact = $order->getContact();
        if (!empty($contact)) {            
            return $this->file($webservice->downloadDelivery($delivery, $this->getParameter('file.pdf.delivery.download_dir')));
        } else {
            $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");
        }

        return $this->redirectToRoute('order_show', [
            'id' => $order->getId()
        ]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Deletes ]--------------------------------------------------------*/


    /**
     * @Route("/admin/commande/{id}/delete", options={"expose"=true}, name="order_delete", requirements={"id"="\d+"})
     */
    public function delete(QuoteOrder $order,
                           ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        foreach($this->orderDetailRepo->findBy(['QuoteOrder' => $order]) as $orderDetail){
            $qtDeliveries = $orderDetail->getQuantityDeliveries();
            $item = $orderDetail->getItem();
            $item->setIsErasable(true);
          
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
            $manager->persist($item);
            $manager->remove($orderDetail);
        }
        $manager->remove($order);
        $manager->flush();
        return $this->RedirectToRoute('order_home');
    }

    /**
     * @Route("/admin/commande/devis/produit/{id}/{idItem}/delete/{status}", options={"expose"=true}, name="order_quote_item_delete", requirements={"id"="\d+"})
     */
    public function deleteQuoteItem(QuoteOrder $order, string $status,
                                    int $idItem,
                                    ItemRepository $itemRepo,
                                    QuoteOrderDetailRepository $orderDetailRepo,
                                    ObjectManager $manager
    ) {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        //dump($order);die();
        $item = $itemRepo->find($idItem);
        $orderDetail = $orderDetailRepo->findOneBy(['QuoteOrder' => $order, 'Item' => $item]);
        if (!empty($orderDetail)) {
            $item->setIsErasable(true);
            
            $manager->remove($orderDetail);
            $manager->persist($order);
            $manager->persist($item);
            $manager->flush();
            $this->ErrorHandler->success("Le produit Ref. ". $item->getRef() . " a été supprimé avec succés!");
        }
        else
            $this->ErrorHandler->error("Une erreur s'est produite lors la suppression du produit Ref. " . $item->getRef() . "!");

        if($status == "STATUS_ORDER")
            return $this->RedirectToRoute('order_show', ['id' => $order->getId()]);
        else
            return $this->RedirectToRoute('order_show_quote', ['id' => $order->getId()]);
    }

    /*_____________________________________________[ Ajax/Data ]_________________________ */

    /**
     * @Route("/admin/commande/donnee", options={"expose"=true}, name="order_data")
     */
    public function dataOrder() {
        
        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/commande/donnee/devis", options={"expose"=true}, name="order_data_quote")
     */
    public function dataQuote()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/commande/donnee/precommande", options={"expose"=true}, name="order_data_preorder")
     */
    public function dataPreorder()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREORDER'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/commande/donnee/preavoir", options={"expose"=true}, name="order_data_prerefund")
     */
    public function dataPrerefund()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/commande/donnee/avoir", options={"expose"=true}, name="order_data_refund")
     */
    public function dataRefund()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUND'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/commande/donnee/Facture", options={"expose"=true}, name="order_data_bill")
     */
    public function dataBill()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_BILL'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/commande/donnee/avoir/Facture", options={"expose"=true}, name="order_data_bill_refund")
     */
    public function dataBillRefund()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDBILL'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/commande/donnee/validation", options={"expose"=true}, name="order_data_valid")
     */
    public function dataCustomerValid()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_VALID'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/commande/donnee/cloturee", options={"expose"=true}, name="order_data_closed")
     */
    public function dataClosed()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_CLOSED'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/commande/donnee/avoir/cloture", options={"expose"=true}, name="order_data_refund_closed")
     */
    public function dataRefundClosed()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED'])]))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /*_____________________________________________[ private ]_________________________ */

    private function getRouteFromStatus(OrderStatus $status, $order){
        
        switch ($status->getName()) {
            case 'STATUS_REFUND':
                return $this->redirectToRoute('order_show_refund', ['id' => $order->getId()]);
            case 'STATUS_VALID':
                return $this->redirectToRoute('order_show_valid', ['id' => $order->getId()]);
            case 'STATUS_ORDER':
                return $this->redirectToRoute('order_show', ['id' => $order->getId()]);
            case 'STATUS_PREREFUND':
                return $this->redirectToRoute('order_show_prerefund', ['id' => $order->getId()]);
            case 'STATUS_PREORDER':
                return $this->redirectToRoute('order_show_preorder', ['id' => $order->getId()]);
            case 'STATUS_QUOTE':
                return $this->redirectToRoute('order_show_quote', ['id' => $order->getId()]);
        }

        return $this->redirectToRoute('order_home');
    }

    private function checkContact(QuoteOrder $order){
        //dump();die();
        if (empty($order->getContact())) {
            $myRoute = null;
            $message = "";
            switch ($order->getStatus()->getName()) {
                case 'STATUS_REFUND':
                case 'STATUS_VALID':
                case 'STATUS_ORDER':
                    $myRoute = 'order_show';
                    $message = "Veuillez renseigner une adresse de livraison!";
                case 'STATUS_PREREFUND':
                    $myRoute = 'order_show_prerefund';
                    $message = "Veuillez renseigner une adresse de livraison pour le pré-avoir";
                case 'STATUS_PREORDER':
                    $myRoute = 'order_show_preorder';
                    $message = "Veuillez renseigner une adresse de livraison pour la pré-commande";
                case 'STATUS_QUOTE':
                    $myRoute = 'order_show_quote';
                    $message = "Veuillez renseigner une adresse de livraison pour le devis";
            }
            $this->ErrorHandler->error($message);
            if(!empty($myRoute)){
                return $this->redirectToRoute($myRoute, [
                    'id' => $order->getId()
                ]);
            }
            else
                $this->orderManager->loggCommandeErr($order, "La route retour apres le check du contact n'a pas pu être trouvée!");
            
        }
        return true;
    }




}