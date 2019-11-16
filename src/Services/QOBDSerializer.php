<?php

namespace App\Services;

use App\Dependency\DateTimeNormalizer;
use App\Dependency\FormErrorNormalizer;
use App\Dependency\CollectionNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

class QOBDSerializer
{
    private $serializer;

    public function __construct(){
        
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $normalizer->setCircularReferenceLimit(2);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        
        $encoders = array (new XmlEncoder(), new JsonEncoder());
        $normalizers = array($normalizer);
        $this->serializer = new Serializer($normalizers, $encoders); 
        
    }

    public function getSerializer()
    {      
        return $this->serializer;
    }
}