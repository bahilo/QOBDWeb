<?php

namespace App\Controller;

use App\Services\Serializer;
use App\Services\OrderManager;
use App\Services\SecurityManager;
use App\Services\StatisticManager;
use App\Repository\ActionRepository;
use App\Repository\QuoteOrderRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class StatisticController extends Controller
{

    protected $securityUtility;
    protected $actionRepo;
    protected $serializer;
    protected $orderManager;
    protected $statManager;

    public function __construct(OrderManager $orderManager, 
                                Serializer $serializer, 
                                SecurityManager $securityUtility, 
                                ActionRepository $actionRepo,
                                StatisticManager $statManager)
    {
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
        $this->serializer = $serializer;
        $this->orderManager = $orderManager;
        $this->statManager = $statManager;
    }
    
    /**
     * @Route("/admin/statistic", name="statistic")
     */
    public function index()
    {
        return $this->render('statistic/index.html.twig', [
            'controller_name' => 'StatisticController',
        ]);
    }

    /**
     * @Route("/admin/statistic/commande/semaine", options={"expose"=true}, name="statistic_order_week")
     */
    public function orderByWeek()
    {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        $data =$this->serializer->serialize(['object_array' => $this->statManager->getOrderDataByWeek(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/commande/mois", options={"expose"=true}, name="statistic_order_month")
     */
    public function orderByMonth()
    {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        $data =$this->serializer->serialize(['object_array' => $this->statManager->getOrderDataByMonth(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/commande/annee", options={"expose"=true}, name="statistic_order_year")
     */
    public function orderByYear()
    {

        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        $data =$this->serializer->serialize(['object_array' => $this->statManager->getOrderDataByYear(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

}
