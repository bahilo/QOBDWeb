<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Comment;
use App\Entity\Provider;
use App\Entity\ItemBrand;
use App\Entity\ItemGroupe;
use App\Services\QOBDSerializer;
use App\Form\ItemRegistrationType;
use App\Repository\ItemRepository;
use App\Services\CatalogueHydrate;
use App\Form\ProviderRegistrationType;
use App\Form\ItemBrandRegistrationType;
use JMS\Serializer\SerializerInterface;
use App\Form\ItemGroupeRegistrationType;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CatalogueController extends Controller
{
    /**
     * @Route("/admin/catalogue", name="catalogue_home")
     */
    public function home(SerializerInterface $serializer, 
                          ItemRepository $itemRepo,
                          SessionInterface $session,
                          CatalogueHydrate $catHydrate)
    {               
        return $this->render('catalogue/index.html.twig', [
            'item_data_source' => $serializer->serialize($catHydrate->hydrateItem($itemRepo->findAll()), 'json', SerializationContext::create()->setGroups(array('class_property'))),
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
                                    CatalogueHydrate $catHydrate)
    {
        if(!$item)
            $item = new Item();
        
        $item = $catHydrate->hydrateItem([$item])[0];
     
        $form = $this->createForm(ItemRegistrationType::class, $item);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){

            $item = $catHydrate->hydrateItemRelationFromForm($item, $request->request->get('item_registration'));

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
     * @Route("/admin/catalogue/marque/inscription", options={"expose"=true}, name="catalogue_brand_registration")
     * @Route("/admin/catalogue/marque/{id}/edit", options={"expose"=true}, name="catalogue_brand_edit")
     * 
     */
    public function brandRegistration(ItemBrand $itemBrand = null, Request $request, ObjectManager $manager)
    {
        if(!$itemBrand)
            $itemBrand = new ItemBrand();
        
        $form = $this->createForm(ItemBrandRegistrationType::class, $itemBrand);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $itemBrand->setCreatedAt(new \DateTime());
            $itemBrand->setIsEnabled(true);

            $manager->persist($itemBrand);
            $manager->flush();

            return $this->redirectToRoute('catalogue_home');
        }        

        return $this->render('catalogue/brand_registration.html.twig', [
            'formBrand' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/catalogue/famille/inscription", options={"expose"=true}, name="catalogue_group_registration")
     * @Route("/admin/catalogue/famille/{id}/edit", options={"expose"=true}, name="catalogue_group_edit")
     * 
     */
    public function groupRegistration(ItemGroupe $itemGroupe = null, Request $request, ObjectManager $manager)
    {
        if(!$itemGroupe)
            $itemGroupe = new ItemGroupe();
        
        $form = $this->createForm(ItemGroupeRegistrationType::class, $itemGroupe);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $itemGroupe->setIsEnabled(true);

            $manager->persist($itemGroupe);
            $manager->flush();

            return $this->redirectToRoute('catalogue_home');
        }        

        return $this->render('catalogue/group_registration.html.twig', [
            'formGroup' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/catalogue/fournisseur/inscription", options={"expose"=true}, name="catalogue_provider_registration")
     * @Route("/admin/catalogue/fournisseur/{id}/edit", options={"expose"=true}, name="catalogue_provider_edit")
     * 
     */
    public function providerRegistration(Provider $provider = null, Request $request, ObjectManager $manager)
    {
        if(!$provider)
            $provider = new Provider();
        
        $form = $this->createForm(ProviderRegistrationType::class, $provider);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $provider->setIsEnabled(true);

            $manager->persist($provider);
            $manager->flush();

            return $this->redirectToRoute('catalogue_home');
        }        

        return $this->render('catalogue/provider_registration.html.twig', [
            'formProvider' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/catalogue/fournisseur/{id}/delete", options={"expose"=true}, name="catalogue_provider_delete")
     */
    public function providerDelete(Provider $provider, ObjectManager $manager){

        $manger->remove($provider);
        $manger->flush();
    }

    /**
     * @Route("/admin/catalogue/famille/{id}/delete", options={"expose"=true}, name="catalogue_group_delete")
     */
    public function groupDelete(ItemGroupe $group, ObjectManager $manager){

        $manger->remove($group);
        $manger->flush();
    }

    /**
     * @Route("/admin/catalogue/marque/{id}/delete", options={"expose"=true}, name="catalogue_brand_delete")
     */
    public function brandDelete(ItemBrand $brand, ObjectManager $manager){

        $manger->remove($brand);
        $manger->flush();
    }

    /**
     * @Route("/admin/catalogue/produit/{id}/delete", options={"expose"=true}, name="catalogue_delete")
     */
    public function delete(Item $item, ObjectManager $manager){

        $manger->remove($item);
        $manger->flush();
    }

}
