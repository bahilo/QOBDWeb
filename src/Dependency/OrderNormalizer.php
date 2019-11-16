<?php

namespace App\Dependency;

use App\Entity\QuoteOrderDetail;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;


class OrderNormalizer extends SerializerAwareNormalizer implements NormalizerInterface{

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof QuoteOrderDetail;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return $object->map(function ($item) use ($format, $context) {
            return $this->serializer->normalize($item, $format, $context);
        })->getValues();
    }
}