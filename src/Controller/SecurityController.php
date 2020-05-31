<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Agent;
use App\Entity\Action;
use App\Events\MyEvents;
use App\Services\Mailer;
use App\Entity\Privilege;
use App\Services\Utility;
use App\Entity\ActionRole;
use App\Services\Serializer;
use App\Services\ErrorHandler;
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
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends Controller
{

    protected $manager;
    protected $securityUtility;
    protected $actionRepo;
    protected $agentRepo;
    protected $eventDispatcher;
    protected $ErrorHandler;


    public function __construct(SecurityManager $securityUtility, 
                                ActionRepository $actionRepo, 
                                AgentRepository $agentRepo, 
                                EventDispatcherInterface $eventDispatcher, 
                                ObjectManager $manager,
                                ErrorHandler $ErrorHandler)
    {
        $this->securityUtility = $securityUtility;
        $this->actionRepo = $actionRepo;
        $this->agentRepo = $agentRepo;
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
        $this->ErrorHandler = $ErrorHandler;
    }
 
#region [ Views ]
    // /**
    //  * @Route("/admin/security", name="security_home")
    //  */
    // public function home( Serializer $serializer, 
    //                      ActionRoleRepository $actionRoleRepo, 
    //                      AgentRepository $agentRepo, 
    //                      RoleRepository $roleRepo) {

    //     if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
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

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        $roles = $roleRepo->findAll();
        $actions = $this->actionRepo->findAll();
        return $this->render('security/home/home_agent_role.html.twig', [
            'agents' => $agentRepo->findAll(),
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/admin/security/action", options={"expose"=true}, name="security_action")
     */
    public function action( Serializer $serializer,
                            ActionRepository $actionRepo) {


        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        $actions = $actionRepo->findAll();
        return $this->render('security/home/home_action.html.twig', [
            'action_data_source' => $serializer->serialize(['object_array' => $actions, 'format' => 'json', 'group' => 'class_property']),
            'security_target' => 'action',
       ]);
    }

    /**
     * @Route("/admin/security/profile", options={"expose"=true}, name="security_profile")
     */
    public function profile(ActionRoleRepository $actionRoleRepo, 
                            AgentRepository $agentRepo,
                            RoleRepository $roleRepo,
                            Utility $utility) {


        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $roles = $roleRepo->findAll();
        $actions = $this->actionRepo->findAll();
        
        return $this->render('security/home/home_profile.html.twig', [
            'agents' => $agentRepo->findAll(),
            'roles' => $roles,
            'roles_distinct' => $roles,
            'actions' => $actions,
            'action_roles' => $actionRoleRepo->findAll(),
            'security_target' => 'profile',
            
        ]);
    }

    /**
     * @Route("/admin/security/role", options={"expose"=true}, name="security_role")
     */
    public function role( Serializer $serializer, 
                          RoleRepository $roleRepo ) {

        if (!$this->securityUtility->checkHasRead($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }
        
        $roles = $roleRepo->findAll();
        return $this->render('security/home/home_role.html.twig', [
            'role_data_source' => $serializer->serialize(['object_array' => $roles, 'format' => 'json', 'group' => 'class_property'], 'json'),
            'security_target' => 'role',
        ]);
    }

    /**
     * @Route("/admin/security/accesrefuse", name="security_deny_access")
     */
    public function denyAccess() {
        
        return $this->render('security/access_deny.html.twig');
    }

#endregion

#region [ login/logout ]
    /*--------------------------------------------------------------------------------------------------
    ------------------------------------[ login/logout ]------------------------------------------------
    ----------------------------------------------------------------------------------------------------*/

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
    public function logout()
    {
    }

#endregion

#region [ Registration ]
    /**
     * @Route("/security/agent/inscription", options={"expose"=true}, name="security_registration")
     * @Route("/admin/security/agent/inscription/{id}/edit", options={"expose"=true}, name="security_edit")
     * 
     */
    public function agentRegistration(Agent $agent = null, 
                                      Request $request,                                        
                                      UserPasswordEncoderInterface $encoder,
                                      RoleRepository $roleRepo,
                                      Utility $utility,
                                      Mailer $mailer,
                                      ValidatorInterface $validator)
    {
        
        $isEdit = true;
        if(!$agent){
            $agent = new Agent();
            $isEdit = false;
        }

        
        $form = $this->createForm(AgentRegistrationType::class, $agent);

        $form->handleRequest($request);
        $errors = $validator->validate($agent);

        foreach ($errors as $error) {
            $this->ErrorHandler->error($error->getMessage());
        }

        if($form->isSubmitted() && $form->isValid() && count($errors) == 0){
            
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

            $this->manager->persist($agent);
            $this->manager->flush();
            

            if(!$isEdit){
                $event = new GenericEvent([
                    'to' => $agent->getEmail(),
                    'subject' => 'Inscription',
                    'view' =>$this->renderView('email/_partials/registration.html', ['agent' => $agent]),
                ]);
                $this->eventDispatcher->dispatch(MyEvents::USER_REGISTRATION_SEND_EMAIL, $event);
                return $this->redirectToRoute('security_login');
            }
            else
                return $this->redirectToRoute('home');
        }        

        //dump($errors);die();
        if($isEdit){
            return $this->render('agent/show.html.twig', [
                'formAgent' => $form->createView(),
                'agent' => $agent,
            ]);  
        }
        else{
            return $this->render('security/anonymous_registration.html.twig', [
                'formAgent' => $form->createView(),
            ]);            
        }
    }

    /**
     * @Route("/security/agent/anonyme/inscription", options={"expose"=true}, name="security_anonymous_registration")
     */
    public function anonymousRegistration()
    {
        $form = $this->createForm(AgentRegistrationType::class, new Agent());
        return $this->render('security/anonymous_registration.html.twig', [
            'formAgent' => $form->createView(),
            'errors' => []
        ]);
    }

    /**
     * @Route("/admin/security/action/create", options={"expose"=true}, name="security_action_registration")
     * @Route("/admin/security/action/{id}/edit", options={"expose"=true}, name="security_action_edit")
     * 
     */
    public function actionRegistration(
        Action $action = null,
        Request $request
    ) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$action) {
            $action = new Action();
        }

        $form = $this->createForm(ActionRegistrationType::class, $action);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->persist($action);
            $this->manager->flush();

            return $this->redirectToRoute('security_action');
        }

        return $this->render('security/action_registration.html.twig', [
            'formAction' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/security/role/create", options={"expose"=true}, name="security_role_registration")
     * @Route("/admin/security/role/{id}/edit", options={"expose"=true}, name="security_role_edit")
     * 
     */
    public function roleRegistration(Role $role = null, Request $request)
    {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if (!$role) {
            $role = new Role();
        }

        $form = $this->createForm(RoleRegistrationType::class, $role);
        // dump($request);
        // die();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->persist($role);
            $this->manager->flush();

            return $this->redirectToRoute('security_role');
        }

        return $this->render('security/role_registration.html.twig', [
            'formRole' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/security/action_role/create", options={"expose"=true}, name="security_action_role")
     *
     */
    public function actionRole(
        Request $request,
        ActionRoleRepository $actionRoleRepo,
        RoleRepository $roleRepo,
        ActionRepository $actionRepo
    ) {

        if (
            !$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY'])) &&
            !$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))
        ) {
            return $this->redirectToRoute('security_deny_access');
        }

        //dump('Passed'); die();

        $privileges = $request->request->get('privilege');

        foreach ($privileges as $keyRole => $oRole) {

            $role = $roleRepo->find($keyRole);

            foreach ($oRole as $keyAction => $oAction) {
                $bInitialized = false;
                $action = $actionRepo->find($keyAction);
                $actionRole = $actionRoleRepo->findOneBy(['Action' => $action, 'Role' => $role]);

                if (!$actionRole)
                    $actionRole = new ActionRole();

                $privilege = $actionRole->getPrivilege();

                if (!$privilege)
                    $privilege = new Privilege();

                if ($privilege->getId() && $privilege->getId() > 0) {
                    $bInitialized = true;
                }

                if ($privileges[$keyRole][$keyAction]['IsRead'] != "") {
                    $privilege->setIsRead(true);
                    $bInitialized = true;
                } else
                    $privilege->setIsRead(false);

                if ($privileges[$keyRole][$keyAction]['IsWrite'] != "") {
                    $privilege->setIsWrite(true);
                    $bInitialized = true;
                } else
                    $privilege->setIsWrite(false);

                if ($privileges[$keyRole][$keyAction]['IsUpdate'] != "") {
                    $privilege->setIsUpdate(true);
                    $bInitialized = true;
                } else
                    $privilege->setIsUpdate(false);

                if ($privileges[$keyRole][$keyAction]['IsRead'] != "") {
                    $privilege->setIsRead(true);
                    $bInitialized = true;
                } else
                    $privilege->setIsRead(false);

                if ($privileges[$keyRole][$keyAction]['IsDelete'] != "") {
                    $privilege->setIsDelete(true);
                    $bInitialized = true;
                } else
                    $privilege->setIsDelete(false);

                if ($privileges[$keyRole][$keyAction]['IsSendMail'] != "") {
                    $privilege->setIsSendMail(true);
                    $bInitialized = true;
                } else
                    $privilege->setIsSendMail(false);

                $privilege->setCreatedAt(new \DateTime());

                $actionRole->setPrivilege($privilege);
                $actionRole->setAction($action);
                $actionRole->setRole($role);

                // dump($privilege);
                // dump($action);
                // dump($role);

                if ($bInitialized) {
                    $this->manager->persist($privilege);
                    $this->manager->persist($action);
                    $this->manager->persist($role);
                    $this->manager->persist($actionRole);
                    $this->manager->flush();
                }
            }
        }

        return $this->RedirectToRoute('security_profile');
    }

    /**
     * @Route("/admin/security/agent_profile/create", options={"expose"=true}, name="security_agent_profile")
     *
     */
    public function agentProfile(
        Request $request,
        AgentRepository $agentRepo,
        RoleRepository $roleRepo
    ) {

        if (!$this->securityUtility->checkHasWrite($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $profile = $request->request->get('profile');

        foreach ($profile as $keyAgent => $oAgent) {

            $agent = $agentRepo->find($keyAgent);

            foreach ($oAgent as $keyRole => $oRole) {

                $role = $roleRepo->find($keyRole);
                $agent_roles = $agent->getObjectRoles();

                if ($agent_roles->contains($role)) {
                    if ($profile[$keyAgent][$keyRole] == "") {
                        $agent->removeRole($role);
                    }
                } elseif ($profile[$keyAgent][$keyRole] != "") {
                    $agent->addRole($role);
                }

                $this->manager->persist($agent);
                $this->manager->flush();
            }
        }

        return $this->RedirectToRoute('security_agent_role');
    }

#endregion

#region [ Activation ]

    /**
     * @Route("/admin/security/agent/{id}/activation", options={"expose"=true}, name="security_activate_agent")
     */
    public function activateAgent(Agent $agent)
    {
        if (!$this->securityUtility->checkHasUpdate($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        if($agent->getIsActivated())
            $agent->setIsActivated(false);
        else
            $agent->setIsActivated(true);

        $this->manager->persist($agent);
        $this->manager->flush();
        
        return $this->RedirectToRoute('agent_home');
    }

    /**
     * @Route("/security/agent/formulaire/oubli-mot-de-passe/{token}", options={"expose"=true}, name="security_form_password_forgotten")
     */
    public function forgottenPasswordForm(string $token)
    {

        return $this->render('security/password_forgotten_form.html.twig', ["token" => $token]);
    }

    /**
     * @Route("/security/agent/oubli-mot-de-passe", options={"expose"=true}, name="security_password_forgotten")
     */
    public function forgottenPassword(TokenGeneratorInterface $tokenGen, Request $request, Mailer $mailer)
    {

        $username = $request->query->get("_username");

        /** @var Agent $agent */
        $agent = $this->agentRepo->findOneBy(["UserName" => $username]);

        if (!empty($agent)) {

            $token = $tokenGen->generateToken();
            $agent->setToken($token);
            $this->manager->persist($agent);
            $this->manager->flush();
            $mailer->send(
                ['to' => $agent->getEmail()],
                "Mot de passe oublié",
                $this->renderView("email/_partials/password_forgotten.html", ["url" => $this->generateUrl("security_form_password_forgotten", ["token" => $token], UrlGeneratorInterface::ABSOLUTE_URL)])
            );
            $this->ErrorHandler->success("Un mail vous a été envoyé avec un lien, clicker sur le lien pour confirmer le reset de mot de passe!");
            return $this->redirectToRoute("security_login");
        }

        $this->ErrorHandler->error("Votre nom d'utilisateur n'existe pas dans notre base de données!");
        return $this->redirectToRoute("security_login");
    }

    /**
     * @Route("/security/agent/reinitialiser/oubli-mot-de-passe", options={"expose"=true}, name="security_reset_password")
     */
    public function resetPassword(Request $request, EncoderFactoryInterface  $encoderGene)
    {

        /** @var Agent $agent */
        $token = $request->request->get("token");
        $agent = $this->agentRepo->findOneBy(["Token" => $token]);

        if (!empty($agent)) {
            $encoder = $encoderGene->getEncoder($agent);
            $newPassword = $encoder->encodePassword($request->request->get("password"), $agent->getSalt());
            if ($encoder->isPasswordValid($newPassword, $request->request->get("password"), $agent->getSalt())) {
                $agent->setPassword($newPassword);
                $agent->setToken(null);
                $this->manager->persist($agent);
                $this->manager->flush();
                $this->ErrorHandler->success("Votre mot de passe a été regénéré avec succés!");
                return $this->redirectToRoute("security_login");
            } else {
                $this->ErrorHandler->error("Votre mot de passe doit faire minimun 6 caractères!");
                return $this->redirectToRoute("security_form_password_forgotten", ["token" => $token]);
            }
        }
        $this->ErrorHandler->error("Mot de passe non reconnu!");
        return $this->redirectToRoute("security_login");
    }

#endregion

#region [ Delete ]

    /**
     * @Route("/admin/security/agent/{id}/delete", options={"expose"=true}, name="security_delete")
     */
    public function delete(Agent $agent) {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $this->manager->remove($agent);
        $this->manager->flush();

        return $this->RedirectToRoute('agent_home');
    }

    /**
     * @Route("/admin/security/action/{id}/delete", options={"expose"=true}, name="security_action_delete")
     */
    public function actionDelete(Action $action)
    {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $this->manager->remove($action);
        $this->manager->flush();

        return $this->RedirectToRoute('security_action');
    }



    /**
     * @Route("/admin/security/role/{id}/delete", options={"expose"=true}, name="security_role_delete")
     */
    public function roleDelete(Role $role)
    {

        if (!$this->securityUtility->checkHasDelete($this->actionRepo->findOneBy(['Name' => 'ACTION_SECURITY']))) {
            return $this->redirectToRoute('security_deny_access');
        }

        $this->manager->remove($role);
        $this->manager->flush();

        return $this->RedirectToRoute('security_role');
    }

#endregion

}
