<?php

namespace Acme\Serializer\Normalizer;

use ItraBundle\Entity\Product;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * User normalizer
 */
class UserNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id'         => $object->getId(),
            'name'       => $object->getName(),
            //'category'   => $object->getName(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Product;
    }
}