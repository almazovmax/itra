<?php
namespace ItraBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('ItraBundle:Product')->findBy(array(), array('id' => 'DESC'), 8);

        return $this->render('ItraBundle:Page:homepage.html.twig', array(
            'products' => $products,
        ));
    }
}