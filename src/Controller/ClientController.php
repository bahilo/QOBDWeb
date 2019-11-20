<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Address;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Services\ClientHydrate;
use App\Form\ClientRegistrationType;
use App\Repository\ClientRepository;
use App\Form\AddressRegistrationType;
use App\Form\ContactRegistrationType;
use App\Repository\ContactRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ClientController extends Controller
{
    /**
     * @Route("/admin/client", options={"expose"=true}, name="client_home")
     */
    public function home(SerializerInterface $serializer, 
                        ClientRepository $clientRepo,
                        ClientHydrate $clientHydrate)
    {
        return $this->render('client/index.html.twig', [
            'client_data_source' => $serializer->serialize($clientHydrate->hydrate($clientRepo->findAll()), 'json', SerializationContext::create()->setGroups(array('class_property'))),
        ]);
    }

    /**
     * @Route("/admin/client/{id}/detail", options={"expose"=true}, name="client_show")
     */
    public function show(Client $client,
                         SerializerInterface $serializer,
                         ContactRepository $contactRepo)
    {        
        return $this->render('client/show.html.twig', [
            'client' => $client,
            'contact_data_source' => $serializer->serialize($contactRepo->findBy(['Client' => $client]), 'json', SerializationContext::create()->setGroups(array('class_property'))),
        ]);
    }

    /**
     * @Route("/admin/client/selection/{id}", options={"expose"=true}, name="client_select")
     */
    public function select($id, SessionInterface $session)
    {
        $client = $session->get('client',[]);

        $client['id'] = $id;

        $session->set('client', $client);
          
        return $this->RedirectToRoute('cart_home');
    }

    /**
     * @Route("/admin/client/inscription", options={"expose"=true}, name="client_registration")
     * @Route("/admin/client/{id}/edit", options={"expose"=true}, name="client_edit")
     * 
     */
    public function registration(Client $client = null, 
                                Request $request, 
                                ObjectManager $manager,
                                ClientHydrate $clientHydrate)
    {
        if(!$client)
            $client = new Client();
        else
            $client = $clientHydrate->hydrate([$client])[0];
     
        $form = $this->createForm(ClientRegistrationType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){

            $client = $clientHydrate->hydrateClientRelationFromForm($client, $request->request->get('client_registration'));
            
            foreach($client->getContacts()->toArray() as $contact){
                $manager->persist($contact);
            }
                        
            $manager->persist($client);
            $manager->flush();

            return $this->redirectToRoute('client_home');/**/
        }       

        return $this->render('Client/registration.html.twig', [
            'formClient' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/client/address/registration", options={"expose"=true}, name="client_address_registration")
     * @Route("/admin/client/address/{id}", options={"expose"=true}, name="client_address_edit")
     */
    public function addressRegistration(Address $address = null,
                                       ContactRepository $contactRepo, 
                                       Request $request,
                                       ClientHydrate $clientHydrate,
                                       ObjectManager $manager)
    {
        if(!$address)
            $address = new Address();

        $address = $clientHydrate->hydrateAddress($address);
     
        $form = $this->createForm(AddressRegistrationType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){

            $address = $clientHydrate->hydrateAddressRelationFromForm($address, $request->request->get('client_address_registration'));

            $manager->persist($address);
            $manager->flush();

            return $this->redirectToRoute('client_home');
        }

        return $this->render('client/address_registration.html.twig', [
            'formAddress' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/client/{idClient}/contact/inscription", options={"expose"=true}, name="client_contact_registration")
     * @Route("/admin/client/contact/{id}", options={"expose"=true}, name="client_contact_edit")
     */
    public function contactRegistration(Contact $contact = null,
                                        $idClient = null,
                                       ClientRepository $clientRepo, 
                                       Request $request,
                                       ClientHydrate $clientHydrate,
                                       ObjectManager $manager)
    {
       
        if(!$contact)
            $contact = new Contact();
        
        $contact = $clientHydrate->hydrateContact($contact);
     
        $form = $this->createForm(ContactRegistrationType::class, $contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($idClient)
                $contact->setClient($clientRepo->find($idClient));  

            $contact = $clientHydrate->hydrateContactRelationFromForm($contact, $request->request->get('contact_registration'));
            
            $manager->persist($contact);
            $manager->flush();

            return $this->redirectToRoute('client_home');
        }

        return $this->render('client/contact_registration.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }
    

    /**
     * @Route("/admin/client/{id}/delete", options={"expose"=true}, name="client_delete")
     */
    public function delete(Client $client, ObjectManager $manager)
    {
        $manager->remove($client);
        $manager->flush();

        return $this->RedirectToRoute('client_home');
    }
    

    /**
     * @Route("/admin/client/contact/{id}/delete", options={"expose"=true}, name="client_contact_delete")
     */
    public function contactDelete(Contact $contact, ObjectManager $manager)
    {
        $manager->remove($contact);
        $manager->flush();

        return $this->RedirectToRoute('client_home');
    }

}
