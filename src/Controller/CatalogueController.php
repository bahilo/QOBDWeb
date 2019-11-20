<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Comment;
use App\Entity\Provider;
use App\Entity\ItemBrand;
use App\Entity\ItemGroupe;
use App\Services\Serializer;
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
    public function home(Serializer $serializer, 
                          ItemRepository $itemRepo,
                          SessionInterface $session,
                          CatalogueHydrate $catHydrate)
    {               
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
     * @Route("/admin/catalogue/produit/{id}/delete", options={"expose"=true}, name="catalogue_delete")
     */
    public function delete(Item $item, ObjectManager $manager){

        $manager->remove($item);
        $manager->flush();
    }

}
