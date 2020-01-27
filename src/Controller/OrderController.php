<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Delivery;
use App\Services\Mailer;
use App\Entity\QuoteOrder;
use App\Services\Serializer;
use App\Services\OrderHydrate;
use App\Services\OrderManager;
use App\Services\PdfWebService;
use App\Entity\QuantityDelivery;
use App\Entity\QuoteOrderDetail;
use App\Repository\TaxRepository;
use App\Services\SecurityManager;
use App\Repository\BillRepository;
use App\Repository\ItemRepository;
use App\Repository\AgentRepository;
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
use App\Services\SettingManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends Controller
{
    protected $orderRepo;
    protected $orderDetailRepo;
    protected $statusRepo;
    protected $orderHydrate;
    protected $serializer;
    protected $securityUtility;
    protected $actionRepo;


    public function __construct(Serializer $serializer, 
                                SecurityManager $securityUtility, 
                                ActionRepository $actionRepo,
                                QuoteOrderRepository $orderRepo,
                                OrderStatusRepository $statusRepo,
                                OrderHydrate $orderHydrate,
                                QuoteOrderDetailRepository $orderDetailRepo)
    {
        $this->orderDetailRepo = $orderDetailRepo;
        $this->orderRepo = $orderRepo;
        $this->statusRepo = $statusRepo;
        $this->orderHydrate = $orderHydrate;
        $this->serializer = $serializer;
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Views ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/accueil", options={"expose"=true}, name="order")
     */
    public function index(AgentRepository $agentRepo, ClientRepository $clientRepo)
    {
        return $this->render('order/index.html.twig', [
            'page_title' => "Commandes",
            'target' => 'order_data_source',
            'agents' => $agentRepo->findAll(),
            'clients' => $clientRepo->findAll(),
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER'])])), 'format' => 'json', 'group' => 'class_property']),
         ]);
    }

    /**
     * @Route("/admin/commande", options={"expose"=true}, name="order_home")
     * @Route("/admin/commande/error/{message}", options={"expose"=true}, name="order_home_error")
     */
    public function home($message = null) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('order/home.html.twig', [
            'page_title' => "Commandes",
            'target' => 'order_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER'])])), 'format' => 'json', 'group' => 'class_property']),
            'message' => $message,
        ]);
    }

    /**
     * @Route("/admin/commande/devis", options={"expose"=true}, name="order_quote")
     */
    public function quote() {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_QUOTE']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        //dump($this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE'])])));die();
        return $this->render('order/home.html.twig', [
            'page_title' => "Devis",
            'target' => 'quote_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser() ,'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/precommande", options={"expose"=true}, name="order_preorder")
     */
    public function preorder() {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PREORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'page_title' => "Commande à valider",
            'target' => 'preorder_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREORDER'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/preavoir", options={"expose"=true}, name="order_prerefund")
     */
    public function prerefund() {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PREREFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'page_title' => "Avoirs à valider",
            'target' => 'prerefund_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/avoir", options={"expose"=true}, name="order_refund")
     */
    public function refund() {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'page_title' => "Avoirs",
            'target' => 'refund_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUND'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/facturation", options={"expose"=true}, name="order_bill")
     */
    public function bill() {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'page_title' => "Factures expédiées",
            'target' => 'bill_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_BILL'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/facturation/avoir", options={"expose"=true}, name="order_bill_refund")
     */
    public function billRefund() {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_BILL']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'page_title' => "Avoirs Expédiés",
            'target' => 'bill_refund_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDBILL'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/validation/client", options={"expose"=true}, name="order_customer_valid")
     */
    public function customerValid() {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_VALID']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        return $this->render('order/home.html.twig', [
            'page_title' => "Commandes à re-valider avec le client",
            'target' => 'customer_valid_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_VALID'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/cloture", options={"expose"=true}, name="order_closed")
     */
    public function closed() {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER_CLOSED']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('order/home.html.twig', [
            'page_title' => "Commandes cloturées",
            'target' => 'closed_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_CLOSED'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/avoir/cloture", options={"expose"=true}, name="order_refund_closed")
     */
    public function refundClosed() {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND_CLOSED']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('order/home.html.twig', [
            'page_title' => "Avoirs cloturés",
            'target' => 'refund_closed_data_source',
            'source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findBy(['Agent' => $this->getUser(), 'Status' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDCLOSED'])])), 'format' => 'json', 'group' => 'class_property'])
        ]);
    }

    /**
     * @Route("/admin/commande/detail/{id}", options={"expose"=true}, name="order_show", requirements={"id"="\d+"})
     * @Route("/admin/commande/detail/{id}/erreur/{message}/{statut}", options={"expose"=true}, name="order_show_report")
     */
    public function show(
        QuoteOrder $order,
        DeliveryRepository $deliveryRepo,
        BillRepository $billRepo,
        OrderManager $orderManager,
        string $message = null,
        $statut = null) {

        if (!$this->securityUtility->checkHasUpdate($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $bills = $billRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']);
        $orderDetrail = $this->orderHydrate->hydrateOrderDetail($this->orderDetailRepo->findBy(['QuoteOrder' => $order]));

        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $orderDetrail, 'format' => 'json', 'group' => 'class_property']),
            'order_detail_delivery_data_source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateOrderDetail($this->orderDetailRepo->findByQuantityRecieved($order)), 'format' => 'json', 'group' => 'class_property']),
            'order_detail_bill_data_source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateOrderDetail($this->orderDetailRepo->findByBillStatus($order)), 'format' => 'json', 'group' => 'class_property']),
            'bill_data_source' => $this->serializer->serialize(['object_array' => $bills, 'format' => 'json', 'group' => 'class_property']),
            'delivery_data_source' => $this->serializer->serialize(['object_array' => $deliveryRepo->findByOrder(['order' => $order, 'status' => 'STATUS_BILLED']), 'format' => 'json', 'group' => 'class_property']),
            'status' => $order->getStatus(),
            'order' => $order,
            'bills' => $bills,
            'deliveries' => $deliveryRepo->findByBillStatus(['order' => $order, 'status' => 'STATUS_NOT_BILLED']),
            'info' => $orderManager->getCommandeInfo($orderDetrail),
            'can_open_row' => true,
            'email_content' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'facture.txt'),
            'code_message' => $message,
            'code_status' => $statut
        ]);
    }

    /**
     * @Route("/admin/commande/devis/detail/{id}", options={"expose"=true}, name="order_show_quote", requirements={"id"="\d+"})
     * @Route("/admin/commande/devis/detail/{id}/erreur/{message}/{statut}", options={"expose"=true}, name="order_show_quote_report")
     */
    public function showQuote(int $id, 
                              OrderManager $orderManager, 
                              string $message = null, 
                              $statut = null) {

        if (!$this->securityUtility->checkHasUpdate($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_QUOTE']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        $order = $this->orderRepo->find($id);
        $orderDetails = $this->orderDetailRepo->findBy(['QuoteOrder' => $order]);

        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateOrderDetail($orderDetails), 'format' => 'json', 'group' => 'class_property']),
            'status_prerefund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREREFUND']),
            'status_preorder' => $this->statusRepo->findOneBy(['Name' => 'STATUS_PREORDER']),
            'order' => $order,
            'status' => $order->getStatus(),
            'info' => $orderManager->getCommandeInfo($orderDetails),
            'email_content' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'devis.txt'),
            'code_message' => $message,
            'code_status' => $statut
        ]);
    }

    /**
     * @Route("/admin/commande/precommande/detail/{id}", options={"expose"=true}, name="order_show_preorder", requirements={"id"="\d+"})
     * @Route("/admin/commande/precommande/detail/{id}/erreur/{message}/{statut}", options={"expose"=true}, name="order_show_preorder_report")
     */
    public function showPreOrder(int $id, 
                                OrderManager $orderManager, 
                                string $message = null, 
                                $statut = null) {

        if (!$this->securityUtility->checkHasUpdate($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PREORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $this->orderRepo->find($id);
        $orderDetails = $this->orderDetailRepo->findBy(['QuoteOrder' => $order]);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateOrderDetail($orderDetails), 'format' => 'json', 'group' => 'class_property']),
            'status_order' => $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER']),
            'status_valid' => $this->statusRepo->findOneBy(['Name' => 'STATUS_VALID']),
            'order' => $order,
            'status' => $order->getStatus(),
            'info' => $orderManager->getCommandeInfo($orderDetails),
            'code_message' => $message,
            'code_status' => $statut
        ]);
    }

    /**
     * @Route("/admin/commande/preavoir/detail/{id}", options={"expose"=true}, name="order_show_prerefund", requirements={"id"="\d+"})
     * @Route("/admin/commande/preavoir/detail/{id}/erreur/{message}/{statut}", options={"expose"=true}, name="order_show_prerefund_report")
     */
    public function showPreRefund(int $id, 
                                  OrderManager $orderManager,
                                  string $message = null,
                                  $statut = null) {

        if (!$this->securityUtility->checkHasUpdate($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_REFUND']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $order = $this->orderRepo->find($id);
        $orderDetails = $this->orderDetailRepo->findBy(['QuoteOrder' => $order]);
        return $this->render('order/show.html.twig', [
            'order_detail_data_source' => $this->serializer->serialize(['object_array' => $this->orderHydrate->hydrateOrderDetail($orderDetails), 'format' => 'json', 'group' => 'class_property']),
            'status_refund' => $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUND']),
            'order' => $order,
            'status' => $order->getStatus(),
            'info' => $orderManager->getCommandeInfo($orderDetails),
            'code_message' => $message,
            'code_status' => $statut
        ]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Validations ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/{id}/validation/{idStatus}", options={"expose"=true}, name="order_validation", requirements={"id"="\d+"})
     */
    public function validation(
        int $idStatus,
        QuoteOrder $order,
        ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        //$this->denyAccessUnlessGranted('ROLE_VALIDATOR');

        $status = $this->statusRepo->find($idStatus);
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
            $status = $this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE']);
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
                $item->setIsErasable(false);
                $orderDetail->setItem($item);
                $orderDetail->setQuantity($quantity);
                $orderDetail->setItemSellPrice(0);

                $orderDetail->setQuoteOrder($order);

                if ($tax)
                    $orderDetail->setTax($tax);

                $manager->persist($order);
                $manager->persist($item);
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
    --------------------------------------------[ email ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/email/devis/{id}", options={"expose"=true}, name="order_email_quote")
     */
    public function emailQuote(QuoteOrder $order,
                          Request $request, 
                          Mailer $mailer, 
                          SettingManager $settingManager, 
                          PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasEmail($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $contact = $order->getContact();
        
        $form = $request->request->get('order_detail_form')['setting']['email'];
        
        $file = $webservice->downloadQuote($order, $this->getParameter('file.pdf.quote.download_dir'), $this->getParameter('file.type.download_quote'));
         
        $mailer->sendAttachedFile($contact->getEmail(), $form['subject'], $this->renderView('email/_partials/quote.html',[
            'contact_name' => $contact->getLastName(),
            'company' => $settingManager->get('SOCIETE', 'societe')->getValue()
         ]), $file);

        return $this->redirectToRoute('order_show_quote_report', [
            'message' => "Le devis devis ". basename($file) . " a été envoyé au client avec succès!",
            'statut' => 200,
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

        if (!$this->securityUtility->checkHasEmail($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $contact = $order->getContact();

        $form = $request->request->get('order_detail_form')['setting']['email'];
        $bill = $billRepo->find($form['bill']);
        $file = $webservice->downloadBill($bill, $this->getParameter('file.pdf.bill.download_dir'), $this->getParameter('file.type.download_order'), $this->getParameter('file.type.download_refund'));

        $mailer->sendAttachedFile($contact->getEmail(), $form['subject'], $this->renderView('email/_partials/bill.html', [
            'contact_name' => $contact->getLastName(),
            'company' => $settingManager->get('SOCIETE', 'societe')->getValue(),
            'bill_id' => $setManager->get("FACTURE","prefix")->getValue() . $bill->getId(),
        ]), $file);

        return $this->redirectToRoute('order_show_report', [
            'message' => "La facture " . basename($file) . " a été envoyé au client avec succès!",
            'statut' => 200,
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

        /*if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/
        
        $data = $this->serializer->serialize([
            'object_array' => [
                'data' => $this->orderHydrate->hydrateQuoteOrder($this->orderRepo->findCustomBy($request->request->get('search')))],
            'format' => 'json',
            'group' => 'class_property'
        ]);        

        return new Response($data);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Savings / Cancels / PDF ]--------------------------------------------------------*/

    /**
     * @Route("/admin/commande/{id}/detail/sauvegarde", options={"expose"=true}, name="order_detail_save", requirements={"id"="\d+"})
     * @Route("/admin/commande/{id}/detail/sauvegarde/error/{message}", options={"expose"=true}, name="order_detail_save_error", requirements={"id"="\d+"})
     */
    public function save($message = null,
                         Request $request,
                         ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_ORDER']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $form = $request->request->get('order_detail_form');
        $order = $this->orderHydrate->hydrateQuoteOrderRelationFromForm($this->orderRepo->find($request->request->get('order')), $form);
        $manager->persist($order);

        foreach($form['tab'] as $key => $val){
            $orderDetail = $this->orderHydrate->hydrateQuoteOrderDetailRelationFromForm($this->orderDetailRepo->find($key), $val);

            $manager->persist($orderDetail->getItem());
            $manager->persist($orderDetail);
        }
        $manager->flush();
       return $this->RedirectToRoute("order_show", [
           'id' => $order->getId()
       ]);
    }

    /**
     * @Route("/admin/commande/{id}/livraison/sauvegarde", options={"expose"=true}, name="order_delivery_save", requirements={"id"="\d+"})
     */
    public function deliverySave(QuoteOrder $order,
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

        foreach($this->orderDetailRepo->findByQuantityRecieved($order) as $orderDetail){

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
     * @Route("/admin/commande/{id}/facturation/sauvegarde", options={"expose"=true}, name="order_bill_save", requirements={"id"="\d+"})
     */
    public function billSave(
        QuoteOrder $order,
        Request $request,
        DeliveryRepository $deliveryRepo,
        QuantityDeliveryRepository $qtDelRepo,
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
            
            foreach($this->orderDetailRepo->findByDelivery($order, $delivery) as $orderDetail){
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
     * @Route("/admin/commande/facturation/{id}/annulation", options={"expose"=true}, name="order_bill_cancel", requirements={"id"="\d+"})
     */
    public function billCancel(
        Bill $bill,
        DeliveryStatusRepository $delStatusRepo,
        DeliveryRepository $deliveryRepo,
        QuantityDeliveryRepository $qtDeliveryRepo,
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

        return $this->RedirectToRoute("order_show", [
            'id' => $this->orderRepo->findOneByBill($bill)->getId()
        ]);
    }

    /**
     * @Route("/admin/commande/pdf/facture/{id}", options={"expose"=true}, name="order_pdf_bill", requirements={"id"="\d+"})
     */
    public function pdfBill(Bill $bill, 
                            PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->file($webservice->downloadBill($bill, $this->getParameter('file.pdf.bill.download_dir'), $this->getParameter('file.type.download_order'), $this->getParameter('file.type.download_refund')));   
    }

    /**
     * @Route("/admin/commande/pdf/devis/{id}", options={"expose"=true}, name="order_pdf_quote", requirements={"id"="\d+"})
     */
    public function pdfQuote(QuoteOrder $order,
                            PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(empty($order->getContact())){
            return $this->redirectToRoute('order_show_quote_report', [
                'message' => "Veuillez renseigner un contact pour le devis",
                'statut' => 500,
                'id' => $order->getId()
            ]);
        }

        return $this->file($webservice->downloadQuote($order, $this->getParameter('file.pdf.quote.download_dir'), $this->getParameter('file.type.download_quote')));   
    }

    /**
     * @Route("/admin/commande/pdf/bl/{id}", options={"expose"=true}, name="order_pdf_delivery", requirements={"id"="\d+"})
     */
    public function pdfDelivery(Delivery $delivery, 
                            PdfWebService $webservice) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->file($webservice->downloadDelivery($delivery, $this->getParameter('file.pdf.delivery.download_dir')));   
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Deletes ]--------------------------------------------------------*/


    /**
     * @Route("/admin/commande/{id}/delete", options={"expose"=true}, name="order_delete", requirements={"id"="\d+"})
     */
    public function delete(QuoteOrder $order,
                           ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_PDF']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        foreach($this->orderDetailRepo->findBy(['QuoteOrder' => $order]) as $orderDetail){
            $qtDeliveries = $orderDetail->getQuantityDeliveries();
            $item = $orderDetail->getItem();
            $item->setIsErasable(true);
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
            $manager->persist($item);
            $manager->remove($orderDetail);
        }
        $manager->remove($order);
        $manager->flush();
        return $this->RedirectToRoute('order_home');
    }
}
