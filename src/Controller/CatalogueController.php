<?php

namespace App\Controller;

use App\Entity\Item;
use App\Services\Utility;
use App\Services\Serializer;
use App\Services\ErrorHandler;
use App\Services\SecurityManager;
use App\Form\ItemRegistrationType;
use App\Repository\ItemRepository;
use App\Services\CatalogueHydrate;
use App\Repository\ActionRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\QuoteOrderDetailRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CatalogueController extends Controller
{
    protected $serializer;
    protected $securityUtility;
    protected $actionRepo;
    protected $ErrorHandler;
    protected $rootDir;

    public function __construct(Serializer $serializer, 
                                SecurityManager $securityUtility, 
                                ActionRepository $actionRepo,
                                ErrorHandler $ErrorHandler,
                                    $root_dir)
    {
        $this->serializer = $serializer;
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
        $this->ErrorHandler = $ErrorHandler;
        $this->rootDir = $root_dir;
    }
    
    /**
     * @Route("/admin/catalogue", name="catalogue_home")
     */
    public function home( SessionInterface $session)
    {
        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_CATALOGUE']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('catalogue/index.html.twig', [
            //'item_data_source' => $serializer->serialize([ 'object_array' => $catHydrate->hydrateItem($itemRepo->findAll()), 'format' => 'json', 'group' => 'class_property']),
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
                                    Utility $utility,
                                    ValidatorInterface $validator) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_CATALOGUE']))) {
            return $this->redirectToRoute('security_deny_access');
        }
    
        if(!$item){
            $item = new Item();
            $item->setIsErasable(true);
        }
        
        $item = $catHydrate->hydrateItem([$item])[0];
        
        $form = $this->createForm(ItemRegistrationType::class, $item);
        $form->handleRequest($request);
        
        if($form->isSubmitted() ){

            $this->ErrorHandler->registerError($validator->validate($item));
            
            if($form->isValid()){
               try{

                    $file = $request->files->get('item_registration')['PictureFile'];

                    $fileName = $utility->uploadFile($file, $utility->getAbsoluteRootPath() . '/' . $this->getParameter('abs.file.setting.catalogue.download_dir'));
                    if (!empty($fileName)) {
                        if (!empty($item->getPicture()) && file_exists($utility->getAbsoluteRootPath() . '/' . $this->getParameter('abs.file.setting.catalogue.download_dir') . '/' . $item->getPicture())) {
                            unlink($utility->getAbsoluteRootPath() . '/' . $this->getParameter('abs.file.setting.catalogue.download_dir') . '/' . $item->getPicture());
                        }
                        $item->setPicture($fileName);
                    }

                    if (empty($item->getComment()->getId()))
                        $item->getComment()->setCreateAt(new \Datetime());
                    
                    $item->setCreatedAt(new \Datetime());
                    $manager->persist($item);
                    $manager->flush();
                    $this->ErrorHandler->success("Le produit a été enregistré avec succès.");

               }catch(Exception $ex){
                    $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde du produit.");
                    $this->ErrorHandler->error($ex->getMessage());
               }

                return $this->redirectToRoute('catalogue_home');
            }
        }        
        

        return $this->render('catalogue/item_registration.html.twig', [
            'formItem' => $form->createView(),
        ]);
    }   

    /**
     * @Route("/admin/catalogue/produit/{id}/delete", options={"expose"=true}, name="catalogue_delete")
     */
    public function delete(Item $item, 
                           ObjectManager $manager,
                           QuoteOrderDetailRepository $orderDetailRepo) {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_CATALOGUE']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        try{
            if ($item->getIsErasable()) {
                if (!empty($item->getPicture()))
                    unlink($this->getParameter('file.setting.catalogue.download_dir') . '/' . $item->getPicture());

                $manager->remove($item);
                $manager->flush();
            } 
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression du produit.");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->redirectToRoute('catalogue_home');
    }

    /*-------------------------------------------------------------------------------------------------
    ---------------------------------------------[ Json/ Ajax ]---------------------------------------*/

    /**
     * @Route("/admin/catalogue/donnee", options={"expose"=true}, name="catalogue_home_data")
     */
    public function data(
        ItemRepository $itemRepo,
        CatalogueHydrate $catHydrate
    ) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_CATALOGUE']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $catHydrate->hydrateItem($itemRepo->findAll())],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

}
