<?php

namespace App\Controller;

use App\Entity\Tax;
use App\Entity\Comment;
use App\Entity\Setting;
use App\Entity\Currency;
use App\Entity\Provider;
use App\Entity\ItemBrand;
use App\Entity\ItemGroupe;
use App\Entity\OrderStatus;
use App\Services\Serializer;
use App\Entity\DeliveryStatus;
use App\Form\TaxRegistrationType;
use App\Repository\TaxRepository;
use App\Form\SettingRegistrationType;
use App\Repository\SettingRepository;
use App\Form\CurrencyRegistrationType;
use App\Repository\CurrencyRepository;
use App\Repository\ProviderRepository;
use App\Repository\ItemBrandRepository;
use App\Repository\ItemGroupeRepository;
use App\Repository\QuoteOrderRepository;
use JMS\Serializer\SerializationContext;
use App\Form\OrderStatusRegistrationType;
use App\Repository\OrderStatusRepository;
use App\Form\DeliveryStatusRegistrationType;
use App\Repository\DeliveryStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingController extends Controller
{
    /**
     * @Route("/admin/configuration", name="setting_home")
     */
    public function home(SettingRepository $settingRepo,
                        Serializer $serializer)
    {
        return $this->render('setting/index.html.twig', [
            'general_data_source' => $serializer->serialize([
                'object_array' => $settingRepo->findAll(),
                'format' => 'json',
                'group' => 'class_property'
            ])
        ]);
    }

    /**
     * @Route("/admin/configuration/monnaie", options={"expose"=true}, name="setting_currency")
     */
    public function currency(CurrencyRepository $currencyRepo,
                             Serializer $serializer)
    {
        return $this->render('setting/index.html.twig', [
            'currency_data_source' => $serializer->serialize([
                'object_array' => $currencyRepo->findAll(), 
                'format' => 'json',
                'group' =>'class_property'
            ])
        ]);
    }

    /**
     * @Route("/admin/configuration/taxe", options={"expose"=true}, name="setting_tax")
     */
    public function tax(TaxRepository $taxRepo,
                        Serializer $serializer)
    {
        return $this->render('setting/index.html.twig', [
            'tax_data_source' => $serializer->serialize([
                'object_array' => $taxRepo->findAll(),
                'format' => 'json',
                'group' => 'class_property'
            ])
        ]);
    }

    /**
     * @Route("/admin/configuration/facturation/statut", options={"expose"=true}, name="setting_delivery_status")
     */
    public function deliveryStatus(DeliveryStatusRepository $delStatusRepo,
                                   Serializer $serializer)
    {
        return $this->render('setting/index.html.twig', [
            'delivery_status_data_source' => $serializer->serialize([
                'object_array' => $delStatusRepo->findAll(),
                'format' => 'json',
                'group' => 'class_property'
            ])
        ]);
    }

    /**
     * @Route("/admin/configuration/commande/statut", options={"expose"=true}, name="setting_order_status")
     */
    public function orderStatus(OrderStatusRepository $orderStatusRepo,
                                Serializer $serializer)
    {
        return $this->render('setting/index.html.twig', [
            'order_status_data_source' => $serializer->serialize([
                'object_array' => $orderStatusRepo->findAll(),
                'format' => 'json',
                'group' => 'class_property'
            ])
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/marque", options={"expose"=true}, name="setting_catalogue_brand")
     */
    public function catalogueBrand(ItemBrandRepository $brandRepo,
                                   Serializer $serializer)
    {
        return $this->render('setting/index.html.twig', [
            'brand_data_source' => $serializer->serialize([
                'object_array' => $brandRepo->findAll(),
                'format' => 'json',
                'group' => 'class_property'
            ])
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/famille", options={"expose"=true}, name="setting_catalogue_group")
     */
    public function catalogueGroup(ItemGroupeRepository $groupRepo,
                                   Serializer $serializer)
    {
        return $this->render('setting/index.html.twig', [
            'group_data_source' => $serializer->serialize([
                'object_array' => $groupRepo->findAll(),
                'format' => 'json',
                'group' => 'class_property'
            ])
        ]);
    }

    /**
     * @Route("/admin/configuration/produit/fournisseur", options={"expose"=true}, name="setting_catalogue_provider")
     */
    public function catalogueProvider(ProviderRepository $providerRepo,
                                   Serializer $serializer)
    {
        return $this->render('setting/index.html.twig', [
            'group_data_source' => $serializer->serialize([
                'object_array' => $providerRepo->findAll(),
                'format' => 'json',
                'group' => 'class_property'
            ])
        ]);
    }

    /**
     * @Route("/admin/configuration/inscription", options={"expose"=true}, name="setting_registration")
     * @Route("/admin/configuration/{id}/edit", options={"expose"=true}, name="setting_edit")
     * 
     */
    public function registration(Setting $setting = null, Request $request, ObjectManager $manager)
    {
        if(!$setting)
            $setting = new Setting();
        
        $form = $this->createForm(SettingRegistrationType::class, $setting);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
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
     * @Route("/admin/configuration/currency/{id}/edit", options={"expose"=true}, name="setting_currency_edit")
     * 
     */
    public function currencyRegistration(Currency $currency = null, Request $request, ObjectManager $manager)
    {
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

            return $this->redirectToRoute('setting_home');
        }        

        return $this->render('setting/currency_registration.html.twig', [
            'formCurrency' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/statut/livraison/inscription", options={"expose"=true}, name="setting_delivery_status_registration")
     * @Route("/admin/configuration/statut/livraison/{id}/edit", options={"expose"=true}, name="setting_delivery_status_edit")
     * 
     */
    public function deliveryStatusRegistration(DeliveryStatus $status = null, Request $request, ObjectManager $manager)
    {
        if (!$status)
            $status = new DeliveryStatus();

        $form = $this->createForm(DeliveryStatusRegistrationType::class, $status);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($status);
            $manager->flush();

            return $this->redirectToRoute('setting_home');
        }

        return $this->render('setting/delivery_status_registration.html.twig', [
            'formStatus' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/admin/configuration/tax/inscription", options={"expose"=true}, name="setting_tax_registration")
     * @Route("/admin/configuration/tax/{id}/edit", options={"expose"=true}, name="setting_tax_edit")
     * 
     */
    public function taxRegistration(Tax $tax = null, Request $request, ObjectManager $manager)
    {
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
                $comment->setCreatedAt(new \DateTime());
    
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

            return $this->redirectToRoute('setting_home');
        }        

        return $this->render('setting/tax_registration.html.twig', [
            'formTax' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/marque/inscription", options={"expose"=true}, name="setting_brand_registration")
     * @Route("/admin/configuration/marque/{id}/edit", options={"expose"=true}, name="setting_brand_edit")
     * 
     */
    public function brandRegistration(ItemBrand $itemBrand = null, Request $request, ObjectManager $manager)
    {
        if (!$itemBrand)
            $itemBrand = new ItemBrand();

        $form = $this->createForm(ItemBrandRegistrationType::class, $itemBrand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $itemBrand->setCreatedAt(new \DateTime());
            $itemBrand->setIsEnabled(true);

            $manager->persist($itemBrand);
            $manager->flush();

            return $this->redirectToRoute('setting_home');
        }

        return $this->render('setting/brand_registration.html.twig', [
            'formBrand' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/famille/inscription", options={"expose"=true}, name="setting_group_registration")
     * @Route("/admin/configuration/famille/{id}/edit", options={"expose"=true}, name="setting_group_edit")
     * 
     */
    public function groupRegistration(ItemGroupe $itemGroupe = null, Request $request, ObjectManager $manager)
    {
        if (!$itemGroupe)
            $itemGroupe = new ItemGroupe();

        $form = $this->createForm(ItemGroupeRegistrationType::class, $itemGroupe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $itemGroupe->setIsEnabled(true);

            $manager->persist($itemGroupe);
            $manager->flush();

            return $this->redirectToRoute('setting_home');
        }

        return $this->render('setting/group_registration.html.twig', [
            'formGroup' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/fournisseur/inscription", options={"expose"=true}, name="setting_provider_registration")
     * @Route("/admin/configuration/fournisseur/{id}/edit", options={"expose"=true}, name="setting_provider_edit")
     * 
     */
    public function providerRegistration(Provider $provider = null, Request $request, ObjectManager $manager)
    {
        if (!$provider)
            $provider = new Provider();

        $form = $this->createForm(ProviderRegistrationType::class, $provider);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $provider->setIsEnabled(true);

            $manager->persist($provider);
            $manager->flush();

            return $this->redirectToRoute('setting_home');
        }

        return $this->render('setting/provider_registration.html.twig', [
            'formProvider' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/commande/statut/inscription", options={"expose"=true}, name="setting_order_status_registration")
     * @Route("/admin/configuration/commande/statut/{id}/edit", options={"expose"=true}, name="setting_order_status_edit")
     * 
     */
    public function orderStatusRegistration(OrderStatus $status = null, Request $request, ObjectManager $manager)
    {
        if (!$status)
            $status = new OrderStatus();

        $form = $this->createForm(OrderStatusRegistrationType::class, $status);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($status);
            $manager->flush();

            return $this->redirectToRoute('order_home');
        }

        return $this->render('order/status_registration.html.twig', [
            'formStatus' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/configuration/{id}/delete", options={"expose"=true}, name="setting_delete")
     */
    public function delete(Setting $setting, ObjectManager $manager)
    {
        $manager->remove($setting);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/currency/{id}/delete", options={"expose"=true}, name="setting_currency_delete")
     */
    public function currencyDelete(Currency $currency, ObjectManager $manager)
    {
        $manager->remove($currency);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/tax/{id}/delete", options={"expose"=true}, name="setting_tax_delete")
     */
    public function taxDelete(Tax $tax, ObjectManager $manager)
    {
        $manager->remove($tax);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/statut/livraison/{id}/delete", options={"expose"=true}, name="setting_delivery_status_delete")
     */
    public function deliveryStatusDelete(DeliveryStatus $status, ObjectManager $manager)
    {        
        $manager->remove($status);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/fournisseur/{id}/delete", options={"expose"=true}, name="setting_provider_delete")
     */
    public function providerDelete(Provider $provider, ObjectManager $manager)
    {

        $manager->remove($provider);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/famille/{id}/delete", options={"expose"=true}, name="setting_group_delete")
     */
    public function groupDelete(ItemGroupe $group, ObjectManager $manager)
    {

        $manager->remove($group);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/marque/{id}/delete", options={"expose"=true}, name="setting_brand_delete")
     */
    public function brandDelete(ItemBrand $brand, ObjectManager $manager)
    {

        $manager->remove($brand);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/commande/statut/{id}/delete", options={"expose"=true}, name="setting_order_status_delete")
     */
    public function orderStatusDelete(OrderStatus $status, QuoteOrderRepository $orderRepo, ObjectManager $manager)
    {

        if (count($orderRepo->findBy(['Status' => $status])) == 0) {
            $manager->remove($status);
            $manager->flush();
            return $this->RedirectToRoute('order_home');
        }
        return $this->RedirectToRoute('order_home', ['message' => 'Le status ne peut pas être supprimé. Il est en cours d\'utilisation pour au moins une commande!']);
    }

}
