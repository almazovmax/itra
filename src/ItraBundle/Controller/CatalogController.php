<?php
namespace ItraBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends Controller
{
    /**
     * @Route("/catalog", name="catalog")
     */
    public function indexAction(Request $request)
    {
        $pagination = $this->pagination($request);

        return $this->render('ItraBundle:Page:catalog.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    public function pagination(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em
            ->getRepository('ItraBundle:Product')
            ->createQueryBuilder('u')
            ->getQuery();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 3);

        return $pagination;
    }
}