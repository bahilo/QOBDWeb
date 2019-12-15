<?php

namespace App\Services;

use App\Entity\Agent;
use App\Entity\Action;

class SecurityManager{

    public function __construct()
    {
        
    }

    public function isAccessGranted(Agent $agent)
    {
        if (empty($agent) || !$agent->getIsActivated()) {
            return false;
        }
        return true;
    }

    public function checkHas(Agent $agent, Action $action, $privilege)
    {
        if ($this->isAccessGranted($agent)) {
            foreach ($agent->getObjectRoles() as $role) {
                foreach ($role->getActionRoles() as $actionRole) {
                    $arAction = $actionRole->getAction();
                    if (!empty($arAction) && !empty($action) && $arAction->getId() == $action->getId()) {
                        if ($privilege == 'READ' && $actionRole->getPrivilege()->getIsRead())
                            return true;
                        elseif ($privilege == 'UPDATE' && $actionRole->getPrivilege()->getIsUpdate())
                            return true;
                        elseif ($privilege == 'DELETE' && $actionRole->getPrivilege()->getIsDelete())
                            return true;
                        elseif ($privilege == 'WRITE' && $actionRole->getPrivilege()->getIsWrite())
                            return true;
                        elseif ($privilege == 'MAIL' && $actionRole->getPrivilege()->getIsSendMail())
                            return true;
                    }
                }
            }
        }
        return false;
    }

    public function checkHasRead(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'READ');
    }

    public function checkHasWrite(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'WRITE');
    }

    public function checkHasUpdate(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'UPDATE');
    }

    public function checkHasDelete(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'DELETE');
    }

    public function checkHasEmail(Agent $agent, Action $action)
    {
        return $this->checkHas($agent, $action, 'MAIL');
    }

}