<?php

namespace ItraBundle\Services;

use ItraBundle\Entity\Product;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id'                 => $object->getId(),
            'category'           => $object->getCategory()->getName(),
            'name'               => $object->getName(),
            'description'        => $object->getDescription(),
            'date_create'        => date('d.m.Y', $object->getDateCreate()->getTimestamp()),
            'date_update'        => $object->getDateUpdate() ? date('d-m-Y', $object->getDateUpdate()->getTimestamp()) : null,
            'is_active'          => $object->getIsActive(),
            'sku'                => $object->getSku(),
            'my_relation'        => array_map(
                function ($object){
                    return $this->normalizeRelation($object);
                },
                $object->getMyRelation()->getValues()
            ),
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Product;
    }

    public function normalizeRelation($object)
    {
        return [
            'id'                 => $object->getId(),
            'name'               => $object->getName(),
        ];
    }
}