<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\RegistrationType;
use App\Services\QOBDSerializer;
use App\Repository\RoleRepository;
use App\Repository\ActionRepository;
use App\Repository\LicenseRepository;
use App\Repository\PrivilegeRepository;
use App\Repository\ActionTrackerRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/security", name="security_home")
     */
    public function home( QOBDSerializer $QOBDSerializer)
    {
        return $this->render('security/home.html.twig', [
            //'securities_data_source' => $QOBDSerializer->getSerializer()->serialize($securityRepo->findAll(), 'json'),
        ]);
    }

    /**
     * @Route("/inscription", name="security_registration")
     * 
     */
    public function registration(Agent $agent = null, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        if(!$agent){
            $agent = new Agent();
        }
        
        $form = $this->createForm(RegistrationType::class, $agent);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $hash = $encoder->encodePassword($agent, $agent->getPassword());

            $agent->setPassword($hash);
            $agent->setIsAdmin(false);
            $manager->persist($agent);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'formAgent' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", options={"expose"=true}, name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/security/{id}/delete", options={"expose"=true}, name="security_delete")
     */
    public function delete( ObjectManager $manager)
    {
        $manager->remove($security);
        $manager->flush();

        return $this->RedirectToRoute('security_home');
    }
}
