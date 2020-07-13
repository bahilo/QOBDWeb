<?php

namespace App\Controller;

use Exception;
use App\Entity\Bill;
use App\Entity\Delivery;
use App\Events\MyEvents;
use App\Services\Mailer;
use App\Services\Utility;
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
    protected $utility;
    protected $manager;
    protected $qtDelRepo;


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
                                Utility $utility,
                                ObjectManager $manager,
                                QuantityDeliveryRepository $qtDelRepo,
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
        $this->utility = $utility;
        $this->manager = $manager;
        $this->qtDelRepo = $qtDelRepo;
    }

#region [ Liste Order ]

  /*=====================================================================================================================================
  ===================================================================================================================================== 
     ______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Liste Order ]--------------------------------------------------------*/

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
        
        return $this->render('order/home/home_order_valid.html.twig', [
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

#endregion

#region [ show order ]

/*=====================================================================================================================================
  ===================================================================================================================================== 
     ______________________________________________________________________________________________________________________ 
    --------------------------------------------[ show order ]--------------------------------------------------------*/

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
        $orderDetail = $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findBy(['QuoteOrder' => $order]));
        $orderDetrailQtReceived = $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findByQuantityRecieved($order));
        $roderDeliveries = $this->orderManager->getHydrater()->hydrateQuantityDelivery($this->quantityDelRepo->findByBillStatus($order), $order);
        $CreateDeliveries = $this->deliveryRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']);
        $infos = $this->orderManager->getCommandeInfo($orderDetail, $order);
        
        return $this->render('order/show/show_order.html.twig', [
            'count_delivery_processing' => count($this->orderDetailRepo->findByQuantityRecieved($order)),
            'count_bill_processing' => count($this->quantityDelRepo->findByBillStatus($order)),
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
            'email_content' => file_get_contents($this->utility->getAbsoluteRootPath() . '/'. $this->getParameter('file.setting.email') . '/' . 'facture.txt'),
        ]);
    }

    /**
     * @Route("/admin/commande/cloture/detail/{id}", options={"expose"=true}, requirements={"id"="\d+"}, name="order_show_closed")
     */
    public function showOrderClosed(
        QuoteOrder $order,
        CurrencyRepository $currRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $bills = $this->orderManager->getHydrater()->hydrateBill($this->billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), $order);
        $orderDetail = $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findBy(['QuoteOrder' => $order]));
        $orderDetrailQtReceived = $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findByQuantityRecieved($order));
        $roderDeliveries = $this->orderManager->getHydrater()->hydrateQuantityDelivery($this->quantityDelRepo->findByBillStatus($order), $order);
        $CreateDeliveries = $this->deliveryRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']);
        $infos = $this->orderManager->getCommandeInfo($orderDetail, $order);
        
        return $this->render('order/show/show_order_closed.html.twig', [
            'order' => $order,
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'info' => $infos,
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
            'status_prerefund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND']),
            'status_preorder' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREORDER']),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $tvaRepo->findAll(),
            'info' => $this->orderManager->getCommandeInfo($orderDetails, $order),
            'email_content' => file_get_contents($this->utility->getAbsoluteRootPath().'/' . $this->getParameter('file.setting.email') . '/' . 'devis.txt'),
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
            'status_order' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $tvaRepo->findAll(),
            'info' => $this->orderManager->getCommandeInfo($orderDetails, $order),
            'email_content' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'devis.txt'),
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
        $orderDetail = $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findBy(['QuoteOrder' => $order]));
        $orderDetrailQtReceived = $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findByQuantityRecieved($order));
        $roderDeliveries = $this->orderManager->getHydrater()->hydrateQuantityDelivery($this->quantityDelRepo->findByBillStatus($order), $order);
        $CreateDeliveries = $this->deliveryRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']);
        $infos = $this->orderManager->getCommandeInfo($orderDetail, $order);

        return $this->render('order/show/show_refund.html.twig', [
            //'order_detail_data_source' => $this->serializer->serialize(['object_array' => $orderDetail, 'format' => 'json', 'group' => 'class_property']),
            // 'order_detail_delivery_data_source' => $this->serializer->serialize(['object_array' => $orderDetrailQtReceived, 'format' => 'json', 'group' => 'class_property']),
            // 'order_detail_bill_data_source' => $this->serializer->serialize(['object_array' => $roderDeliveries, 'format' => 'json', 'group' => 'class_property']),
            // 'bill_data_source' => $this->serializer->serialize(['object_array' => $bills, 'format' => 'json', 'group' => 'class_property']),
            // 'delivery_data_source' => $this->serializer->serialize(['object_array' => $CreateDeliveries, 'format' => 'json', 'group' => 'class_property']),
            'count_delivery_processing' => count($this->orderDetailRepo->findByQuantityRecieved($order)),
            'count_bill_processing' => count($this->quantityDelRepo->findByBillStatus($order)),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $this->tvaRepo->findAll(),
            'status_refund_close' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED']),
            // 'refund_status_close' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'bills' => $bills,
            'deliveries' => $this->deliveryRepo->findByBillStatus(['order' => $order, 'status' => 'STATUS_NOT_BILLED']),
            'info' => $infos,
            'can_open_row' => true,
            'email_content' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'facture.txt'),
        ]);
    }

    /**
     * @Route("/admin/commande/avoir/cloture/detail/{id}", options={"expose"=true}, requirements={"id"="\d+"}, name="order_show_refund_closed")
     */
    public function showRefundClosed(
        QuoteOrder $order,
        CurrencyRepository $currRepo
    ) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $bills = $this->orderManager->getHydrater()->hydrateBill($this->billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), $order);
        $orderDetail = $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findBy(['QuoteOrder' => $order]));
        $orderDetrailQtReceived = $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findByQuantityRecieved($order));
        $roderDeliveries = $this->orderManager->getHydrater()->hydrateQuantityDelivery($this->quantityDelRepo->findByBillStatus($order), $order);
        $CreateDeliveries = $this->deliveryRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']);
        $infos = $this->orderManager->getCommandeInfo($orderDetail, $order);

        return $this->render('order/show/show_refund_closed.html.twig', [
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $this->tvaRepo->findAll(),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'info' => $infos,
        ]);
    }

    /**
     * @Route("/admin/commande/facturee/detail/{id}", options={"expose"=true}, name="order_show_order_bill", requirements={"id"="\d+"})
    */
    public function showOrderBilled(QuoteOrder $order, 
                                  CurrencyRepository $currRepo,
                                  TaxRepository $tvaRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        //$order = $this->orderRepo->find($id);
        $bills = $this->orderManager->getHydrater()->hydrateBill($this->billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), $order);
        $orderDetails = $this->orderDetailRepo->findBy(['QuoteOrder' => $order]);
        return $this->render('order/show/show_order_billed.html.twig', [
            'status_prerefund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND']),
            'order_status_valid' => $this->statusRepo->findOneBy(['Name' => 'STATUS_VALID']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'status_close' => $this->statusRepo->findOneBy(['Name' => 'STATUS_CLOSED']),
            'bills' => $bills,
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $tvaRepo->findAll(),
            'info' => $this->orderManager->getCommandeInfo($orderDetails, $order),
            'email_content' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'facture.txt'),
        ]);
    }

    /**
     * @Route("/admin/commande/avoir/facture/detail/{id}", options={"expose"=true}, name="order_show_refund_bill", requirements={"id"="\d+"})
    */
    public function showRefundBilled(QuoteOrder $order, 
                                  CurrencyRepository $currRepo,
                                  TaxRepository $tvaRepo) {

        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        //$order = $this->orderRepo->find($id);
        $bills = $this->orderManager->getHydrater()->hydrateBill($this->billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), $order);
        $orderDetails = $this->orderDetailRepo->findBy(['QuoteOrder' => $order]);
        return $this->render('order/show/show_refund_billed.html.twig', [
            'order_status_refund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUND']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'order' => $order,
            'status_close' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED']),
            'bills' => $bills,
            'currencies' => $currRepo->findAll(),
            'taxes' => $tvaRepo->findAll(),
            'info' => $this->orderManager->getCommandeInfo($orderDetails, $order),
            'email_content' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'facture.txt'),
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
             'status_refund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUND']),
            'order_status_quote' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']),
            'order' => $order,
            'currencies' => $currRepo->findAll(),
            'taxes' => $tvaRepo->findAll(),
            'info' => $this->orderManager->getCommandeInfo($orderDetails, $order),
        ]);
    }

#endregion

#region [ Registrations / Validation ]

/*=====================================================================================================================================
  ===================================================================================================================================== 
     ______________________________________________________________________________________________________________________ 
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

        try{
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

                return $this->RedirectToRoute('order_show_quote', ['id' => $order->getId()]);
            }
            $this->ErrorHandler->error("Veuillez renseigner un client !");
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de vos données!");
            $this->ErrorHandler->error($ex->getMessage());
        }
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

        try{
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
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant l'ajout d'un produit dans le devis!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        $session->set('panier', []);
        if($status == 'STATUS_ORDER')
            return $this->RedirectToRoute('order_show', ['id' => $order->getId()]);
        else
            return $this->RedirectToRoute('order_show_quote', ['id' => $order->getId()]);

    }

    /**
     * @Route("/admin/commande/validation/{id}/{idStatus}", options={"expose"=true}, name="order_validation", requirements={"id"="\d+"})
     */
    public function validation(
        int $idStatus,
        QuoteOrder $order,
        SettingManager $settingManager,
        ObjectManager $manager
    ) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        try{
            $status = $this->statusRepo->find($idStatus);
            if ($this->getIsCleanable($order->getStatus(), $status)) {
                $this->clearOrder($order); 
            }

            $order->setStatus($status);
            $manager->persist($order);
            $manager->flush();

            if (empty($order->getContact())){
                $this->ErrorHandler->error("Veuillez renseigner une adresse de livraison pour la commande");
                return $this->getRouteFromStatus($order);
            }

            // envoi d'un mail de prise en compte de la commande
            if (!empty($order->getStatus()) && $order->getStatus()->getName() == 'STATUS_ORDER') {
                $this->orderManager->checkOrderStock($order->getQuoteOrderDetails());
                $societe = $settingManager->get('SOCIETE', 'SOCIETE_NOM');
                // dump($status);
                // dump($order);
                // die();
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
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la modification du statut!");
            $this->ErrorHandler->error($ex->getMessage());
        }
        return $this->getRouteFromStatus($order);
    }

#endregion

#region [ Emails ]

  /*=====================================================================================================================================
  ===================================================================================================================================== 
     ______________________________________________________________________________________________________________________ 
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

        try{
            $contact = $order->getContact();

            if (!empty($contact) && !empty($contact->getEmail())) {

                $form = $request->request->get('order_detail_form')['setting']['email'];
                $devisFile = $webservice->downloadQuotation($order, $this->getParameter('abs.file.pdf.quote.download_dir'));
                $cgvFile = $webservice->downloadCGV($this->getParameter('abs.file.pdf.quote.download_dir'));
            
                $view = $this->renderView('email/_partials/quote.html', [
                    'contact_name' => $contact->getLastName(),
                ]);

                $event = new GenericEvent([
                    'to' => $contact->getEmail(),
                    'form' => $form,
                    'files' => [$devisFile, $cgvFile],
                    'view' => $view,
                ]);
                $this->eventDispatcher->dispatch(MyEvents::ORDER_EMAIL_BILL, $event);
                $this->ErrorHandler->success("Le devis devis " . basename($devisFile) . " a été envoyé au client avec succès!");
            } else {
                $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la création de l'email!");
            $this->ErrorHandler->error($ex->getMessage());
        }
        return $this->redirectToRoute('order_show_quote', [
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

        try{
            $contact = $order->getContact();

            if (!empty($contact) && !empty($contact->getEmail())) {

                $form = $request->request->get('order_detail_form')['setting']['email'];
                $bill = $billRepo->find($form['bill']);
                $file = $webservice->downloadOrder($order, $bill, $this->getParameter('abs.file.pdf.bill.download_dir'));
                $view = $this->renderView('email/_partials/bill.html', [
                    'contact_name' => $contact->getLastName(),
                    'bill_id' => $setManager->get("PDF", "FACTURE_PREFIX")->getValue() . $bill->getId(),
                ]);

                $event = new GenericEvent([
                    'to' => $contact->getEmail(),
                    'form' => $form,
                    'files' => [$file],
                    'view' => $view,
                ]);
                $this->eventDispatcher->dispatch(MyEvents::ORDER_EMAIL_BILL, $event);

                $this->ErrorHandler->success("La facture " . basename($file) . " a été envoyé au client avec succès!");
            } else {
                $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");
            }
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la création de l'email!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->redirectToRoute('order_show', [
            'id' => $order->getId()
        ]);
    }
    #endregion 

#region [ Savings / Cancels ]
 /*=====================================================================================================================================
  ===================================================================================================================================== 
     ______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Savings / Cancels ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/detail/sauvegarde/{id}", options={"expose"=true}, name="order_detail_save", requirements={"id"="\d+"})
     */
    public function save(QuoteOrder $order,
                         BillRepository $billRepo,
                         OrderStatusRepository $statusRepo,
                         Request $request,
                         Utility $utility,
                         ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        try{
            $form = $request->request->get('order_detail_form');            
            $order = $this->orderManager->getHydrater()->hydrateQuoteOrderRelationFromForm($order, $form);            
            $manager->persist($order);
            $manager->flush();
            $this->ErrorHandler->success("Vos données on été sauvegardées avec succès!");
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de vos données!");
            $this->ErrorHandler->error($ex->getMessage());
        }
        
        return $this->getRouteFromStatus($order);
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

        try{
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
        
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la création du BL!");
            $this->ErrorHandler->error($ex->getMessage());
        }

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

        try{
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

            $manager->persist($order);
            $order = $this->orderManager->setOrderbilled($order);
            $manager->flush();
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la création de la facture!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->getRouteFromStatus($order);
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
        try{
            $message = "Votre facture n°". $bill->getId()." d'un montant de ". $bill->getPay()."Eur a été supprimée avec succès!";
            $order = $this->orderRepo->findOneByBill($bill);
    
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
            $this->deleteBill($bill);
            $this->ErrorHandler->success($message);
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite lors de la suppression de votre facture!");
        }       
        
        return $this->getRouteFromStatus($order);
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

        $order = null;

        try{
            $message = "Votre BL n°". $qtDelivery->getId()." a été supprimé avec succès!";
            $orderDetail = $qtDelivery->getOrderDetail();

            if (!empty($orderDetail->getQuantityDelivery()))
                $orderDetail->setQuantityDelivery($orderDetail->getQuantityDelivery() - $qtDelivery->getQuantity());
            else
                $orderDetail->setQuantityDelivery(0);

            if (!empty($orderDetail->getQuantityRecieved()))
                $orderDetail->setQuantityRecieved($orderDetail->getQuantityRecieved() + $qtDelivery->getQuantity());
            else
                $orderDetail->setQuantityRecieved($qtDelivery->getQuantity());

            $delivery = $qtDelivery->getDelivery();
            $qtDelivery->setDelivery(null);
            $manager->remove($delivery);
            $manager->remove($qtDelivery);
            $manager->persist($orderDetail);

            $manager->flush();

            $order = $orderDetail->getQuoteOrder();
            $this->ErrorHandler->success($message);
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression de votre BL!");
            $this->ErrorHandler->error($ex->getMessage());
        }
        return $this->getRouteFromStatus($order);
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
        try{
            $orderDetail->setQuantityRecieved(0);

            $manager->persist($orderDetail);
            $manager->flush();
            $this->ErrorHandler->success("La rérérence a été annullé avec succès!");
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant l'annulation de la référence!");
        }
        
        return $this->getRouteFromStatus($order);
    }

#endregion

#region [ PDF ]
 /*=====================================================================================================================================
  ===================================================================================================================================== 
    ______________________________________________________________________________________________________________________ 
    --------------------------------------------[ PDF ]--------------------------------------------------------*/

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
        try{
            if (!empty($contact)) {
                return $this->file($webservice->downloadOrder($order, $bill, $this->getParameter('abs.file.pdf.bill.download_dir')));
            } else {
                $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");
            }
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la création du la facture!");
            $this->ErrorHandler->error($ex->getMessage());
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
       
        try {
            if (!empty($contact)) {
                return $this->file($webservice->downloadQuotation($order, $this->getParameter('abs.file.pdf.quote.download_dir')));
                //return $this->file($webservice->downloadCGV($this->getParameter('abs.file.pdf.quote.download_dir')));   
            } else {
                $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la création du devis!");
            $this->ErrorHandler->error($ex->getMessage());
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

        try {
            if (!empty($contact)) {
                return $this->file($webservice->downloadDelivery($delivery, $this->getParameter('abs.file.pdf.delivery.download_dir')));
            } else {
                $this->ErrorHandler->error("Veuillez Renseigner une adresse de livraison!");
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la création du BL!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->redirectToRoute('order_show', [
            'id' => $order->getId()
        ]);
    }

#endregion

#region [ Deletes ]
/*=====================================================================================================================================
  ===================================================================================================================================== 
     ______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Deletes ]--------------------------------------------------------*/


    /**
     * @Route("/admin/commande/{id}/delete", options={"expose"=true}, name="order_delete", requirements={"id"="\d+"})
     */
    public function delete(QuoteOrder $order,
                           ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        try{
            foreach ($this->orderDetailRepo->findBy(['QuoteOrder' => $order]) as $orderDetail) {
                $qtDeliveries = $orderDetail->getQuantityDeliveries();
                $item = $orderDetail->getItem();
                $item->setIsErasable(true);

                if ($qtDeliveries) {
                    foreach ($qtDeliveries as $qtDelivery) {
                        $bill = $qtDelivery->getBill();
                        if ($bill)
                            $this->manager->remove($bill);

                        $delivery = $qtDelivery->getDelivery();
                        if ($delivery)
                            $this->manager->remove($delivery);

                        $this->manager->remove($qtDelivery);
                    }
                }
                $this->manager->persist($item);
                $this->manager->remove($orderDetail);
            }
            $this->manager->remove($order);
            $this->manager->flush();
        
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression de la commande!");
            $this->ErrorHandler->error($ex->getMessage());
        }
        return $this->getRouteFromStatus($order, false);
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
        
        try{
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
            
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression du produit!");
            $this->ErrorHandler->error($ex->getMessage());
        }
    }

    #endregion

#region [ Ajax/Data ]
  /*=====================================================================================================================================
  ===================================================================================================================================== 
     ______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Ajax/Data ]--------------------------------------------------------*/

    #region [ show details ]

    /**
     * @Route("/admin/commande/detail/{id}/donnee", options={"expose"=true}, name="order_data_detail")
     */
    public function dataOrderDetails(QuoteOrder $order)
    {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        $data = $this->serializer->serialize([
            'object_array' => [
                'data' => $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findBy(['QuoteOrder' => $order]))
            ],
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return new Response($data);
    }

    /**
     * @Route("/admin/commande/detail/creation/bl/{id}/donnee", options={"expose"=true}, name="order_data_delivery_processing_receipt")
     */
    public function dataDeliveryProcessingReceipt(QuoteOrder $order)
    {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        $data = $this->serializer->serialize([
            'object_array' => [
                'data' => $this->orderManager->hydrateOrderDetailStats($this->orderDetailRepo->findByQuantityRecieved($order))
            ],
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return new Response($data);
    }

    /**
     * @Route("/admin/commande/detail/creation/facture/{id}/donnee", options={"expose"=true}, name="order_data_bill_processing_receipt")
     */
    public function dataBillProcessingReceipt(QuoteOrder $order)
    {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        $data = $this->serializer->serialize([
            'object_array' => [
                'data' => $this->orderManager->getHydrater()->hydrateQuantityDelivery($this->quantityDelRepo->findByBillStatus($order), $order)
            ],
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return new Response($data);
    }
    
    /**
     * @Route("/admin/commande/detail/bl/{id}/donnee", options={"expose"=true}, name="order_data_delivery_receipt")
     */
    public function dataDeliveryReceipt(QuoteOrder $order)
    {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        $data = $this->serializer->serialize([
            'object_array' => [
                'data' => $this->deliveryRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED'])
            ],
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return new Response($data);
    }

    /**
     * @Route("/admin/commande/detail/facture/{id}/donnee", options={"expose"=true}, name="order_data_bill_receipt")
     */
    public function dataBillReceipt(QuoteOrder $order) {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        $data = $this->serializer->serialize([
            'object_array' => [
                'data' => $this->orderManager->getHydrater()->hydrateBill($this->billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), $order)
            ],
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return new Response($data);
    }

    #endregion

    #region [ listes ]

    /**
     * @Route("/admin/commande/recherche", options={"expose"=true}, name="order_search")
     */
    public function dataSearch(
        Request $request,
        ClientRepository $clientRepo,
        BillRepository $billRepo,
        AgentRepository $agentRepo
    ) {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        $data = $this->serializer->serialize([
            'object_array' => [
                'data' => $this->orderManager->getHydrater()->hydrateQuoteOrder($this->orderRepo->findCustomBy($request->request->get('search'), $this->getUser()))
            ],
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return new Response($data);
    }

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

    #endregion

#endregion

#region [ Private ]

 /*=====================================================================================================================================
  ===================================================================================================================================== 
     ______________________________________________________________________________________________________________________ 
      --------------------------------------------[ Private ]--------------------------------------------------------*/

    private function getRouteFromStatus($order, bool $isShow = true){
        
        try{
            switch ($order->getStatus()->getName()) {
                case 'STATUS_REFUND':
                    if ($isShow)
                        return $this->redirectToRoute('order_show_refund', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_refund');
                case 'STATUS_VALID':
                    if ($isShow)
                        return $this->redirectToRoute('order_show_valid', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_customer_valid');
                case 'STATUS_ORDER':
                    if ($isShow)
                        return $this->redirectToRoute('order_show', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_home');
                case 'STATUS_PREREFUND':
                    if ($isShow)
                        return $this->redirectToRoute('order_show_prerefund', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_prerefund');
                case 'STATUS_PREORDER':
                    if ($isShow)
                        return $this->redirectToRoute('order_show_preorder', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_preorder');
                case 'STATUS_REFUNDCLOSED':
                    if ($isShow)
                        return $this->redirectToRoute('order_show_refund_closed', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_refund_closed');
                case 'STATUS_CLOSED':
                    if ($isShow)
                        return $this->redirectToRoute('order_show_closed', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_closed');
                case 'STATUS_QUOTE':
                    if ($isShow)
                        return $this->redirectToRoute('order_show_quote', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_quote');
                case 'STATUS_BILL':
                    if ($isShow)
                        return $this->redirectToRoute('order_show_order_bill', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_bill');
                case 'STATUS_REFUNDBILL':
                    if ($isShow)
                        return $this->redirectToRoute('order_show_refund_bill', ['id' => $order->getId()]);
                    return $this->redirectToRoute('order_bill_refund');
            }
        }
        catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant l'opération!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->redirectToRoute('order_home');
    }

    private function deleteBill($bill){
        $qtDeliveries = $this->qtDelRepo->findByBill($bill);
        if ($qtDeliveries) {
            foreach ($qtDeliveries as $qtDelivery) {
                $bill = $qtDelivery->getBill();
                if ($bill)
                    $this->manager->remove($bill);

                $delivery = $qtDelivery->getDelivery();
                if ($delivery)
                    $this->manager->remove($delivery);
                
                $this->manager->remove($qtDelivery);
                /*dump($qtDelivery);
                die();*/
            }
            $this->manager->flush();
        }
    }

    private function clearOrder(QuoteOrder $order){        
        try{
            foreach ($this->orderDetailRepo->findBy(['QuoteOrder' => $order]) as $orderDetail) {
                $qtDeliveries = $orderDetail->getQuantityDeliveries();
                //$item = $orderDetail->getItem();
                //$item->setIsErasable(true);

                if ($qtDeliveries) {
                    foreach ($qtDeliveries as $qtDelivery) {
                        $bill = $qtDelivery->getBill();
                        if ($bill)
                            $this->manager->remove($bill);

                        $delivery = $qtDelivery->getDelivery();
                        if ($delivery)
                            $this->manager->remove($delivery);

                        $this->manager->remove($qtDelivery);
                    }
                }
                $orderDetail->setQuantityDelivery(0);
                $orderDetail->setQuantityRecieved(0);
                $this->manager->persist($orderDetail);
            }
            $this->manager->flush();
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la manipulation de la commande #". $order->getId());
        }
        
    }

    private function getIsCleanable(OrderStatus $statusFrom, OrderStatus $statusTo){
        $statusArray = [
            "STATUS_ORDER", 
            "STATUS_CLOSED", 
            "STATUS_BILL",
            "STATUS_REFUND", 
            "STATUS_REFUNDCLOSED", 
            "STATUS_REFUNDBILL",
        ];
        if (in_array($statusFrom->getName(), $statusArray) && !in_array($statusTo->getName(), $statusArray)) {
            return true;
        }
        return false;
    }
#endregion



}