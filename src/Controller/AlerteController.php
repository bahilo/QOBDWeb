<?php

namespace App\Controller;

use App\Services\SearchToView;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AlerteController extends Controller
{

    protected $search;

    public function __construct(SearchToView $search
    ) {
        $this->search = $search;
    }
    

    /**
     * @Route("/admin/alerte", name="alerte")
     */
    public function index()
    {
        /*if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_AGENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }*/

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/alerte/index.html.twig', [
            'controller_name' => 'AlerteController',
        ]);
    }
}
