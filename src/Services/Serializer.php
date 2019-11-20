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
        return $this->serializer->serialize($params['object_array'], $params['format'], SerializationContext::create()->setGroups(array($params['group'])));
    }
}