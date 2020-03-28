<?php

namespace App\Services;

use App\Entity\Agent;
use App\Services\ChatToView;
use App\Services\ChatManager;
use App\Repository\ActionRepository;
use App\Repository\SettingRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SearchToView{

    protected $setting;
    protected $settingFileDir;
    protected $avatarFileDir;
    protected $rootDir;
    protected $chatManager;
    protected $securityManager;
    protected $actionRepo;
    protected $chatUtility;

    public function __construct(SettingManager $setting, 
                                $logo_dir,
                                $avatar_dir,
                                $root_dir,
                                ChatManager $chatManager,
                                SecurityManager $securityManager,
                                ActionRepository $actionRepo,
                                ChatToView $chatUtility)
    {
        $this->setting = $setting;
        $this->settingFileDir = $logo_dir;
        $this->avatarFileDir = $avatar_dir;
        $this->rootDir = $root_dir;
        $this->chatManager = $chatManager;
        $this->securityManager = $securityManager;
        $this->actionRepo = $actionRepo;
        $this->chatUtility = $chatUtility;
    }

    public function logo(){
        $setting = $this->get_setting('SOCIETE', 'SOCIETE_LOGO');
        if(!empty($setting) && $setting->getIsFile() && !empty($setting->getValue())){
            return $this->root_dir() . '/'. $this->settingFileDir . '/' . $setting->getValue();
        }
        return null;
    }

    public function root_dir(){
        return $this->rootDir;
    }

    public function avatar_dir(?Agent $agent){

        if(!empty($agent)){
            return $this->root_dir() . '/' . $this->avatarFileDir . '/' . ((!empty($agent->getPicture())) ? $agent->getPicture() : 'default.png') ;
        }
        return "";
    }

    public function get_right_admin(){

        return $this->securityManager->checkIsAdmin();
    }

    public function discussions(){
        return $this->chatManager->hydrateDiscussion($this->chatUtility->discussions());
    }

    public function get_setting($code, $name){
        return $setting = $this->setting->get($code, $name);
    }

    public function get_right_read(string $actionName){
        if ($this->securityManager->checkHasRead($this->actionRepo->findOneBy(['Name' => $actionName]))) {
            return true;
        }        
        return false;
    }

    public function get_right_write(string $actionName){
        if ($this->securityManager->checkHasWrite($this->actionRepo->findOneBy(['Name' => $actionName]))) {
            return true;
        }        
        return false;
    }

    public function get_right_update(string $actionName){
        if ($this->securityManager->checkHasUpdate($this->actionRepo->findOneBy(['Name' => $actionName]))) {
            return true;
        }        
        return false;
    }

    public function get_right_delete(string $actionName){
        if ($this->securityManager->checkHasDelete($this->actionRepo->findOneBy(['Name' => $actionName]))) {
            return true;
        }        
        return false;
    }

    public function get_right_email(string $actionName){
        if ($this->securityManager->checkHasEmail($this->actionRepo->findOneBy(['Name' => $actionName]))) {
            return true;
        }        
        return false;
    }
}