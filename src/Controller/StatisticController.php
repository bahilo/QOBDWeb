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

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data =$this->serializer->serialize(['object_array' => $this->statManager->getOrderDataByWeek(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/commande/mois", options={"expose"=true}, name="statistic_order_month")
     */
    public function orderByMonth()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data =$this->serializer->serialize(['object_array' => $this->statManager->getOrderDataByMonth(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/commande/annee", options={"expose"=true}, name="statistic_order_year")
     */
    public function orderByYear()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data =$this->serializer->serialize(['object_array' => $this->statManager->getOrderDataByYear(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    //----------------------[ Count Order ]

    /**
     * @Route("/admin/statistic/commande/total/semaine", options={"expose"=true}, name="statistic_count_order_week")
     */
    public function countOrderByWeek()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountOrderByWeek(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/commande/total/mois", options={"expose"=true}, name="statistic_count_order_month")
     */
    public function countOrderByMonth()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountOrderByMonth(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/commande/total/annee", options={"expose"=true}, name="statistic_count_order_year")
     */
    public function CountOrderByYear()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountOrderByYear(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    //----------------------[ Count Quote ]

    /**
     * @Route("/admin/statistic/devis/total/semaine", options={"expose"=true}, name="statistic_count_quote_week")
     */
    public function countQuoteByWeek()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountQuoteByWeek(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/devis/total/mois", options={"expose"=true}, name="statistic_count_quote_month")
     */
    public function countQuoteByMonth()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountQuoteByMonth(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/devis/total/annee", options={"expose"=true}, name="statistic_count_quote_year")
     */
    public function CountQuoteByYear()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountQuoteByYear(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    //----------------------[ Count Refund ]

    /**
     * @Route("/admin/statistic/avoir/total/semaine", options={"expose"=true}, name="statistic_count_refund_week")
     */
    public function countRefundByWeek()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountRefundByWeek(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/avoir/total/mois", options={"expose"=true}, name="statistic_count_refund_month")
     */
    public function countRefundByMonth()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountRefundByMonth(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/avoir/total/annee", options={"expose"=true}, name="statistic_count_refund_year")
     */
    public function CountRefundByYear()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountRefundByYear(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    //----------------------[ Count Valid ]

    /**
     * @Route("/admin/statistic/commande/revalidation/total/semaine", options={"expose"=true}, name="statistic_count_valid_week")
     */
    public function countValidByWeek()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountValidByWeek(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/commande/revalidation/total/mois", options={"expose"=true}, name="statistic_count_valid_month")
     */
    public function countValidByMonth()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountValidByMonth(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

    /**
     * @Route("/admin/statistic/commande/revalidation/total/annee", options={"expose"=true}, name="statistic_count_valid_year")
     */
    public function CountValidByYear()
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_STATISTIC']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $data = $this->serializer->serialize(['object_array' => $this->statManager->getCountValidByYear(), 'format' => 'json', 'group' => 'class_property']);

        return new Response($data);
    }

}
