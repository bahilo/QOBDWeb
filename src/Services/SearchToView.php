<?php

namespace App\Services;

use App\Entity\Agent;
use App\Services\ChatToView;
use App\Services\ChatManager;
use App\Repository\SettingRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SearchToView{

    protected $settingRepo;
    protected $settingFileDir;
    protected $avatarFileDir;
    protected $chatManager;
    protected $chatUtility;

    public function __construct(SettingRepository $settingRepo, 
                                $logo_dir,
                                $avatar_dir,
                                ChatManager $chatManager,
                                ChatToView $chatUtility)
    {
        $this->settingRepo = $settingRepo;
        $this->settingFileDir = $logo_dir;
        $this->avatarFileDir = $avatar_dir;
        $this->chatManager = $chatManager;
        $this->chatUtility = $chatUtility;
    }

    public function logo(){

        $setting = $this->settingRepo->findOneBy(['Code' => 'SOCIETE', 'Name' => 'logo']);
        if(!empty($setting)){
            return $this->settingFileDir . '/' . $setting->getValue();
        }
        return "";
    }

    public function avatar_dir(?Agent $agent){

        if(!empty($agent)){
            return $this->avatarFileDir . '/' . $agent->getPicture();
        }
        return "";
    }

    public function discussions(){

        return $this->chatManager->hydrateDiscussion($this->chatUtility->discussions());
    }
}