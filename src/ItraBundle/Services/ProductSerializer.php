<?php
namespace ItraBundle\Services;


use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ProductSerializer
{
    public function serializer()
    {
        $encoders = new JsonEncoder();
        $normalizer = new ProductNormalizer();

        $serializer = new Serializer(array($normalizer), array($encoders));

        return $serializer;
    }
}