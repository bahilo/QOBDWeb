<?php

namespace App\Controller;

use Exception;
use App\Entity\Tax;
use App\Entity\Item;
use App\Entity\Site;
use App\Entity\Comment;
use App\Entity\EanCode;
use App\Entity\Setting;
use App\Entity\Currency;
use App\Entity\Provider;
use App\Events\MyEvents;
use App\Entity\ItemBrand;
use App\Services\Utility;
use App\Entity\ItemGroupe;
use App\Entity\OrderStatus;
use App\Services\Serializer;
use App\Entity\DeliveryStatus;
use App\Services\ErrorHandler;
use App\Services\SearchToView;
use Hoa\Event\Test\Unit\Source;
use App\Services\SettingManager;
use App\Form\TaxRegistrationType;
use App\Repository\TaxRepository;
use App\Services\SecurityManager;
use App\Form\SiteRegistrationType;
use App\Repository\SiteRepository;
use App\Repository\ActionRepository;
use App\Form\EanCodeRegistrationType;
use App\Form\SettingRegistrationType;
use App\Repository\EanCodeRepository;
use App\Repository\SettingRepository;
use App\Form\CurrencyRegistrationType;
use App\Form\ProviderRegistrationType;
use App\Repository\CurrencyRepository;
use App\Repository\ImeiCodeRepository;
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
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SettingController extends Controller
{

    protected $securityUtility;
    protected $actionRepo;
    protected $settingManager;
    protected $validator;
    protected $serializer;
    protected $ErrorHandler;
    protected $settingRepo;
    protected $eventDispatcher;
    protected $utility;
    protected $search;


    public function __construct(Serializer $serializer, 
                                SecurityManager $securityUtility, 
                                ActionRepository $actionRepo,
                                SettingManager $settingManager,
                                ValidatorInterface $validator,
                                SettingRepository $settingRepo,
                                Utility $utility,
                                EventDispatcherInterface $eventDispatcher, 
                                ErrorHandler $ErrorHandler,
                                SearchToView $search)
    {
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
        $this->settingManager = $settingManager;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->ErrorHandler = $ErrorHandler;
        $this->settingRepo = $settingRepo;
        $this->eventDispatcher = $eventDispatcher;
        $this->utility = $utility;
        $this->search = $search;
    }

#region [ Views ]
    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Views ]--------------------------------------------------------*/

    /**
     * @Route("/admin/configuration", name="setting_home")
     */
    public function home(Utility $utility, SiteRepository $siteRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/general.html.twig', [
            'source_site' => $siteRepo->findAll(),
            'data_table_source' => 'general_data_source',
            'source' => $utility->getDistinctByCode($this->settingRepo->findAll()),
            'create_url' => $this->generateUrl('setting_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/admin", name="setting_home_admin")
     */
    public function homeAdmin(Utility $utility)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/general_admin.html.twig', [
            'data_table' => 'general_table_js',
            'data_table_source' => 'general_data_source',
            'codes' => $utility->getAdminDistinctByCode($this->settingRepo->findAll()),
            'create_url' => $this->generateUrl('setting_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/monnaie", options={"expose"=true}, name="setting_currency")
     */
    public function currency(CurrencyRepository $currencyRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/currency.html.twig', [
            'data_table' => 'currency_table_js',
            'data_table_source' => 'currency_data_source',
            'codes' => ["CURRENCY"],
            'create_url' => $this->generateUrl('setting_currency_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/taxe", options={"expose"=true}, name="setting_tax")
     */
    public function tax(TaxRepository $taxRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        //dump($this->settingManager->getSettingDataSource($taxRepo->findAll()));die();
        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/tax.html.twig', [
            'data_table' => 'tax_table_js',
            'data_table_source' => 'tax_data_source',
            'codes' => ["TAX"],
            'create_url' => $this->generateUrl('setting_tax_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/facturation/statut", options={"expose"=true}, name="setting_delivery_status")
     */
    public function deliveryStatus(DeliveryStatusRepository $delStatusRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/delivery_status.html.twig', [
            'data_table' => 'delivery_status_table_js',
            'data_table_source' => 'delivery_status_data_source',
            'codes' => ["STATUS"],
            'create_url' => $this->generateUrl('setting_delivery_status_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/commande/statut", options={"expose"=true}, name="setting_order_status")
     */
    public function orderStatus(OrderStatusRepository $orderStatusRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        //dump($orderStatusRepo->findAll());die();
        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/order_status.html.twig', [
            'data_table' => 'status_table_js',
            'data_table_source' => 'order_status_data_source',
            'codes' => ["STATUS"],
            'create_url' => $this->generateUrl('setting_order_status_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/marque", options={"expose"=true}, name="setting_catalogue_brand")
     */
    public function catalogueBrand(ItemBrandRepository $brandRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/catalogue_brand.html.twig', [
            'data_table' => 'brand_table_js',
            'data_table_source' => 'brand_data_source',
            'codes' => ["BRAND"],
            'create_url' => $this->generateUrl('setting_brand_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/famille", options={"expose"=true}, name="setting_catalogue_group")
     */
    public function catalogueGroup(ItemGroupeRepository $groupRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/catalogue_group.html.twig', [
            'data_table' => 'group_table_js',
            'data_table_source' => 'group_data_source',
            'codes' => ["GROUP"],
            'create_url' => $this->generateUrl('setting_group_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/fournisseur", options={"expose"=true}, name="setting_catalogue_provider")
     */
    public function catalogueProvider(ProviderRepository $providerRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/catalogue_provider.html.twig', [
            'data_table' => 'provider_table_js',
            'data_table_source' => 'provider_data_source',
            'codes' => ["PROVIDER"],
            'create_url' => $this->generateUrl('setting_provider_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/ean", options={"expose"=true}, name="setting_catalogue_ean")
     */
    public function catalogueEan(EanCodeRepository $eanRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/catalogue_ean.html.twig', [
            'data_table' => 'ean_table_js',
            'data_table_source' => 'ean_data_source',
            'codes' => ["EAN"],
            'create_url' => $this->generateUrl('setting_ean_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/sites", options={"expose"=true}, name="setting_sites")
     */
    public function sites(EanCodeRepository $eanRepo) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/home/site.html.twig', [
            'data_table' => 'ean_table_js',
            'data_table_source' => 'site_data_source',
            'codes' => ["SITE"],
            'create_url' => $this->generateUrl('setting_sites_registration'),
        ]);
    }

    /**
     * @Route("/admin/configuration/email", options={"expose"=true}, name="setting_email")
     */
    public function showEmail(){
        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . 'email/index.html.twig', [
            'quote' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'devis.txt'),
            'bill' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'facture.txt'),
            'first_reminder' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email').'/'. 'relance_paiement_1.txt'),
            'seconde_reminder' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'relance_paiement_2.txt'),
            'validate' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'validation_commande.txt'),
            'inscription' => file_get_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'inscription.txt'),
        ]);
    }

    /**
     * @Route("/admin/configuration/texte", options={"expose"=true}, name="setting_text")
     */
    public function showText(Utility $utility){
        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . 'document/index.html.twig', [
            'cgv' => file_get_contents($utility->getAbsoluteRootPath() . $this->getParameter('file.setting.text') . '/' . 'cgv.txt'),
            'quote' => file_get_contents($utility->getAbsoluteRootPath() . $this->getParameter('file.setting.text') . '/' . 'quote.txt'),
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/import", options={"expose"=true}, name="setting_catalogue_import")
     */
    public function import(){
        
        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/import_registration.html.twig');
    }

#endregion

#region [ Registrations ]
    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Registrations ]--------------------------------------------------------*/

    /**
     * @Route("/admin/configuration/inscription", options={"expose"=true}, name="setting_registration")
     */
    public function registration(Setting $setting = null, 
                                 Request $request, 
                                 ObjectManager $manager,
                                 Utility $utility) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!$setting)
            $setting = new Setting();
        
        $form = $this->createForm(SettingRegistrationType::class, $setting);

        try{
            $form->handleRequest($request);
            $errors = $this->validator->validate($setting);

            //dump($request);die();
            if ($form->isSubmitted() && $form->isValid()) {

                if (isset($request->request->get('setting_registration')['switch'])) {
                    $file = $request->files->get('setting_registration')['file'];
                    $fileName = $utility->uploadFile($file, $utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir'), 'logo.png');

                    if (!empty($fileName)) {
                        if (file_exists($utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir') . '/' . $setting->getValue())) {
                            unlink($utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir') . '/' . $setting->getValue());
                        }
                        $setting->setValue($fileName);
                        $setting->setIsFile(true);
                    }

                    /*$fileFullPath = $utility->getAbsoluteRootPath() . $this->getParameter('file.setting.image.download_dir') . '/' . $fileName;
                    $event = new GenericEvent([
                        "files" => [$fileFullPath],
                    ]);
                    $this->eventDispatcher->dispatch(MyEvents::FTP_IMAGE_SEND, $event);*/
                } else {
                    $setting->setIsFile(false);
                }

                $manager->persist($setting);
                $manager->flush();
                $this->ErrorHandler->success("La configuration a été sauvegardée avec succès!");
                return $this->redirectToRoute('setting_home');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de la configuration!");
            $this->ErrorHandler->error($ex->getMessage());
        }       

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/registration.html.twig', [
            'formSetting' => $form->createView(),
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/admin/configuration/edit", options={"expose"=true}, name="setting_edit")
     * 
     */
    public function edit(
        Setting $setting = null,
        Request $request,
        ObjectManager $manager,
        SiteRepository $siteRepo,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        try {
            $form = $request->request->get('settings');
            //dump($form);
            //dump($request->files->get('settings'));
            //die();

            if(!empty($form['site'])){
                $site = $siteRepo->find($form['site']);
                if(!empty($site)){
                    $oldActiveSite = $siteRepo->findOneBy(['Active' => true]);
                    if(!empty($oldActiveSite)){
                        $oldActiveSite->setActive(false);
                        $manager->persist($oldActiveSite);
                    }
                    $site->setActive(true);
                    $manager->persist($site);                    
                }
            }
           
            foreach ($form['values'] as $code => $settings) {
                foreach ($settings as $name => $value) {
                    $setting = $this->settingRepo->findOneBy(["Code" => $code, "Name" => $name]);
                    if (!empty($setting)) {
                        //dump($setting);
                        if ($setting->getIsFile()) {
                            $file = $request->files->get('settings')[$name];
                            $fileName = $utility->uploadFile($file, $utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir'), 'logo.png');
                            //dump($fileName);die();
                            if (!empty($fileName)) {
                                if (file_exists($utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir') . '/' . $setting->getValue())) {
                                    unlink($utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir') . '/' . $setting->getValue());
                                }
                                $setting->setValue($fileName);
                            }
                        } else {
                            $setting->setValue($value);
                        }
                        $manager->persist($setting);
                    }
                }
            }

            // sauvegarde du logo de la société 
            $setting = $this->settingRepo->findOneBy(["Code" => "SOCIETE", "Name" => "SOCIETE_LOGO"]);
            $file = $request->files->get('settings')['values']['SOCIETE']['SOCIETE_LOGO'];
            if (!empty($setting) && $setting->getIsFile() && !empty($file)) {

                // suppression de l'image précedemment sauvegardé
                if (file_exists($utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir') . '/' . $setting->getValue())) {
                    unlink($utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir') . '/' . $setting->getValue());
                }

                $fileName = $utility->uploadFile($file, $utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir'), 'logo.png');
               
                if (!empty($fileName))                 
                    $setting->setValue($fileName);
            }
            
            $manager->flush();
            $this->ErrorHandler->success("La configuration a été sauvegardée avec succès!");            
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de la configuration!");
            $this->ErrorHandler->error($ex->getMessage()); 
        }

        return $this->redirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/admin/edit/{id}", options={"expose"=true}, name="setting_admin_edit")
     * 
     */
    public function editAdmin(
        Setting $setting,
        Request $request,
        ObjectManager $manager,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$setting)
            $setting = new Setting();

        $form = $this->createForm(SettingRegistrationType::class, $setting);

        try {
            $form->handleRequest($request);
            $errors = $this->validator->validate($setting);

            //dump($request);die();
            if ($form->isSubmitted() && $form->isValid()) {

                if (isset($request->request->get('setting_registration')['switch'])) {
                    $file = $request->files->get('setting_registration')['file'];
                    $fileName = $utility->uploadFile($file, $utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir'), 'logo.png');

                    if (!empty($fileName)) {
                        if (file_exists($utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir') . '/' . $setting->getValue())) {
                            unlink($utility->getAbsoluteRootPath() . $this->getParameter('abs.file.setting.image.download_dir') . '/' . $setting->getValue());
                        }
                        $setting->setValue($fileName);
                        $setting->setIsFile(true);
                    }

                    /*$fileFullPath = $utility->getAbsoluteRootPath() . $this->getParameter('file.setting.image.download_dir') . '/' . $fileName;
                    $event = new GenericEvent([
                        "files" => [$fileFullPath],
                    ]);
                    $this->eventDispatcher->dispatch(MyEvents::FTP_IMAGE_SEND, $event);*/
                } else {
                    $setting->setIsFile(false);
                }

                $manager->persist($setting);
                $manager->flush();
                $this->ErrorHandler->success("La configuration a été sauvegardée avec succès!");
                return $this->redirectToRoute('setting_home_admin');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de la configuration!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/registration.html.twig', [
            'formSetting' => $form->createView(),
            'errors' => $errors
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

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!$currency)
            $currency = new Currency();
        
        $form = $this->createForm(CurrencyRegistrationType::class, $currency);

        try{
            $form->handleRequest($request);
            $errors = $this->validator->validate($currency);

            if ($form->isSubmitted() && $form->isValid()) {
                $isDefault = $request->request->get('currency_registration[IsDefault]');
                if (!isset($isDefault)) {
                    $currency->setIsDefault(false);
                }
                $currency->setCreatedAt(new \DateTime());
                $manager->persist($currency);
                $manager->flush();
                $this->ErrorHandler->success("La monnaie a été sauvegardée avec succès!");
                return $this->redirectToRoute('setting_currency');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de la monnaie!");
            $this->ErrorHandler->error($ex->getMessage());
        }        

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/currency_registration.html.twig', [
            'formCurrency' => $form->createView(),
            'errors' => $errors
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

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$status)
            $status = new DeliveryStatus();

        $form = $this->createForm(DeliveryStatusRegistrationType::class, $status);

        try{
            $form->handleRequest($request);
            $errors = $this->validator->validate($status);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($status);
                $manager->flush();
                $this->ErrorHandler->success("Le satut a été sauvegardé avec succès!");
                return $this->redirectToRoute('setting_delivery_status');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde du statut!");
            $this->ErrorHandler->error($ex->getMessage());
        } 

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/delivery_status_registration.html.twig', [
            'formStatus' => $form->createView(),
            'errors' => $errors
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

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!$tax)
            $tax = new Tax();
        
        
        $comment = $tax->getComment();
        if($comment)
            $tax->getCommentContent($comment->getContent());

        $form = $this->createForm(TaxRegistrationType::class, $tax);

        try{
            $form->handleRequest($request);
            $errors = $this->validator->validate($tax);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($request->request->get('tax_registration[CommentContent]'))) {

                    if (!$comment)
                        $comment = new Comment();
                    $comment->setContent($request->request->get('tax_registration[CommentContent]'));
                    $comment->setCreateAt(new \DateTime());

                    $tax->setComment($comment);
                    $manager->persist($comment);
                }

                $tax->setCreateAt(new \DateTime());
                $manager->persist($tax);
                $manager->flush();
                $this->ErrorHandler->success("La taxe a été sauvegardée avec succès!");
                return $this->redirectToRoute('setting_tax');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de la taxe!");
            $this->ErrorHandler->error($ex->getMessage());
        }        

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/tax_registration.html.twig', [
            'formTax' => $form->createView(),
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/admin/configuration/site/inscription", options={"expose"=true}, name="setting_sites_registration")
     * @Route("/admin/configuration/site/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_sites_edit")
     * 
     */
    public function siteRegistration(
        Site $site = null,
        Request $request,
        ObjectManager $manager
    ) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$site)
            $site = new Site();

        $form = $this->createForm(SiteRegistrationType::class, $site);

        try {
            $form->handleRequest($request);
            $errors = $this->validator->validate($site);
            if ($form->isSubmitted() && $form->isValid()) {
                
                $manager->persist($site);
                $manager->flush();
                $this->ErrorHandler->success("Le site a été sauvegardé avec succès!");
                return $this->redirectToRoute('setting_sites');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde du site!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/site_registration.html.twig', [
            'formSite' => $form->createView(),
            'errors' => $errors
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

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$itemBrand)
            $itemBrand = new ItemBrand();

        $form = $this->createForm(ItemBrandRegistrationType::class, $itemBrand);

        try{
            $form->handleRequest($request);
            $errors = $this->validator->validate($itemBrand);

            if ($form->isSubmitted() && $form->isValid()) {

                $itemBrand->setCreatedAt(new \DateTime());
                $itemBrand->setIsEnabled(true);

                $manager->persist($itemBrand);
                $manager->flush();
                $this->ErrorHandler->success("La marque a été sauvegardée avec succès!");
                return $this->redirectToRoute('setting_catalogue_brand');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de la marque!");
            $this->ErrorHandler->error($ex->getMessage());
        }  

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/brand_registration.html.twig', [
            'formBrand' => $form->createView(),
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/admin/configuration/famille/inscription", options={"expose"=true}, name="setting_group_registration")
     * @Route("/admin/configuration/famille/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_group_edit")
     * 
     */
    public function groupRegistration(ItemGroupe $itemGroupe = null, 
                                      Request $request, 
                                      ObjectManager $manager,
                                      ValidatorInterface $validator) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$itemGroupe)
            $itemGroupe = new ItemGroupe();

        $form = $this->createForm(ItemGroupeRegistrationType::class, $itemGroupe);

        try{
            $form->handleRequest($request);
            $this->ErrorHandler->registerError($validator->validate($itemGroupe));

            if ($form->isSubmitted() && $form->isValid()) {

                $itemGroupe->setIsEnabled(true);

                $manager->persist($itemGroupe);
                $manager->flush();
                $this->ErrorHandler->success("La famille a été sauvegardée avec succès!");
                return $this->redirectToRoute('setting_catalogue_group');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de la famille!");
            $this->ErrorHandler->error($ex->getMessage());
        } 

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/group_registration.html.twig', [
            'formGroup' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/configuration/fournisseur/inscription", options={"expose"=true}, name="setting_provider_registration")
     * @Route("/admin/configuration/fournisseur/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_provider_edit")
     * 
     */
    public function providerRegistration(Provider $provider = null, 
                                         Request $request, 
                                         ObjectManager $manager,
                                         ValidatorInterface $validator) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$provider)
            $provider = new Provider();

        $form = $this->createForm(ProviderRegistrationType::class, $provider);

        try{
            $form->handleRequest($request);
            $this->ErrorHandler->registerError($validator->validate($provider));

            if ($form->isSubmitted() && $form->isValid()) {

                $provider->setIsEnabled(true);

                $manager->persist($provider);
                $manager->flush();
                $this->ErrorHandler->success("Le fournisseur a été sauvegardé avec succès!");
                return $this->redirectToRoute('setting_catalogue_provider');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde du fournisseur!");
            $this->ErrorHandler->error($ex->getMessage());
        } 

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/provider_registration.html.twig', [
            'formProvider' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/configuration/ean/inscription", options={"expose"=true}, name="setting_ean_registration")
     * @Route("/admin/configuration/ean/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_ean_edit")
     * 
     */
    public function eanRegistration(EanCode $ean = null, 
                                         Request $request, 
                                         ObjectManager $manager,
                                         ValidatorInterface $validator) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$ean)
            $ean = new EanCode();

        $form = $this->createForm(EanCodeRegistrationType::class, $ean);

       try{
            $form->handleRequest($request);
            $this->ErrorHandler->registerError($validator->validate($ean));

            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($ean);
                $manager->flush();
                $this->ErrorHandler->success("L'EAN a été sauvegardé avec succès!");
                return $this->redirectToRoute('setting_catalogue_ean');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde de l'EAN!");
            $this->ErrorHandler->error($ex->getMessage());
        } 

        return $this->render('site/' . $this->search->get_site_config()->getCode() . 'catalogue/ean_registration.html.twig', [
            'formEAN' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/configuration/commande/statut/inscription", options={"expose"=true}, name="setting_order_status_registration")
     * @Route("/admin/configuration/commande/statut/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_order_status_edit")
     * 
     */
    public function orderStatusRegistration(OrderStatus $status = null, 
                                            Request $request, 
                                            ObjectManager $manager,
                                            ValidatorInterface $validator) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$status)
            $status = new OrderStatus();

        $form = $this->createForm(OrderStatusRegistrationType::class, $status);

        try{
            $form->handleRequest($request);
            $this->ErrorHandler->registerError($validator->validate($status));

            if ($form->isSubmitted() && $form->isValid()) {

                $manager->persist($status);
                $manager->flush();
                $this->ErrorHandler->success("Le statut a été sauvegardé avec succès!");
                return $this->redirectToRoute('setting_order_status');
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la sauvegarde du statut!");
            $this->ErrorHandler->error($ex->getMessage());
        } 

        return $this->render('site/' . $this->search->get_site_config()->getCode() . '/setting/status_registration.html.twig', [
            'formStatus' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/import/sauvegarde", options={"expose"=true}, name="setting_catalogue_import_registration")
     */
    public function importRegistration(
        Request $request,
        ObjectManager $manager
    ) {

        $error = [
            'statut' => 0,
            'message' => ''
        ];

        try{
            $form = $request->files->get('catalogue_upload');
            if (!empty($form['item']))
            $error = $this->settingManager->importCatalogueItem($form['item'], $this->getParameter('file.setting.catalogue.download_dir'));

            if (!empty($form['provider']) && $error['statut'] == 0)
            $error = $this->settingManager->importCatalogueProvider($form['provider'], $this->getParameter('file.setting.catalogue.download_dir'));

            if (!empty($form['categorie']) && $error['statut'] == 0)
            $error = $this->settingManager->importCatalogueCategorie($form['categorie'], $this->getParameter('file.setting.catalogue.download_dir'));

            if (!empty($form['brand']) && $error['statut'] == 0)
            $error = $this->settingManager->importCatalogueBrand($form['brand'], $this->getParameter('file.setting.catalogue.download_dir'));

            if ($error['statut'] == 0)
            $this->ErrorHandler->success('Vos éléments ont été importés avec succès!');
            else
                $this->ErrorHandler->error($error['message']);
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant l'importation!");
            $this->ErrorHandler->error($ex->getMessage());
        } 

        return $this->redirectToRoute('setting_catalogue_import');
    }

    /**
     * @Route("/admin/configuration/email/edit", options={"expose"=true}, name="setting_email_edit") 
     */
    public function emailRegistration(Request $request) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $form = $request->request->get('emails');

        try{
            if (!empty($form)) {
                $quoteFile = $this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'quote.txt';
                file_put_contents($quoteFile, trim($form['quote'], ' \t'));
                file_put_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'facture.txt', trim($form['bill'], ' \t'));
                file_put_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'relance_paiement_1.txt', trim($form['first_reminder'], ' \t'));
                file_put_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'relance_paiement_2.txt', trim($form['seconde_reminder'], ' \t'));
                file_put_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'validation_commande.txt', trim($form['validate'], ' \t'));
                file_put_contents($this->utility->getAbsoluteRootPath() . '/' . $this->getParameter('file.setting.email') . '/' . 'inscription.txt', trim($form['inscription'], ' \t'));
                $this->ErrorHandler->success("Les templates emails ont été sauvegardés avec succès!");
            } else {
                $this->errorHandler->error("Une erreur s'est produite lors de l'enregistrement de vos modèles d\'email!");
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant l'enregistrement des templates email!");
            $this->ErrorHandler->error($ex->getMessage());
        } 
        
        return $this->redirectToRoute('setting_email');
    }

    /**
     * @Route("/admin/configuration/texte/edit", options={"expose"=true}, name="setting_text_edit") 
     */
    public function textRegistration(Request $request, Utility $utility) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $form = $request->request->get('texts');

        try{
            if (!empty($form)) {
                $cgvFile = $utility->getAbsoluteRootPath() . $this->getParameter('file.setting.text') . '/' . 'cgv.txt';
                $quoteFile = $utility->getAbsoluteRootPath() . $this->getParameter('file.setting.text') . '/' . 'quote.txt';
                //dump(trim($form['cgv']));die();
                file_put_contents($cgvFile, trim($form['cgv'], ' \t'));
                file_put_contents($quoteFile, trim($form['quote'], ' \t'));

                $event = new GenericEvent([
                    'files' => [$cgvFile, $quoteFile]
                ]);
                $this->eventDispatcher->dispatch(MyEvents::FTP_TEXT_SEND, $event);
                $this->ErrorHandler->success("Les textes ont été sauvegardés avec succès!");
            } else {
                $this->errorHandler->error("Une erreur s'est produite lors de l'enregistrement de vos modèles d\'email!");
            }
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant l'enregistrement des textes!");
            $this->ErrorHandler->error($ex->getMessage());
        } 
        
        return $this->redirectToRoute('setting_text');
    }

#endregion

#region [ deletes ]
    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Deletes ]--------------------------------------------------------*/

    /**
     * @Route("/admin/configuration/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_delete")
     */
    public function delete(Setting $setting, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        try {
            $manager->remove($setting);
            $manager->flush();
            $this->ErrorHandler->success("La configuration a été supprimé avec succès!");
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression de la configuration");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/site/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_sites_delete")
     */
    public function siteDelete(Site $site, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        try {
            $manager->remove($site);
            $manager->flush();
            $this->ErrorHandler->success("Le site a été supprimé avec succès!");
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression du site");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->RedirectToRoute('setting_sites');
    }

    /**
     * @Route("/admin/configuration/currency/{id}/delete", options={"expose"=true}, name="setting_currency_delete", requirements={"id"="\d+"})
     */
    public function currencyDelete(Currency $currency, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        try {
            $manager->remove($currency);
            $manager->flush();
            $this->ErrorHandler->success("La monnaie a été supprimé avec succès!");
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression de la monnaie");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->RedirectToRoute('setting_currency');
    }

    /**
     * @Route("/admin/configuration/tax/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_tax_delete")
     */
    public function taxDelete(Tax $tax, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        try {
            $manager->remove($tax);
            $manager->flush();
            $this->ErrorHandler->success("La taxe a été supprimé avec succès!");
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression de la TVA");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->RedirectToRoute('setting_tax');
    }

    /**
     * @Route("/admin/configuration/statut/livraison/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_delivery_status_delete")
     */
    public function deliveryStatusDelete(DeliveryStatus $status, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        try {
            $manager->remove($status);
            $manager->flush();
            $this->ErrorHandler->success("Le status a été supprimé avec succès!");
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression du status livraison");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->RedirectToRoute('setting_delivery_status');
    }

    /**
     * @Route("/admin/configuration/fournisseur/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_provider_delete")
     */
    public function providerDelete(Provider $provider, ObjectManager $manager)
    {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        try {
            $manager->remove($provider);
            $manager->flush();
            $this->ErrorHandler->success("Le fournisseur a été supprimé avec succès!");
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression du fournisseur");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->RedirectToRoute('setting_catalogue_provider');
    }

    /**
     * @Route("/admin/configuration/ean/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_ean_delete")
     */
    public function eanDelete(EanCode $ean, ImeiCodeRepository $imeiRepo, ObjectManager $manager)
    {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        try{
            $imei = $imeiRepo->findOneBy(["EanCode"=> $ean]);
            if(!empty($imei)){
                $imei->setEanCode(null);
                $manager->remove($ean);
                $manager->flush();
                $this->ErrorHandler->success("L'EAN a été supprimé avec succès!");
            }
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression du code EAN");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->RedirectToRoute('setting_catalogue_ean');
    }

    /**
     * @Route("/admin/configuration/famille/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_group_delete")
     */
    public function groupDelete(ItemGroupe $group, ObjectManager $manager)
    {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        try {
            $manager->remove($group);
            $manager->flush();
            $this->ErrorHandler->success("La famille a été supprimé avec succès!");
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression de la famille!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->RedirectToRoute('setting_catalogue_group');
    }

    /**
     * @Route("/admin/configuration/marque/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_brand_delete")
     */
    public function brandDelete(ItemBrand $brand, ObjectManager $manager)
    {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        try {
            $manager->remove($brand);
            $manager->flush();
            $this->ErrorHandler->success("La marque a été supprimé avec succès!");
        } catch (Exception $ex) {
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression de la marque!");
            $this->ErrorHandler->error($ex->getMessage());
        }

        return $this->RedirectToRoute('setting_catalogue_brand');
    }

    /**
     * @Route("/admin/configuration/commande/statut/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="setting_order_status_delete")
     */
    public function orderStatusDelete(OrderStatus $status, QuoteOrderRepository $orderRepo, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        try{
            if (count($orderRepo->findBy(['Status' => $status])) == 0) {
                $manager->remove($status);
                $manager->flush();
            } else
                $this->ErrorHandler->error("Le status ne peut pas être supprimé. Il est en cours d'utilisation par au moins une commande!");
            $this->ErrorHandler->success("Le status a été supprimé avec succès!");
        }catch(Exception $ex){
            $this->ErrorHandler->error("Une erreur s'est produite durant la suppression du status commande!");
            $this->ErrorHandler->error($ex->getMessage());
        }
        return $this->RedirectToRoute('setting_order_status');
    }

#endregion

#region [ Ajax/Data ]
    /*_____________________________________________[ Ajax/Data ]_________________________ */

    /**
     * @Route("/admin/configuration/generale/donnee/{code}", options={"expose"=true}, name="setting_data")
     */
    public function dataSetting(string $code)
    {
       
        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }
        
        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $this->settingRepo->findBy(['Code' => $code])],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }


    /**
     * @Route("/admin/configuration/monnaie/donnee", options={"expose"=true}, name="setting_data_currency")
     */
    public function dataCurrency(CurrencyRepository $currencyRepo)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $currencyRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }


    /**
     * @Route("/admin/configuration/taxe/donnee", options={"expose"=true}, name="setting_data_tax")
     */
    public function dataTax(TaxRepository $taxRepo)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $taxRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }


    /**
     * @Route("/admin/configuration/statut/facturation/donnee", options={"expose"=true}, name="setting_data_delivery_status")
     */
    public function dataDeliveryStatus(DeliveryStatusRepository $delStatusRepo)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $delStatusRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }


    /**
     * @Route("/admin/configuration/statut/commande/donnee", options={"expose"=true}, name="setting_data_order_status")
     */
    public function dataOrderStatus(OrderStatusRepository $orderStatusRepo)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $orderStatusRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }


    /**
     * @Route("/admin/configuration/marque/donnee", options={"expose"=true}, name="setting_data_brand")
     */
    public function dataCatalogueBrand(ItemBrandRepository $brandRepo)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $brandRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }


    /**
     * @Route("/admin/configuration/famille/donnee", options={"expose"=true}, name="setting_data_group")
     */
    public function dataCatalogueGroup(ItemGroupeRepository $groupRepo)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' =>$groupRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }


    /**
     * @Route("/admin/configuration/fournisseur/donnee", options={"expose"=true}, name="setting_data_provider")
     */
    public function dataCatalogueProvider(ProviderRepository $providerRepo)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $providerRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }


    /**
     * @Route("/admin/configuration/ean/donnee", options={"expose"=true}, name="setting_data_ean")
     */
    public function dataCatalogueEan(EanCodeRepository $eanRepo)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        //dump($eanRepo->findAll());die();

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $eanRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }


    /**
     * @Route("/admin/configuration/site/donnee", options={"expose"=true}, name="setting_data_sites")
     */
    public function dataSite(SiteRepository $siteRepo)
    {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SETTING']))) {
            return new Response($this->serializer->serialize([
                'object_array' => ['message' => 'Zone à accés restreint!"'],
                'format' => 'json',
            ]));
        }

        //dump($eanRepo->findAll());die();

        return new Response($this->serializer->serialize([
            'object_array' => ['data' => $siteRepo->findAll()],
            'format' => 'json',
            'group' => 'class_property'
        ]));
    }

#endregion

}
