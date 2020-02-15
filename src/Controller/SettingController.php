<?php

namespace App\Controller;

use App\Entity\Tax;
use App\Entity\Item;
use App\Entity\Comment;
use App\Entity\Setting;
use App\Entity\Currency;
use App\Entity\Provider;
use App\Entity\ItemBrand;
use App\Services\Utility;
use App\Entity\ItemGroupe;
use App\Entity\OrderStatus;
use App\Services\Serializer;
use App\Entity\DeliveryStatus;
use App\Form\TaxRegistrationType;
use App\Repository\TaxRepository;
use App\Services\SecurityManager;
use App\Repository\ActionRepository;
use App\Form\SettingRegistrationType;
use App\Repository\SettingRepository;
use App\Form\CurrencyRegistrationType;
use App\Form\ProviderRegistrationType;
use App\Repository\CurrencyRepository;
use App\Repository\ProviderRepository;
use App\Form\ItemBrandRegistrationType;
use App\Repository\ItemBrandRepository;
use App\Form\ItemGroupeRegistrationType;
use App\Repository\ItemGroupeRepository;
use App\Repository\QuoteOrderRepository;
use App\Form\OrderStatusRegistrationType;
use App\Repository\OrderStatusRepository;
use App\Form\DeliveryStatusRegistrationType;
use App\Repository\DeliveryStatusRepository;
use App\Services\SettingManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Hoa\Event\Test\Unit\Source;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingController extends Controller
{

    protected $securityUtility;
    protected $actionRepo;
    protected $settingManager;


    public function __construct(SecurityManager $securityUtility, 
                                ActionRepository $actionRepo,
                                SettingManager $settingManager)
    {
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
        $this->settingManager = $settingManager;
    }
    
    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Views ]--------------------------------------------------------*/

    /**
     * @Route("/admin/configuration", name="setting_home")
     */
    public function home(SettingRepository $settingRepo,
                        Serializer $serializer,
                        Utility $utility) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        $source = $utility->getSettingDataSource($settingRepo->findAll());
        //dump($source); die();
        $codes = $utility->getDistinctByCode($settingRepo->findAll());
        return $this->render('setting/index.html.twig', [
            'data_table' => 'general_table_js',
            'data_table_source' => 'general_data_source',
            'page_title' => '',
            'source' => $source,
            //'table_target' => json_encode($settings),
            'codes' => $codes,
            'create_url' => $this->generateUrl('setting_registration'),
            'page' => 'setting/_partials/general.html',
        ]);
    }

    /**
     * @Route("/admin/configuration/monnaie", options={"expose"=true}, name="setting_currency")
     */
    public function currency(CurrencyRepository $currencyRepo,
                             Serializer $serializer) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $source = $serializer->serialize([
            'object_array' => $currencyRepo->findAll(),
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return $this->render('setting/index.html.twig', [
            'data_table' => 'currency_table_js',
            'data_table_source' => 'currency_data_source',
            'page_title' => 'Monnaie',
            'source' => $source,
            'codes' => ["CURRENCY"],
            'create_url' => $this->generateUrl('setting_currency_registration'),
            'page' => 'setting/_partials/index.html',
        ]);
    }

    /**
     * @Route("/admin/configuration/taxe", options={"expose"=true}, name="setting_tax")
     */
    public function tax(TaxRepository $taxRepo,
                        Serializer $serializer) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $source = $serializer->serialize([
            'object_array' => $taxRepo->findAll(),
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return $this->render('setting/index.html.twig', [
            'data_table' => 'tax_table_js',
            'data_table_source' => 'tax_data_source',
            'page_title' => 'Tax',
            'codes' => ["TAX"],
            'source' => $source,
            'create_url' => $this->generateUrl('setting_tax_registration'),
            'page' => 'setting/_partials/index.html',
        ]);
    }

    /**
     * @Route("/admin/configuration/facturation/statut", options={"expose"=true}, name="setting_delivery_status")
     */
    public function deliveryStatus(DeliveryStatusRepository $delStatusRepo,
                                   Serializer $serializer) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $source = $serializer->serialize([
            'object_array' => $delStatusRepo->findAll(),
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return $this->render('setting/index.html.twig', [
            'data_table' => 'delivery_status_table_js',
            'data_table_source' => 'delivery_status_data_source',
            'page_title' => 'Statut Facturation',
            'source' => $source,
            'codes' => ["STATUS"],
            'create_url' => $this->generateUrl('setting_delivery_status_registration'),
            'page' => 'setting/_partials/index.html',
        ]);
    }

    /**
     * @Route("/admin/configuration/commande/statut", options={"expose"=true}, name="setting_order_status")
     */
    public function orderStatus(OrderStatusRepository $orderStatusRepo,
                                Serializer $serializer) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $source =  $serializer->serialize([
            'object_array' => $orderStatusRepo->findAll(),
            'format' => 'json',
            'group' => 'class_property'
        ]);

        //dump($orderStatusRepo->findAll());die();
        return $this->render('setting/index.html.twig', [
            'data_table' => 'status_table_js',
            'data_table_source' => 'order_status_data_source',
            'page_title' => 'Statut Commande',
            'source' => $source,
            'codes' => ["STATUS"],
            'create_url' => $this->generateUrl('setting_order_status_registration'),
            'page' => 'setting/_partials/index.html',
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/marque", options={"expose"=true}, name="setting_catalogue_brand")
     */
    public function catalogueBrand(ItemBrandRepository $brandRepo,
                                   Serializer $serializer) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $source =  $serializer->serialize([
            'object_array' => $brandRepo->findAll(),
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return $this->render('setting/index.html.twig', [
            'data_table' => 'brand_table_js',
            'data_table_source' => 'brand_data_source',
            'page_title' => 'Marque Produit',
            'source' => $source,
            'codes' => ["BRAND"],
            'create_url' => $this->generateUrl('setting_brand_registration'),
            'page' => 'setting/_partials/index.html',
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/famille", options={"expose"=true}, name="setting_catalogue_group")
     */
    public function catalogueGroup(ItemGroupeRepository $groupRepo,
                                   Serializer $serializer) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $source =  $serializer->serialize([
            'object_array' => $groupRepo->findAll(),
            'format' => 'json',
            'group' => 'class_property'
        ]);

        return $this->render('setting/index.html.twig', [
            'data_table' => 'group_table_js',
            'data_table_source' => 'group_data_source',
            'page_title' => 'Famille Produit',
            'source' => $source,
            'codes' => ["GROUP"],
            'create_url' => $this->generateUrl('setting_group_registration'),
            'page' => 'setting/_partials/index.html',
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/fournisseur", options={"expose"=true}, name="setting_catalogue_provider")
     */
    public function catalogueProvider(ProviderRepository $providerRepo,
                                   Serializer $serializer) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $source =  $serializer->serialize([
            'object_array' => $providerRepo->findAll(),
            'format' => 'json',
            'group' => 'class_property'
        ]);

        //dump($source); die();

        return $this->render('setting/index.html.twig', [
            'data_table' => 'provider_table_js',
            'data_table_source' => 'provider_data_source',
            'page_title' => 'Fournisseur Produit',
            'source' => $source,
            'codes' => ["PROVIDER"],
            'create_url' => $this->generateUrl('setting_provider_registration'),
            'page' => 'setting/_partials/index.html',
        ]);
    }

    /**
     * @Route("/admin/configuration/email", options={"expose"=true}, name="setting_email")
     * @Route("/admin/configuration/email/erreur/{message}/{statut}", options={"expose"=true}, name="setting_email_report")
     */
    public function showEmail(string $message = null, $statut = null){
        
        return $this->render('email/index.html.twig', [
            'quote' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'devis.txt'),
            'bill' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'facture.txt'),
            'first_reminder' => file_get_contents($this->getParameter('file.setting.email').'/'. 'relance_paiement_1.txt'),
            'seconde_reminder' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'relance_paiement_2.txt'),
            'validate' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'validation_commande.txt'),
            'inscription' => file_get_contents($this->getParameter('file.setting.email') . '/' . 'inscription.txt'),
            'message' => $message,
            'status' => $statut
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/import", options={"expose"=true}, name="setting_catalogue_import")
     * @Route("/admin/configuration/produit/import/erreur/{message}/{statut}", options={"expose"=true}, name="setting_catalogue_import_report")
     */
    public function import(string $message = null, $statut = null){
        
        return $this->render('setting/import_registration.html.twig', [
            'message' => $message,
            'status' => $statut
        ]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Registrations ]--------------------------------------------------------*/

    /**
     * @Route("/admin/configuration/inscription", options={"expose"=true}, name="setting_registration")
     * @Route("/admin/configuration/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_edit")
     * 
     */
    public function registration(Setting $setting = null, 
                                 Request $request, 
                                 ObjectManager $manager,
                                 Utility $utility) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!$setting)
            $setting = new Setting();
        
        $form = $this->createForm(SettingRegistrationType::class, $setting);

        $form->handleRequest($request);

        //dump($request);die();
        if($form->isSubmitted() && $form->isValid() ){

            //dump($request);die();
            
            if(empty($request->files->get('setting')['switch'])){
                $file = $request->files->get('setting')['file'];
                $fileName = $utility->uploadFile($file, $this->getParameter('file.setting.image.download_dir'));
                if (!empty($fileName)) {
                    if ( $setting->getIsFile() && !empty($setting->getValue())) {
                        unlink($this->getParameter('file.setting.image.download_dir').'/'. $setting->getValue());
                    }
                    $setting->setValue($fileName);
                    $setting->setIsFile(true);
                }
            }
            else{
                $setting->setIsFile(false);
            }            
            
            $manager->persist($setting);
            $manager->flush();

            return $this->redirectToRoute('setting_home');
        }        

        return $this->render('setting/registration.html.twig', [
            'formSetting' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/currency/inscription", options={"expose"=true}, name="setting_currency_registration")
     * @Route("/admin/configuration/currency/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_currency_edit")
     * 
     */
    public function currencyRegistration(Currency $currency = null, 
                                         Request $request, 
                                         ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!$currency)
            $currency = new Currency();
        
        $form = $this->createForm(CurrencyRegistrationType::class, $currency);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $isDefault = $request->request->get('currency_registration[IsDefault]');
            if(!isset($isDefault)){
                $currency->setIsDefault(false);
            }
            $currency->setCreatedAt(new \DateTime());
            $manager->persist($currency);
            $manager->flush();

            return $this->redirectToRoute('setting_currency');
        }        

        return $this->render('setting/currency_registration.html.twig', [
            'formCurrency' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/statut/livraison/inscription", options={"expose"=true}, name="setting_delivery_status_registration")
     * @Route("/admin/configuration/statut/livraison/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_delivery_status_edit")
     * 
     */
    public function deliveryStatusRegistration(DeliveryStatus $status = null, 
                                            Request $request, 
                                            ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$status)
            $status = new DeliveryStatus();

        $form = $this->createForm(DeliveryStatusRegistrationType::class, $status);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($status);
            $manager->flush();

            return $this->redirectToRoute('setting_delivery_status');
        }

        return $this->render('setting/delivery_status_registration.html.twig', [
            'formStatus' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/tax/inscription", options={"expose"=true}, name="setting_tax_registration")
     * @Route("/admin/configuration/tax/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_tax_edit")
     * 
     */
    public function taxRegistration(Tax $tax = null, 
                                    Request $request, 
                                    ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!$tax)
            $tax = new Tax();
        
        $comment = $tax->getComment();
        if($comment)
            $tax->getCommentContent($comment->getContent());

        $form = $this->createForm(TaxRegistrationType::class, $tax);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            if(!empty($request->request->get('tax_registration[CommentContent]'))){

                if(!$comment)
                    $comment = new Comment();
                $comment->setContent($request->request->get('tax_registration[CommentContent]'));
                $comment->setCreateAt(new \DateTime());
    
                $tax->setComment($comment);
                $manager->persist($comment);
            }

            if(empty($request->request->get('tax_registration[IsCurrent]')))
                $tax->setIsCurrent(false);

            if(empty($request->request->get('tax_registration[IsTVAMarge]')))
                $tax->setIsTVAMarge(false);

            $tax->setCreateAt(new \DateTime());
            $manager->persist($tax);
            $manager->flush();

            return $this->redirectToRoute('setting_tax');
        }        

        return $this->render('setting/tax_registration.html.twig', [
            'formTax' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/marque/inscription", options={"expose"=true}, name="setting_brand_registration")
     * @Route("/admin/configuration/marque/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_brand_edit")
     * 
     */
    public function brandRegistration(ItemBrand $itemBrand = null, 
                                      Request $request, 
                                      ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$itemBrand)
            $itemBrand = new ItemBrand();

        $form = $this->createForm(ItemBrandRegistrationType::class, $itemBrand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $itemBrand->setCreatedAt(new \DateTime());
            $itemBrand->setIsEnabled(true);

            $manager->persist($itemBrand);
            $manager->flush();

            return $this->redirectToRoute('setting_catalogue_brand');
        }

        return $this->render('setting/brand_registration.html.twig', [
            'formBrand' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/famille/inscription", options={"expose"=true}, name="setting_group_registration")
     * @Route("/admin/configuration/famille/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_group_edit")
     * 
     */
    public function groupRegistration(ItemGroupe $itemGroupe = null, 
                                      Request $request, 
                                      ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$itemGroupe)
            $itemGroupe = new ItemGroupe();

        $form = $this->createForm(ItemGroupeRegistrationType::class, $itemGroupe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $itemGroupe->setIsEnabled(true);

            $manager->persist($itemGroupe);
            $manager->flush();

            return $this->redirectToRoute('setting_catalogue_group');
        }

        return $this->render('setting/group_registration.html.twig', [
            'formGroup' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/fournisseur/inscription", options={"expose"=true}, name="setting_provider_registration")
     * @Route("/admin/configuration/fournisseur/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_provider_edit")
     * 
     */
    public function providerRegistration(Provider $provider = null, 
                                         Request $request, 
                                         ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$provider)
            $provider = new Provider();

        $form = $this->createForm(ProviderRegistrationType::class, $provider);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $provider->setIsEnabled(true);

            $manager->persist($provider);
            $manager->flush();

            return $this->redirectToRoute('setting_catalogue_provider');
        }

        return $this->render('setting/provider_registration.html.twig', [
            'formProvider' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/commande/statut/inscription", options={"expose"=true}, name="setting_order_status_registration")
     * @Route("/admin/configuration/commande/statut/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_order_status_edit")
     * 
     */
    public function orderStatusRegistration(OrderStatus $status = null, 
                                            Request $request, 
                                            ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$status)
            $status = new OrderStatus();

        $form = $this->createForm(OrderStatusRegistrationType::class, $status);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($status);
            $manager->flush();

            return $this->redirectToRoute('setting_order_status');
        }

        return $this->render('setting/status_registration.html.twig', [
            'formStatus' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/import/sauvegarde", options={"expose"=true}, name="setting_catalogue_import_registration")
     */
    public function importRegistration(
        Utility $utility,
        Request $request,
        ObjectManager $manager
    ) {

        $error = [
            'statut' => 0,
            'message' => ''
        ];

        $form = $request->files->get('catalogue_upload');
        if(!empty($form['item']))
            $error = $this->settingManager->importCatalogueItem($form['item'], $this->getParameter('file.setting.catalogue.download_dir'));
        
        if (!empty($form['provider']) && $error['statut'] == 0)
            $error = $this->settingManager->importCatalogueProvider($form['provider'], $this->getParameter('file.setting.catalogue.download_dir'));

        if (!empty($form['categorie']) && $error['statut'] == 0)
            $error = $this->settingManager->importCatalogueCategorie($form['categorie'], $this->getParameter('file.setting.catalogue.download_dir'));

        if (!empty($form['brand']) && $error['statut'] == 0)
            $error = $this->settingManager->importCatalogueBrand($form['brand'], $this->getParameter('file.setting.catalogue.download_dir'));

        return $this->redirectToRoute('setting_catalogue_import_report', [
            'message' => ($error['statut'] == 0) ?  'Vos éléments ont été importés avec succès!' : $error['message'],
            'statut' => ($error['statut'] == 0) ? 200 : $error['statut']
        ]);
    }

    /**
     * @Route("/admin/configuration/email/edit", options={"expose"=true}, name="setting_email_edit") 
     */
    public function emailRegistration(Request $request) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $error = [
            'statut' => 0,
            'message' => ''
        ];

        $form = $request->request->get('emails');

        if(!empty($form)){
            file_put_contents($this->getParameter('file.setting.email') . '/' . 'devis.txt',trim($form['quote'], ' \t'));
            file_put_contents($this->getParameter('file.setting.email') . '/' . 'facture.txt', trim($form['bill'], ' \t'));
            file_put_contents($this->getParameter('file.setting.email') . '/' . 'relance_paiement_1.txt', trim($form['first_reminder'], ' \t'));
            file_put_contents($this->getParameter('file.setting.email') . '/' . 'relance_paiement_2.txt', trim($form['seconde_reminder'], ' \t'));
            file_put_contents($this->getParameter('file.setting.email') . '/' . 'validation_commande.txt', trim($form['validate'], ' \t'));
            file_put_contents($this->getParameter('file.setting.email') . '/' . 'inscription.txt', trim($form['inscription'], ' \t'));
        }
        else{
            $error =  [
                'message' => 'Une erreur s\'est produite lors de l\'enregistrement de vos modèles d\'email!',
                'statut' => 500
            ];
        }
        
        return $this->redirectToRoute('setting_email_report', [
            'message' => ($error['statut'] == 0) ?  'Vos modèles d\'email ont été sauvegardés avec succès!' : $error['message'],
            'statut' => ($error['statut'] == 0) ? 200 : $error['statut']
        ]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Deletes ]--------------------------------------------------------*/

    /**
     * @Route("/admin/configuration/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_delete")
     */
    public function delete(Setting $setting, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($setting);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/currency/{id}/delete", options={"expose"=true}, name="setting_currency_delete", requirements={"id"="\d+"})
     */
    public function currencyDelete(Currency $currency, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($currency);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/tax/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_tax_delete")
     */
    public function taxDelete(Tax $tax, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($tax);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/statut/livraison/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_delivery_status_delete")
     */
    public function deliveryStatusDelete(DeliveryStatus $status, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($status);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/fournisseur/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_provider_delete")
     */
    public function providerDelete(Provider $provider, ObjectManager $manager)
    {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($provider);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/famille/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_group_delete")
     */
    public function groupDelete(ItemGroupe $group, ObjectManager $manager)
    {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($group);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/marque/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_brand_delete")
     */
    public function brandDelete(ItemBrand $brand, ObjectManager $manager)
    {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($brand);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/commande/statut/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_order_status_delete")
     */
    public function orderStatusDelete(OrderStatus $status, QuoteOrderRepository $orderRepo, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        if (count($orderRepo->findBy(['Status' => $status])) == 0) {
            $manager->remove($status);
            $manager->flush();
            return $this->RedirectToRoute('order_home');
        }
        return $this->RedirectToRoute('order_home', ['message' => 'Le status ne peut pas être supprimé. Il est en cours d\'utilisation pour au moins une commande!']);
    }

}
