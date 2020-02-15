<?php

namespace App\Services;

use App\Entity\Agent;
use App\Services\ChatToView;
use App\Services\ChatManager;
use App\Repository\SettingRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SearchToView{

    protected $setting;
    protected $settingFileDir;
    protected $avatarFileDir;
    protected $chatManager;
    protected $chatUtility;

    public function __construct(SettingManager $setting, 
                                $logo_dir,
                                $avatar_dir,
                                ChatManager $chatManager,
                                ChatToView $chatUtility)
    {
        $this->setting = $setting;
        $this->settingFileDir = $logo_dir;
        $this->avatarFileDir = $avatar_dir;
        $this->chatManager = $chatManager;
        $this->chatUtility = $chatUtility;
    }

    public function logo(){

        $setting = $this->get_setting('SOCIETE', 'SOCIETE_LOGO');
        if(!empty($setting) && !empty($setting->getValue())){
            return $this->settingFileDir . '/' . $setting->getValue();
        }
        return null;
    }

    public function avatar_dir(?Agent $agent){

        if(!empty($agent)){
            return $this->avatarFileDir . '/' . ((!empty($agent->getPicture())) ? $agent->getPicture() : 'default.png') ;
        }
        return "";
    }

    public function discussions(){
        return $this->chatManager->hydrateDiscussion($this->chatUtility->discussions());
    }

    public function get_setting($code, $name){
        return $setting = $this->setting->get($code, $name);
    }
}