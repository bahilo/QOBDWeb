<?php

namespace App\Controller;

use App\Entity\Tax;
use App\Entity\Comment;
use App\Entity\Setting;
use App\Entity\Currency;
use App\Entity\DeliveryStatus;
use App\Form\TaxRegistrationType;
use App\Form\SettingRegistrationType;
use App\Form\CurrencyRegistrationType;
use App\Form\DeliveryStatusRegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingController extends Controller
{
    /**
     * @Route("/admin/configuration", name="setting_home")
     */
    public function home()
    {
        return $this->render('setting/index.html.twig', [
            'controller_name' => 'SettingController',
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
     * @Route("/admin/configuration/{id}/delete", name="setting_delete")
     */
    public function delete(Setting $setting, ObjectManager $manager)
    {
        $manager->remove($setting);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/currency/{id}/delete", name="setting_currency_delete")
     */
    public function currencyDelete(Currency $currency, ObjectManager $manager)
    {
        $manager->remove($currency);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/tax/{id}/delete", name="setting_tax_delete")
     */
    public function taxDelete(Tax $tax, ObjectManager $manager)
    {
        $manager->remove($tax);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

    /**
     * @Route("/admin/configuration/statut/livraison/{id}/delete", name="setting_delivery_status_delete")
     */
    public function deliveryStatusDelete(DeliveryStatus $status, ObjectManager $manager)
    {        
        $manager->remove($status);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }

}
