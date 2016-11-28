<?php
namespace ItraBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CatalogController extends Controller
{
    private $limitPerPage = 100;

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

    /**
     * @Route("/catalog/ajax", name="catalog_ajax")
     */
    public function ajaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $pagination = $this->pagination($request);

            return $this->render('ItraBundle:Page:catalog_ajax.html.twig', array(
                'pagination' => $pagination,
            ));
        }
    }

    public function pagination(Request $request)
    {
        $query = $this->get('pagination')->paginatorCatalog($request, 'Product');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $this->limitPerPage);


        return $pagination;
    }
}