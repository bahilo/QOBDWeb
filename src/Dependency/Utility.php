<?php

namespace App\Dependency;

class Utility{

    public function __construct()
    {
        
    }

    public function in_array($sourceArray, $object){
        
        foreach($sourceArray as $key => $obj){
            if($obj->getId() == $object->getId())
                return true;
        }
        return false;
    }
}