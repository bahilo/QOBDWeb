<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\AgentRepository;
use App\Entity\Agent;
use App\Services\QOBDSerializer;

class AgentController extends Controller
{

    /**
     * @Route("/", name="agent_home")
     */
    public function home(AgentRepository $agentRepo, QOBDSerializer $QOBDSerializer)
    {
        return $this->render('agent/home.html.twig', [
            'agents_data_source' => $QOBDSerializer->getSerializer()->serialize($agentRepo->findAll(), 'json'),
        ]);
    }

    /**
     * @Route("/agent/create", name="agent_create")
     * @Route("/agent/{id}/edit", options={"expose"=true}, name="agent_edit")
     */
    public function create(Agent $agent = null,Request $request, ObjectManager $manager)
    {
        if(!$agent){
            $agent = new Agent();
        }
        
        $form = $this->createFormBuilder($agent)
                     ->add('FirstName')
                     ->add('LastName')
                     ->add('Phone')
                     ->add('Fax')
                     ->add('Email')
                     ->add('UserName')
                     ->add('Password')
                     ->add('Confirme_password')
                     ->add('Picture')
                     ->add('IsActivated')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $agent->setIsAdmin(false);
            $manager->persist($agent);
            $manager->flush();

            return $this->redirectToRoute('agent_home', [
                'id' => $agent->getId()
            ]);
        }

        return $this->render('agent/create.html.twig', [
            'formAgent' => $form->createView()
        ]);
    }

    /**
     * @Route("/agent/{id}/show", options={"expose"=true}, name="agent_show")
     */
    public function show(Agent $agent)
    {
        return $this->render('agent/index.html.twig', [
            'controller_name' => 'AgentController',
        ]);
    }

    /**
     * @Route("/agent/{id}/delete", options={"expose"=true}, name="agent_delete")
     */
    public function delete(Agent $agent, ObjectManager $manager)
    {
        $manager->remove($agent);
        $manager->flush();

        return $this->RedirectToRoute('agent_home');
    }
}
