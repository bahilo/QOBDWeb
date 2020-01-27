<?php

namespace App\Controller;

use App\Entity\Item;
use App\Services\Utility;
use App\Services\Serializer;
use App\Services\SecurityManager;
use App\Form\ItemRegistrationType;
use App\Repository\ItemRepository;
use App\Services\CatalogueHydrate;
use App\Repository\ActionRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\QuoteOrderDetailRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CatalogueController extends Controller
{
    protected $securityUtility;
    protected $actionRepo;


    public function __construct(SecurityManager $securityUtility, ActionRepository $actionRepo)
    {
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
    }
    
    /**
     * @Route("/admin/catalogue", name="catalogue_home")
     */
    public function home(Serializer $serializer, 
                          ItemRepository $itemRepo,
                          SessionInterface $session,
                          CatalogueHydrate $catHydrate)
    {
        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_CATALOGUE']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('catalogue/index.html.twig', [
            'item_data_source' => $serializer->serialize([ 'object_array' => $catHydrate->hydrateItem($itemRepo->findAll()), 'format' => 'json', 'group' => 'class_property']),
            'cart_total' => count($session->get('panier', []))
        ]);
    }

    /**
     * @Route("/admin/catalogue/produit/inscription", options={"expose"=true}, name="catalogue_item_registration")
     * @Route("/admin/catalogue/produit/{id}/edit", options={"expose"=true}, name="catalogue_item_edit")
     * 
     */
    public function itemRegistration(Item $item = null, 
                                    Request $request, 
                                    ObjectManager $manager,
                                    CatalogueHydrate $catHydrate,
                                    Utility $utility) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_CATALOGUE']))) {
            return $this->redirectToRoute('security_deny_access');
        }
    
        if(!$item)
            $item = new Item();
        
        $item = $catHydrate->hydrateItem([$item])[0];
     
        $form = $this->createForm(ItemRegistrationType::class, $item);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){

            $item = $catHydrate->hydrateItemRelationFromForm($item, $request->request->get('item_registration'));

            $file = $request->files->get('item_registration')['PictureFile'];

            $fileName = $utility->uploadFile($file, $this->getParameter('file.setting.catalogue.download_dir'));
            if (!empty($fileName)) {
                if (!empty($item->getPicture())) {
                    unlink($this->getParameter('file.setting.catalogue.download_dir') . '/' . $item->getPicture());
                }
                $item->setPicture($fileName);
            }

            $item->setIsErasable(true);

            $manager->persist($item->getComment());
            $manager->persist($item);
            $manager->flush();

            return $this->redirectToRoute('catalogue_home');
        }        

        return $this->render('catalogue/item_registration.html.twig', [
            'formItem' => $form->createView()
        ]);
    }   

    /**
     * @Route("/admin/catalogue/produit/{id}/delete", options={"expose"=true}, name="catalogue_delete")
     */
    public function delete(Item $item, 
                           ObjectManager $manager,
                           QuoteOrderDetailRepository $orderDetailRepo) {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_CATALOGUE']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if($item->getIsErasable()){
            if(!empty($item->getPicture()))
                unlink($this->getParameter('file.setting.catalogue.download_dir').'/'.$item->getPicture());
            
            $manager->remove($item);            
            $manager->flush();
        } 

        return $this->redirectToRoute('catalogue_home');
    }

}
