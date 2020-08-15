<?php

namespace App\Controller;

use Exception;
use App\Entity\Client;
use App\Entity\Address;
use App\Entity\Contact;
use App\Services\Serializer;
use App\Services\ErrorHandler;
use App\Services\OrderManager;
use App\Services\SearchToView;
use App\Services\ClientHydrate;
use App\Services\SecurityManager;
use App\Repository\BillRepository;
use App\Form\ClientRegistrationType;
use App\Repository\ActionRepository;
use App\Repository\ClientRepository;
use App\Form\AddressRegistrationType;
use App\Form\ContactRegistrationType;
use App\Repository\ContactRepository;
use Doctrine\DBAL\Driver\PDOException;
use JMS\Serializer\SerializerInterface;
use App\Repository\QuoteOrderRepository;
use JMS\Serializer\SerializationContext;
use App\Repository\OrderStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class ClientController extends Controller
{
    protected $serializer;
    protected $securityUtility;
    protected $actionRepo;
    protected $ErrorHandler;
    protected $orderManager;
    protected $clientRepo;
    protected $contactRepo;
    protected $search;


    public function __construct(SecurityManager $securityUtility, 
                                ActionRepository $actionRepo,
                                ClientRepository $clientRepo,
                                ContactRepository $contactRepo,
                                Serializer $serializer,
                                OrderManager $orderManager,
                                ErrorHandler $ErrorHandler,
                                SearchToView $search)
    {
        $this->serializer = $serializer;
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
        $this->clientRepo = $clientRepo;
        $this->contactRepo = $contactRepo;
        $this->ErrorHandler = $ErrorHandler;
        $this->orderManager = $orderManager;
        $this->search = $search;
    }

#region [ Views ]
    /**
     * @Route("/admin/client", options={"expose"=true}, name="client_home")
     * @Route("/admin/client/panier/{cart}", options={"expose"=true}, name="client_cart_home")
     */
    public function home($cart = null,
                        SerializerInterface $serializer, 
                        ClientRepository $clientRepo,
                        ClientHydrate $clientHydrate) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!empty($cart))
            return $this->render('site/' . $this->search->get_site_config()->getCode() . '/client/index.html.twig',['cart' => true]);

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/client/index.html.twig');
    }

    /**
     * @Route("/admin/client/{id}/detail", options={"expose"=true}, name="client_show")
     */
    public function show(Client $client,
                         SerializerInterface $serializer,
                         QuoteOrderRepository $orderRepo,
                         OrderStatusRepository $statusRepo,
                         BillRepository $billRepo,
                         ContactRepository $contactRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $encours = 0;
        try{
            $billAmount = $billRepo->findScalarBillByClient($client);
            $payedBillAmount = $billRepo->findScalarBillPayedByClient($client);

            if (!empty($billAmount) && !empty($payedBillAmount))
            $encours = $billAmount - $payedBillAmount;
            else if (!empty($billAmount) && empty($payedBillAmount))
            $encours = $billAmount;
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant le rendu de la page client!");
            $this->ErrorHandler->error($ex->getMessage());
        }
       
        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/client/show.html.twig', [
            'client' => $client,
            'nb_order' => $orderRepo->countByStatus($statusRepo->findOneBy(['Name' => 'STATUS_ORDER'])),
            'nb_quote' => $orderRepo->countByStatus($statusRepo->findOneBy(['Name' => 'STATUS_QUOTE'])),
            'encours' => $encours,
        ]);
    }
#endregion

#region [ Selection ]

    /**
     * @Route("/admin/client/selection/{id}", options={"expose"=true}, name="client_select")
     */
    public function select($id, SessionInterface $session)
    {
        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        $client = $session->get('client',[]);
        $client['id'] = $id;
        $session->set('client', $client);          
        return $this->RedirectToRoute('order_registration');
    }
#endregion

#region [ Registrations ]

    /**
     * @Route("/admin/client/inscription", options={"expose"=true}, name="client_registration")
     * @Route("/admin/client/{id}/edit", options={"expose"=true}, name="client_edit")
     * 
     */
    public function registration(Client $client = null, 
                                Request $request, 
                                ObjectManager $manager,
                                ValidatorInterface $validator) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!$client)
            $client = new Client();

            // Recherche du contact principal
            $contactPrincipal = $this->contactRepo->findOneBy(['Client' => $client, 'IsPrincipal' => true]);
            if(empty($contactPrincipal)){
                $oneContact = $this->contactRepo->findOneBy(['Client' => $client]);
                if(!empty($oneContact))
                    $client->setContactPrincipal($oneContact);
            }
            else{
                $client->setContactPrincipal($contactPrincipal);
            }
     
            $form = $this->createForm(ClientRegistrationType::class, $client);
            $form->handleRequest($request);
            try{
                if ($form->isSubmitted()) {
                $this->ErrorHandler->registerError($validator->validate($client));

                if ($form->isValid()) {
                    $newContact = $client->getContactPrincipal();
                    $newContact->setIsPrincipal(true);
                    if(!empty($newContact) && $newContact->getId() == 0)
                        $client->addContact($newContact);

                    $client->setIsActivated(true);
                    $client->setIsProspect(true);
                    if (!empty($request->request->get('client_registration')['client_prospect'])) {
                        $client->setIsProspect(false);
                    }

                    $manager->persist($newContact);
                    $manager->persist($client);
                    $manager->flush();
                    $this->ErrorHandler->success("La client a été sauvegardé avec succès!");
                    return $this->redirectToRoute('client_show', ['id' => $client->getId()]);/**/
                }
            } 
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde du client!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/Client/registration.html.twig', [
            'formClient' => $form->createView(),
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
                                       ObjectManager $manager,
                                       ValidatorInterface $validator) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!$address)
            $address = new Address();

        $address = $clientHydrate->hydrateAddress($address);
     
        $form = $this->createForm(AddressRegistrationType::class, $address);
        $form->handleRequest($request);
        
       try{
            if ($form->isSubmitted()) {
                $this->ErrorHandler->registerError($validator->validate($address));

                if ($form->isValid()) {
                    $address = $clientHydrate->hydrateAddressRelationFromForm($address, $request->request->get('client_address_registration'));

                    $manager->persist($address);
                    $manager->flush();
                    $this->ErrorHandler->success("L'adresse a été sauvegardée avec succès!");
                    return $this->redirectToRoute('client_home');
                }
            }
       }catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de l'adresse!");
            $this->ErrorHandler->error($ex->getMessage());
        }
        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/client/address_registration.html.twig', [
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
                                       ObjectManager $manager,
                                       ValidatorInterface $validator) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }
      
        if(!$contact){
            $contact = new Contact();
            if ($idClient)
                $contact->setClient($clientRepo->find($idClient));
        }
     
        $form = $this->createForm(ContactRegistrationType::class, $contact);
        try{
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $this->ErrorHandler->registerError($validator->validate($contact));
                if ($form->isValid()) {
                    $client = $contact->getClient();
                    $contact->setIsPrincipal(false);
                    if (!empty($request->request->get('contact_registration')['is_principal'])) {
                        $contact->setClient($clientHydrate->resetContactPrincipal($client));
                        $contact->setIsPrincipal(true);
                    }
                    $manager->persist($client);
                    $manager->persist($contact);
                    $manager->flush();
                    $this->ErrorHandler->success("Le contact a été sauvegardé avec succès!");
                    return $this->redirectToRoute('client_show', ['id' => $client->getId()]);
                }
            }
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde du contact!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/client/contact_registration.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }
#endregion

#region [ Delete ]

    /**
     * @Route("/admin/client/{id}/delete", options={"expose"=true}, name="client_delete")
     */
    public function delete(Client $client, 
                          ObjectManager $manager,
                          QuoteOrderRepository $orderRepo) {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        try {
            $clientInfo = $client->getCompanyName();
            foreach($client->getContacts() as $contact){
                $manager->remove($client->removeContact($contact));
            }
            $manager->remove($client);
            $manager->flush();
            $this->ErrorHandler->success("Le client " . $clientInfo . " a été supprimé avec succés!");
        } catch (ForeignKeyConstraintViolationException $ex) {
            $orders = $orderRepo->findCustomBy(['client' => $client->getId()], $this->getUser());
            $this->ErrorHandler->error("Suppression impossible, le client est référencé dans " . count($orders) . " commande(s) !");
            return $this->redirectToRoute('client_edit', ['id' => $client->getId()]);
        }
        return $this->RedirectToRoute('client_home');
    }
    

    /**
     * @Route("/admin/client/contact/{id}/delete", options={"expose"=true}, name="client_contact_delete")
     */
    public function contactDelete(Contact $contact, 
                                  ObjectManager $manager, 
                                  QuoteOrderRepository $orderRepo) {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
            try{
                $ContactInfo = $contact->getFirstname() ." ". $contact->getLastName();
                $manager->remove($contact->getAddress());
                $manager->remove($contact);
                $manager->flush();
                $this->ErrorHandler->success("Le contact ". $ContactInfo ." a été supprimé avec succés!");
            }catch(ForeignKeyConstraintViolationException $ex){
                $orders = $orderRepo->findCustomBy(['clientContact' => $contact->getId()], $this->getUser());
                $this->ErrorHandler->error("Suppression impossible, le contact est référencé dans ". count($orders) ." commande(s) !");
            return $this->redirectToRoute('client_contact_edit', ['id' => $contact->getId()]);
        }

        return $this->redirectToRoute('client_show', ['id' => $contact->getClient()->getId()]);
    }
#endregion

#region [ Data/Ajax ]

    /*-------------------------------------------------------------------------------------------------
    ---------------------------------------------[ Json/ Ajax ]---------------------------------------*/

    /**
     * @Route("/admin/client/donnee", options={"expose"=true}, name="client_home_data")
     */
    public function data(
        ClientRepository $clientRepo,
        ClientHydrate $clientHydrate
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            $this->ErrorHandler->error("Vous n'avez pas les droits suffisants pour accéder à cette zone!");
            return new Response("Zone à accés restreint!");
        }

        return new Response($this->serializer->serialize([
            'object_array' =>['data' =>  $clientRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/client/{id}/contacts", options={"expose"=true}, name="client_contact_data")
     */
    public function contactsData(Client $client,
                                ClientRepository $clientRepo,
                                ClientHydrate $clientHydrate
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            $this->ErrorHandler->error("Vous n'avez pas les droits suffisants pour accéder à cette zone!");
            return new Response("Zone à accés restreint!");
        }

        return new Response($this->serializer->serialize([
            'object_array' =>['data' =>  $client->getContacts()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/client/{id}/devis", options={"expose"=true}, name="client_quote_data")
     */
    public function quoteData(Client $client,
                                QuoteOrderRepository $orderRepo,
                                ClientHydrate $clientHydrate
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            $this->ErrorHandler->error("Vous n'avez pas les droits suffisants pour accéder à cette zone!");
            return new Response("Zone à accés restreint!");
        }

        return new Response($this->serializer->serialize([
            'object_array' =>['data' =>  $this->orderManager->getHydrater()->hydrateQuoteOrder($orderRepo->findCustomBy(['client' => $client->getId(), 'orderStatus' => 'STATUS_QUOTE'], $this->getUser()))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    /**
     * @Route("/admin/client/{id}/commande", options={"expose"=true}, name="client_order_data")
     */
    public function orderData(Client $client,
                                QuoteOrderRepository $orderRepo,
                                ClientHydrate $clientHydrate
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_CLIENT']))) {
            $this->ErrorHandler->error("Vous n'avez pas les droits suffisants pour accéder à cette zone!");
            return new Response("Zone à accés restreint!");
        }

        return new Response($this->serializer->serialize([
            'object_array' =>['data' =>  $this->orderManager->getHydrater()->hydrateQuoteOrder($orderRepo->findCustomBy(['client' => $client->getId(), 'orderStatus' => 'STATUS_ORDER'], $this->getUser()))],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

    #endregion
}
