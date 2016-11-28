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

    public function paginatorCatalog(Request $request)
    {
        $category = $request->get('category');
        $orderBy = $request->get('order') ? : 'asc';

        $qb = $this->em
            //->getRepository('ItraBundle:Product')
            ->createQueryBuilder();

        if($category){
            $query = $qb
                ->select('a')
                ->from('ItraBundle:Product', 'a')
                ->where($qb->expr()->eq('a.category', $category))
                ->join('a.category', 'category')
                //->setParameter('category_id', $category)
                ->getQuery();
        } else {
            $query = $qb
                ->select('a')
                ->from('ItraBundle:Product', 'a')
                ->orderBy('a.category', $orderBy)
                ->getQuery();
        }

        return $query;
    }
}