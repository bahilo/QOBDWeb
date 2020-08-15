<?php

namespace App\Controller;

use App\Services\Utility;
use App\Entity\BlogSetting;
use App\Form\BlogSettingRegistrationType;
use App\Repository\BlogSettingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogAdminController extends Controller
{
    /**
     * @Route("/blog/admin", name="blog_admin")
     */
    public function index()
    {
        return $this->render('blog_admin/index.html.twig', [
            'controller_name' => 'BlogAdminController',
        ]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Views ]--------------------------------------------------------*/

    /**
     * @Route("/blog/admin/configuration", name="blog_admin_setting_home")
     */
    public function home(
        BlogSettingRepository $settingRepo,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('setting/index.html.twig', [
            'data_table' => 'general_table_js',
            'data_table_source' => 'general_data_source',
            'page_title' => '',
            //'source' => $utility->getSettingDataSource($settingRepo->findAll()),
            'codes' => $utility->getDistinctByCode($settingRepo->findAll()),
            'create_url' => $this->generateUrl('blog_admin_setting_registration'),
            'page' => 'setting/_partials/general.html',
        ]);
    }

    /**
     * @Route("/blog/admin/configuration/posters", name="blog_admin_setting_poster")
     */
    public function posters(
        BlogSettingRepository $settingRepo,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('setting/posters.html.twig', [
            'data_table' => 'general_table_js',
        ]);
    }

    /**
     * @Route("/blog/admin/configuration/interets", name="blog_admin_setting_interest")
     */
    public function interest(
        BlogSettingRepository $settingRepo,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('setting/interest.html.twig', [
            'data_table' => 'general_table_js',
        ]);
    }

    /**
     * @Route("/blog/admin/configuration/articles", name="blog_admin_article_general")
     */
    public function articleGeneral(
        BlogSettingRepository $settingRepo,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('setting/article_general.html.twig', [
            'data_table' => 'general_table_js',
        ]);
    }

    /**
     * @Route("/blog/admin/configuration/articles/produits", name="blog_admin_article_product")
     */
    public function articleProduit(
        BlogSettingRepository $settingRepo,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('setting/article_product.html.twig', [
            'data_table' => 'general_table_js',
        ]);
    }

    /**
     * @Route("/blog/admin/configuration/equipe", name="blog_admin_article_team")
     */
    public function articleTeam(
        BlogSettingRepository $settingRepo,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('setting/article_team.html.twig', [
            'data_table' => 'general_table_js',
        ]);
    }

    /**
     * @Route("/blog/admin/configuration/temoignage", name="blog_admin_article_testimony")
     */
    public function articleTestimony(
        BlogSettingRepository $settingRepo,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('setting/article_testimony.html.twig', [
            'data_table' => 'general_table_js',
        ]);
    }

    /**
     * @Route("/blog/admin/configuration/partenaire", name="blog_admin_article_partenaire")
     */
    public function articlePartenaire(
        BlogSettingRepository $settingRepo,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        return $this->render('setting/article_partenaire.html.twig', [
            'data_table' => 'general_table_js',
        ]);
    }

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Registrations ]--------------------------------------------------------*/

    /**
     * @Route("/blog/admin/configuration/inscription", options={"expose"=true}, name="blog_admin_setting_registration")
     * @Route("/blog/admin/configuration/{id}/edit", options={"expose"=true}, requirements={"id"="\d+"}, name="blog_admin_setting_edit")
     * 
     */
    public function registration(
        BlogSetting $setting = null,
        Request $request,
        ObjectManager $manager,
        Utility $utility
    ) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$setting)
            $setting = new BlogSetting();

        $form = $this->createForm(BlogSettingRegistrationType::class, $setting);

        $form->handleRequest($request);

        //dump($request);die();
        if ($form->isSubmitted() && $form->isValid()) {

            //dump($request);die();

            if (empty($request->files->get('setting')['switch'])) {
                $file = $request->files->get('setting')['file'];
                $fileName = $utility->uploadFile($file, $this->getParameter('file.setting.image.download_dir'));
                if (!empty($fileName)) {
                    if ($setting->getIsFile() && !empty($setting->getValue())) {
                        unlink($this->getParameter('file.setting.image.download_dir') . '/' . $setting->getValue());
                    }
                    $setting->setValue($fileName);
                    $setting->setIsFile(true);
                }
            } else {
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

    /*______________________________________________________________________________________________________________________ 
    --------------------------------------------[ Deletes ]--------------------------------------------------------*/

    /**
     * @Route("/blog/admin/configuration/{id}/delete", options={"expose"=true}, requirements={"id"="\d+"}, name="blog_adminsetting_delete")
     */
    public function delete(BLogSetting $setting, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_BLOG']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($setting);
        $manager->flush();

        return $this->RedirectToRoute('setting_home');
    }
}
