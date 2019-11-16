<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Agent;
use App\Entity\Action;
use App\Entity\Privilege;
use App\Entity\ActionRole;
use App\Services\QOBDSerializer;
use App\Form\RoleRegistrationType;
use App\Repository\RoleRepository;
use App\Form\AgentRegistrationType;
use App\Repository\AgentRepository;
use App\Form\ActionRegistrationType;
use App\Repository\ActionRepository;
use App\Repository\LicenseRepository;
use App\Repository\PrivilegeRepository;
use App\Repository\ActionRoleRepository;
use App\Repository\ActionTrackerRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/admin/security", name="security_home")
     */
    public function home( QOBDSerializer $QOBDSerializer, 
                          ActionRoleRepository $actionRoleRepo, 
                          AgentRepository $agentRepo, 
                          RoleRepository $roleRepo, 
                          ActionRepository $actionRepo, 
                          PrivilegeRepository $privilegeRepo)
    {
        $roles = $roleRepo->findAll();
        $actions = $actionRepo->findAll();
        return $this->render('security/index.html.twig', [
            'agents' => $agentRepo->findAll(),
            'roles' => $roles,
            'actions' => $actions,
            'action_roles' => $actionRoleRepo->findAll(),
            'action_data_source' => $QOBDSerializer->getSerializer()->serialize($actions, 'json'),
            'role_data_source' => $QOBDSerializer->getSerializer()->serialize($roles, 'json')
        ]);
    }

    /*--------------------------------------------------------------------------------------------------
    ------------------------------------[ login/logout ]------------------------------------------------
    ----------------------------------------------------------------------------------------------------*/

    /**
     * @Route("/admin/security/agent/inscription", options={"expose"=true}, name="security_registration")
     * @Route("/admin/security/agent/inscription/{id}/edit", options={"expose"=true}, name="security_edit")
     * 
     */
    public function agentRegistration(Agent $agent = null, 
                                      Request $request, 
                                      ObjectManager $manager, 
                                      UserPasswordEncoderInterface $encoder)
    {
        $isEdit = true;
        if(!$agent){
            $agent = new Agent();
            $isEdit = false;
        }
        
        $form = $this->createForm(AgentRegistrationType::class, $agent);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            if($agent->getPlainTextPassword() !== null){
                $hash = $encoder->encodePassword($agent, $agent->getPlainTextPassword());
                $agent->setPassword($hash);
            }
            $agent->setIsAdmin(false);
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
    public function delete(Agent $agent, ObjectManager $manager)
    {
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
                               ObjectManager $manager){
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
                
                dump($privilege);
                dump($action);
                dump($role);

                if($bInitialized){                    
                    $manager->persist($privilege);
                    $manager->persist($action);                
                    $manager->persist($role);
                    $manager->persist($actionRole);
                    $manager->flush();
                }
            }           
        }

        return $this->RedirectToRoute('security_home');
    }

    /**
     * @Route("/admin/security/agent_profile/create", options={"expose"=true}, name="security_agent_profile")
     *
     */
    public function agentProfile(Request $request, 
                                 AgentRepository $agentRepo, 
                                 RoleRepository $roleRepo, 
                                 ObjectManager $manager){
    
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

        return $this->RedirectToRoute('security_home');

    }

     /**
     * @Route("/admin/security/action/create", options={"expose"=true}, name="security_action_registration")
     * @Route("/admin/security/action/{id}/edit", options={"expose"=true}, name="security_action_edit")
     * 
     */
    public function actionRegistration(Action $action = null, 
                                       Request $request, 
                                       ObjectManager $manager)
    {
        if(!$action){
            $action = new Action();
        }
        
        $form = $this->createForm(ActionRegistrationType::class, $action);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $manager->persist($action);
            $manager->flush();

            return $this->redirectToRoute('security_home');
        }

        return $this->render('security/action_registration.html.twig', [
            'formAction' => $form->createView()
        ]);
    }
 
    /**
     * @Route("/admin/security/action/{id}/delete", options={"expose"=true}, name="security_action_delete")
     */
    public function actionDelete(Action $action, ObjectManager $manager)
    {
        $manager->remove($action);
        $manager->flush();

        return $this->RedirectToRoute('security_home');
    }

    /**
     * @Route("/admin/security/role/create", options={"expose"=true}, name="security_role_registration")
     * @Route("/admin/security/role/{id}/edit", options={"expose"=true}, name="security_role_edit")
     * 
     */
    public function roleRegistration(Role $role = null, Request $request, ObjectManager $manager)
    {
        if(!$role){
            $role = new Role();
        }
        
        $form = $this->createForm(RoleRegistrationType::class, $role);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $manager->persist($role);
            $manager->flush();

            return $this->redirectToRoute('security_home');
        }

        return $this->render('security/role_registration.html.twig', [
            'formRole' => $form->createView()
        ]);
    }
 
    /**
     * @Route("/admin/security/role/{id}/delete", options={"expose"=true}, name="security_role_delete")
     */
    public function roleDelete(Role $role, ObjectManager $manager)
    {
        $manager->remove($role);
        $manager->flush();

        return $this->RedirectToRoute('security_home');
    }

}
