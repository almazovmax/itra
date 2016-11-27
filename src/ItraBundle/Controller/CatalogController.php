<?php
namespace ItraBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends Controller
{
    private $limitPerPage = 9;

    /**
     * @Route("/catalog", name="catalog")
     */
    public function indexAction(Request $request)
    {
        $pagination = $this->pagination($request);
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('ItraBundle:Category')->findAll();

        return $this->render('ItraBundle:Page:catalog.html.twig', array(
            'pagination' => $pagination,
            'categories' => $categories,
        ));
    }

    public function pagination(Request $request)
    {
        $query = $this->get('pagination')->paginatorCatalog($request, 'Product');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $this->limitPerPage);

        return $pagination;
    }
}