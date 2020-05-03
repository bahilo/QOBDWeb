<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Services\Utility;
use App\Services\Serializer;
use App\Services\ErrorHandler;
use App\Services\SecurityManager;
use App\Repository\AgentRepository;
use App\Repository\ActionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AgentController extends Controller
{

    protected $securityUtility;
    protected $actionRepo;
    protected $ErrorHandler;


    public function __construct(SecurityManager $securityUtility, 
                                ActionRepository $actionRepo,
                                ErrorHandler $ErrorHandler)
    {
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
        $this->ErrorHandler = $ErrorHandler;
    }
    
    /**
     * @Route("/admin/agent", name="agent_home")
     */
    public function home(AgentRepository $agentRepo, Serializer $serializer, Utility $utility)
    {
        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_AGENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('agent/index.html.twig', [
            'agents_data_source' => $serializer->serialize(['object_array' => $agentRepo->findAll(), 'format' => 'json', 'group' => 'class_property']),
        ]);
    }

    /**
     * @Route("/admin/agent/{id}/show", options={"expose"=true}, name="agent_show")
     */
    public function show(Agent $agent) {
        return $this->redirectToRoute('security_edit',['id' => $agent->getId()]);
    }
}
