<?php

namespace App\Controller;

use App\Services\Mailer;
use App\Services\Serializer;
use App\Services\OrderManager;
use App\Services\SecurityManager;
use App\Repository\ActionRepository;
use App\Repository\QuoteOrderRepository;
use App\Repository\OrderStatusRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{

    protected $securityUtility;
    protected $orderRepo;
    protected $actionRepo;
    protected $statusRepo;
    protected $serializer;
    protected $orderManager;


    public function __construct(OrderManager $orderManager, 
                                Serializer $serializer, 
                                SecurityManager $securityUtility, 
                                QuoteOrderRepository $orderRepo,
                                ActionRepository $actionRepo,
                                OrderStatusRepository $statusRepo)
    {
        $this->securityUtility = $securityUtility;
        $this->orderRepo = $orderRepo;
        $this->actionRepo = $actionRepo;
        $this->statusRepo = $statusRepo;
        $this->serializer = $serializer;
        $this->orderManager = $orderManager;
    }
    
    /**
     * @Route("/admin", name="home")
     */
    public function dashbord(Mailer $mailer) {
        
        if(empty($this->getUser()) || !$this->getUser()->getIsActivated()){
            return $this->redirectToRoute('security_login', ['message' => "Votre compte n'est pas actif. Veuillez contacter votre administrateur!"]);
        }

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_DASHBORD']))) {
            return $this->redirectToRoute('order');//('security_deny_access');
        }
        $orderStatus = $this->statusRepo->findOneBy(['Name' => 'STATUS_ORDER']);
        $orderBilledStatus = $this->statusRepo->findOneBy(['Name' => 'STATUS_BILL']);
        $refundStatus = $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUND']);
        $refundBilledStatus = $this->statusRepo->findOneBy(['Name' => 'STATUS_REFUNDBILL']);
        return $this->render('home/index.html.twig', [
            'nb_quote' => $this->orderRepo->countByStatus($this->statusRepo->findOneBy(['Name' => 'STATUS_QUOTE'])),
            'nb_order' => $this->orderRepo->countByStatus($orderStatus) + $this->orderRepo->countByStatus($orderBilledStatus),
            'nb_refund' => $this->orderRepo->countByStatus($refundStatus) + $this->orderRepo->countByStatus($refundBilledStatus),
            'nb_validation' => $this->orderRepo->countByStatus($this->statusRepo->findOneBy(['Name' => 'STATUS_VALID'])),
        ]);
    }
}
