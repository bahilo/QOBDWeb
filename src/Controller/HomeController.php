<?php

namespace App\Controller;

use App\Services\SecurityManager;
use App\Repository\ActionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{

    protected $securityUtility;
    protected $actionRepo;


    public function __construct(SecurityManager $securityUtility, ActionRepository $actionRepo)
    {
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
    }
    
    /**
     * @Route("/admin", name="home")
     */
    public function dashbord(ActionRepository $actionRepo) {
        
        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_DASHBORD']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
