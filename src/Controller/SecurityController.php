<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Agent;
use App\Entity\Action;
use App\Entity\Privilege;
use App\Services\Utility;
use App\Entity\ActionRole;
use App\Services\Serializer;
use App\Services\SecurityManager;
use App\Form\RoleRegistrationType;
use App\Repository\RoleRepository;
use App\Form\AgentRegistrationType;
use App\Repository\AgentRepository;
use App\Form\ActionRegistrationType;
use App\Repository\ActionRepository;
use App\Repository\ActionRoleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{

    protected $securityUtility;
    protected $actionRepo;


    public function __construct(SecurityManager $securityUtility, ActionRepository $actionRepo)
    {
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
    }
    
    // /**
    //  * @Route("/admin/security", name="security_home")
    //  */
    // public function home( Serializer $serializer, 
    //                      ActionRoleRepository $actionRoleRepo, 
    //                      AgentRepository $agentRepo, 
    //                      RoleRepository $roleRepo) {

    //     if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
    //         return $this->redirectToRoute('security_deny_access');
    //     }
        
    //     $roles = $roleRepo->findAll();
    //     $actions = $this->actionRepo->findAll();
    //     return $this->render('security/index.html.twig', [
    //         'agents' => $agentRepo->findAll(),
    //         'roles' => $roles,
    //         'actions' => $actions,
    //         'action_roles' => $actionRoleRepo->findAll(),
    //         'action_data_source' => $serializer->serialize(['object_array' => $actions, 'format' => 'json', 'group' => 'class_property']),
    //         'role_data_source' => $serializer->serialize(['object_array' => $roles, 'format' => 'json', 'group' => 'class_property'], 'json')
    //     ]);
    // }
    
    /**
     * @Route("/admin/security/commerciaux/role", name="security_agent_role")
     */
    public function agentRole( Serializer $serializer, 
                         ActionRoleRepository $actionRoleRepo, 
                         AgentRepository $agentRepo, 
                         RoleRepository $roleRepo) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        $roles = $roleRepo->findAll();
        $actions = $this->actionRepo->findAll();
        return $this->render('security/index.html.twig', [
            'agents' => $agentRepo->findAll(),
            'roles' => $roles,
            'page_title' => 'Roles commerciaux',
            'page' => 'security/_partials/index.html'
        ]);
    }

    /**
     * @Route("/admin/security/action", options={"expose"=true}, name="security_action")
     */
    public function action( Serializer $serializer,
                            ActionRepository $actionRepo) {


        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        $actions = $actionRepo->findAll();
        return $this->render('security/index.html.twig', [
            'action_data_source' => $serializer->serialize(['object_array' => $actions, 'format' => 'json', 'group' => 'class_property']),
            'page_title' => 'Action',
            'page' => 'security/_partials/action.html',
            'security_target' => 'action',
            'route_plus' => $this->generateUrl('security_action_registration')
       ]);
    }

    /**
     * @Route("/admin/security/profile", options={"expose"=true}, name="security_profile")
     */
    public function profile(ActionRoleRepository $actionRoleRepo, 
                            AgentRepository $agentRepo,
                            RoleRepository $roleRepo,
                            Utility $utility) {


        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $roles = $roleRepo->findAll();
        $actions = $this->actionRepo->findAll();

        return $this->render('security/index.html.twig', [
            'agents' => $agentRepo->findAll(),
            'target_profile' => true,
            'roles' => $roles,
            'roles_distinct' => $utility->getDistinct($roles),
            'actions' => $actions,
            'action_roles' => $actionRoleRepo->findAll(),
            'page_title' => 'Profile',
            'page' => 'security/_partials/profile.html',
            'security_target' => 'profile',
            
        ]);
    }

    /**
     * @Route("/admin/security/role", options={"expose"=true}, name="security_role")
     */
    public function role( Serializer $serializer, 
                          RoleRepository $roleRepo ) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        $roles = $roleRepo->findAll();
        return $this->render('security/index.html.twig', [
            'role_data_source' => $serializer->serialize(['object_array' => $roles, 'format' => 'json', 'group' => 'class_property'], 'json'),
            'page_title' => 'Role',
            'page' => 'security/_partials/role.html',
            'security_target' => 'role',
            'route_plus' => $this->generateUrl('security_role_registration')
        ]);
    }

    /**
     * @Route("/admin/security/accesrefuse", name="security_deny_access")
     */
    public function denyAccess() {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('security/access_deny.html.twig');
    }

    /*--------------------------------------------------------------------------------------------------
    ------------------------------------[ login/logout ]------------------------------------------------
    ----------------------------------------------------------------------------------------------------*/

    /**
     * @Route("/security/agent/inscription", options={"expose"=true}, name="security_registration")
     * @Route("/admin/security/agent/inscription/{id}/edit", options={"expose"=true}, name="security_edit")
     * 
     */
    public function agentRegistration(Agent $agent = null, 
                                      Request $request, 
                                      ObjectManager $manager, 
                                      UserPasswordEncoderInterface $encoder,
                                      RoleRepository $roleRepo,
                                      Utility $utility)
    {
        
        $isEdit = true;
        if(!$agent){
            $agent = new Agent();
            $isEdit = false;
        }

        
        $form = $this->createForm(AgentRegistrationType::class, $agent);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $role = $roleRepo->findOneBy(['Name' => 'ROLE_ANONYMOUS']);
            
            $file = $request->files->get('agent_registration')['PictureFile'];
          
            $fileName = $utility->uploadFile($file, $this->getParameter('file.agent.download_dir'));
            if (!empty($fileName)) {
                if(!empty($agent->getPicture())){
                    unlink($this->getParameter('file.agent.download_dir') . '/'. $agent->getPicture());
                }
                $agent->setPicture($fileName);
            }

            if($agent->getPlainTextPassword() !== null){
                $hash = $encoder->encodePassword($agent, $agent->getPlainTextPassword());
                $agent->setPassword($hash);
            }

            $agent->setIsAdmin(false);
            
            if(!$isEdit){
                $agent->setIsActivated(false);
                $agent->addRole($role);
            }

            $manager->persist($agent);
            $manager->flush();

            if(!$isEdit)
                return $this->redirectToRoute('security_login');
            else
                return $this->redirectToRoute('agent_home');
        }

        return $this->render('security/agent_registration.html.twig', [
            'formAgent' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/security/agent/{id}/activation", options={"expose"=true}, name="security_activate_agent")
     */
    public function activateAgent(Agent $agent, ObjectManager $manager)
    {
        if (!$this->securityUtility->checkHasUpdate($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if($agent->getIsActivated())
            $agent->setIsActivated(false);
        else
            $agent->setIsActivated(true);

        $manager->persist($agent);
        $manager->flush();
        
        return $this->RedirectToRoute('agent_home');
    }

    /**
     * @Route("/security/agent/anonyme/inscription", options={"expose"=true}, name="security_anonymous_registration")
     */
    public function anonymousRegistration()
    {
        $form = $this->createForm(AgentRegistrationType::class, new Agent());
        return $this->render('security/anonymous_registration.html.twig', [
            'formAgent' => $form->createView()
        ]);
    }

    /**
     * @Route("/security/agent/connexion", options={"expose"=true}, name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/admin/security/agent/deconnexion", options={"expose"=true}, name="security_logout")
     */
    public function logout() {}

    /**
     * @Route("/admin/security/agent/{id}/delete", options={"expose"=true}, name="security_delete")
     */
    public function delete(Agent $agent, ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($agent);
        $manager->flush();

        return $this->RedirectToRoute('agent_home');
    }

    /*--------------------------------------------------------------------------------------------------
    -------------------------------------[ Privileges ]-------------------------------------------------
    ----------------------------------------------------------------------------------------------------*/
    /**
     * @Route("/admin/security/action_role/create", options={"expose"=true}, name="security_action_role")
     *
     */
    public function actionRole(Request $request, 
                               ActionRoleRepository $actionRoleRepo, 
                               RoleRepository $roleRepo, 
                               ActionRepository $actionRepo, 
                               ObjectManager $manager) {

        if (!$this->securityUtility->checkHasRead($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        //dump('Passed'); die();

         $privileges = $request->request->get('privilege');
          
         foreach($privileges as $keyRole => $oRole){
             
             $role = $roleRepo->find($keyRole);
             
             foreach($oRole as $keyAction => $oAction){
                 $bInitialized = false;
                 $action = $actionRepo->find($keyAction);
                 $actionRole = $actionRoleRepo->findOneBy(['Action' => $action, 'Role' => $role]);
                 
                 if(!$actionRole)
                    $actionRole = new ActionRole();

                 $privilege = $actionRole->getPrivilege();

                 if(!$privilege)
                    $privilege = new Privilege();

                if($privilege->getId() && $privilege->getId() > 0){
                    $bInitialized = true;
                }

                if($privileges[$keyRole][$keyAction]['IsRead'] != ""){
                    $privilege->setIsRead(true);
                    $bInitialized = true;
                }
                else
                    $privilege->setIsRead(false);
                
                if($privileges[$keyRole][$keyAction]['IsWrite'] != ""){
                    $privilege->setIsWrite(true);
                    $bInitialized = true;
                }
                else
                    $privilege->setIsWrite(false);
                
                if($privileges[$keyRole][$keyAction]['IsUpdate'] != ""){
                    $privilege->setIsUpdate(true);
                    $bInitialized = true;
                }
                else
                    $privilege->setIsUpdate(false);
                
                if($privileges[$keyRole][$keyAction]['IsRead'] != ""){
                    $privilege->setIsRead(true);
                    $bInitialized = true;
                }
                else
                    $privilege->setIsRead(false);
                
                if($privileges[$keyRole][$keyAction]['IsDelete'] != ""){
                    $privilege->setIsDelete(true);
                    $bInitialized = true;
                }
                else
                    $privilege->setIsDelete(false);
                
                if($privileges[$keyRole][$keyAction]['IsSendMail'] != ""){
                    $privilege->setIsSendMail(true);
                    $bInitialized = true;
                }
                else
                    $privilege->setIsSendMail(false);

                $privilege->setCreatedAt(new \DateTime());
                
                $actionRole->setPrivilege($privilege);
                $actionRole->setAction($action);
                $actionRole->setRole($role);    
                
                // dump($privilege);
                // dump($action);
                // dump($role);

                if($bInitialized){                    
                    $manager->persist($privilege);
                    $manager->persist($action);                
                    $manager->persist($role);
                    $manager->persist($actionRole);
                    $manager->flush();
                }
            }           
        }

        return $this->RedirectToRoute('security_profile');
    }

    /**
     * @Route("/admin/security/agent_profile/create", options={"expose"=true}, name="security_agent_profile")
     *
     */
    public function agentProfile(Request $request, 
                                 AgentRepository $agentRepo, 
                                 RoleRepository $roleRepo, 
                                 ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }
    
        $profile = $request->request->get('profile');

        foreach ($profile as $keyAgent => $oAgent) {

            $agent = $agentRepo->find($keyAgent);

            foreach ($oAgent as $keyRole => $oRole) {

                $role = $roleRepo->find($keyRole);  
                $agent_roles = $agent->getObjectRoles();

                if ($agent_roles->contains($role)){
                    if ($profile[$keyAgent][$keyRole] == ""){
                        $agent->removeRole($role);
                    }
                }
                elseif($profile[$keyAgent][$keyRole] != ""){
                    $agent->addRole($role);
                }

                $manager->persist($agent);
                $manager->flush();

            }
        }

        return $this->RedirectToRoute('security_agent_role');

    }

     /**
     * @Route("/admin/security/action/create", options={"expose"=true}, name="security_action_registration")
     * @Route("/admin/security/action/{id}/edit", options={"expose"=true}, name="security_action_edit")
     * 
     */
    public function actionRegistration(Action $action = null, 
                                       Request $request, 
                                       ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if(!$action){
            $action = new Action();
        }
        
        $form = $this->createForm(ActionRegistrationType::class, $action);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $manager->persist($action);
            $manager->flush();

            return $this->redirectToRoute('security_action');
        }

        return $this->render('security/action_registration.html.twig', [
            'formAction' => $form->createView()
        ]);
    }
 
    /**
     * @Route("/admin/security/action/{id}/delete", options={"expose"=true}, name="security_action_delete")
     */
    public function actionDelete(Action $action, ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($action);
        $manager->flush();

        return $this->RedirectToRoute('security_action');
    }

    /**
     * @Route("/admin/security/role/create", options={"expose"=true}, name="security_role_registration")
     * @Route("/admin/security/role/{id}/edit", options={"expose"=true}, name="security_role_edit")
     * 
     */
    public function roleRegistration(Role $role = null, Request $request, ObjectManager $manager) {

        if (!$this->securityUtility->checkHasWrite($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        if(!$role){
            $role = new Role();
        }
        
        $form = $this->createForm(RoleRegistrationType::class, $role);
        // dump($request);
        // die();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $manager->persist($role);
            $manager->flush();

            return $this->redirectToRoute('security_role');
        }

        return $this->render('security/role_registration.html.twig', [
            'formRole' => $form->createView()
        ]);
    }
 
    /**
     * @Route("/admin/security/role/{id}/delete", options={"expose"=true}, name="security_role_delete")
     */
    public function roleDelete(Role $role, ObjectManager $manager) {

        if (!$this->securityUtility->checkHasDelete($this->getUser(), $this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $manager->remove($role);
        $manager->flush();

        return $this->RedirectToRoute('security_role');
    }

}
