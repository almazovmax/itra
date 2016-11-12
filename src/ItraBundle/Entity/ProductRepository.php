<?php
namespace ItraBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

class ProductRepository extends EntityRepository
{
    public function findAll()
    {
        return parent::findAll();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $em = $this->createQueryBuilder('u');
        $product = $em
            ->select('u')
            ->where($em -> expr() -> in('u.id', $criteria['id']))
            ->orderBy('u.id', '')
            ->getQuery();

        return $product->getResult();
    }
}