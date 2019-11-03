<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\RoleRepository;
use App\Repository\PrivilegeRepository;
use App\Repository\ActionRepository;
use App\Repository\LicenseRepository;
use App\Repository\ActionTrackerRepository;
use App\Services\QOBDSerializer;

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
     * @Route("/security/create", name="security_create")
     * @Route("/security/{id}/edit", options={"expose"=true}, name="security_edit")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        /*if(!$security){
            $security = new Security();
        }
        
        $form = $this->createFormBuilder($security)
                     ->add('FirstName')
                     ->add('LastName')
                     ->add('Phone')
                     ->add('Fax')
                     ->add('Email')
                     ->add('UserName')
                     ->add('Password')
                     ->add('Picture')
                     ->add('IsActivated')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $security->setIsAdmin(false);
            $manager->persist($security);
            $manager->flush();

            return $this->redirectToRoute('security_home', [
                'id' => $security->getId()
            ]);
        }*/

        return $this->render('security/create.html.twig', [
            //'formsecurity' => $form->createView()
        ]);
    }

    /**
     * @Route("/security/{id}/show", options={"expose"=true}, name="security_show")
     */
    public function show()
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'securityController',
        ]);
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
