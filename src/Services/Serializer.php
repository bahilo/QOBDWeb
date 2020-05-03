<?php

namespace App\Services;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

class Serializer{

    protected $serializer;

    public function __construct(SerializerInterface $serializer){

        $this->serializer = $serializer;      

    }

    public function serialize($params){
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        if(!empty($params['group']))
            $context->setGroups(array($params['group']));
        return $this->serializer->serialize($params['object_array'], $params['format'], $context);
    }
}