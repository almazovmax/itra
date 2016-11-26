<?php

namespace ItraBundle\Controller;

use ItraBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $pagination = $this->pagination($request);

        $serializer = $this->get('app.product_serialize')->serializer();

        $tree = $serializer->serialize($pagination, 'json');

        return $this->render('product/index.html.twig', array(
            'tree' => $tree,
            'pagination' => $pagination,
        ));
    }

    /**
     * Lists all product entities.
     *
     * @Route("/ajax", name="product_list_ajax")
     * @Method("GET")
     */
    public function ajaxAction(Request $request)
    {
        //var_dump($request); die;




        if($request->isXmlHttpRequest()) {
            $pagination = $this->paginationAjax($request);
            $serializer = $this->get('app.product_serialize')->serializer();

            return new JsonResponse($serializer->serialize($pagination, 'json'));
        }
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('ItraBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $product->getImage();
            $fileName = $this->get('app.images_uploader')->upload($file);
            $product->setImage('images/'.$fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush($product);

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('ItraBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $product->getImage();
            $fileName = $this->get('app.images_uploader')->upload($file);
            $product->setImage('images/'.$fileName);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush($product);
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function pagination(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em
            ->getRepository('ItraBundle:Product')
            ->createQueryBuilder('u')
            ->getQuery();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 6);

        return $pagination;
    }

    public function paginationAjax(Request $request)
    {

        $sortByField = $request->get('sortbyfield');
        $orderBy = $request->get('order');
        $filterByField = $request->get('filterbyfield');
        $pattern = $request->get('pattern');

        $em = $this->getDoctrine()->getManager();
        $qb = $em
            ->getRepository('ItraBundle:Product')
            ->createQueryBuilder('u');

        if($pattern){
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
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 6);

        return $pagination;
    }
}
