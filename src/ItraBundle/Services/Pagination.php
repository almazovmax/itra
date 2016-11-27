<?php
namespace ItraBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class Pagination
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function paginator(Request $request, $entity)
    {
        $sortByField = $request->get('sortbyfield') ? : 'id';
        $orderBy = $request->get('order') ? : 'asc';
        $filterByField = $request->get('filterbyfield');
        $pattern = $request->get('pattern');

        $qb = $this->em
            ->getRepository('ItraBundle:'.$entity)
            ->createQueryBuilder('u');

        if($pattern !== '' && $pattern !== null){
            $query = $qb
                ->where($qb->expr()->like('u.'.$filterByField, ':pattern'))
                ->setParameter('pattern', '%'.$pattern.'%')
                ->orderBy('u.'.$sortByField, $orderBy)
                ->getQuery();
        } else {
            $query = $qb
                ->orderBy('u.'.$sortByField, $orderBy)
                ->getQuery();
        }

        return $query;
    }

    public function paginatorCatalog(Request $request, $entity)
    {
        $category = $request->get('category');
        $orderBy = $request->get('order') ? : 'asc';

        $qb = $this->em
            ->getRepository('ItraBundle:'.$entity)
            ->createQueryBuilder('u');

        if($category){
            $query = $qb
                ->where('category = :category')
                ->setParameter('category', $category)
                ->getQuery();
        } else {
            $query = $qb
                ->orderBy('u.category', $orderBy)
                ->getQuery();
        }

        return $query;
    }
}