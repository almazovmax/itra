<?php
namespace ItraBundle\Controller;

use ItraBundle\Entity\User;
use ItraBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserController extends Controller
{
    private $limitPerPage = 10;

    /**
     * Lists all user entities.
     *
     * @Route("/user", name="user_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $pagination = $this->pagination($request);
        return $this->render('user/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Lists all product entities.
     *
     * @Route("/user/ajax", name="user_list_ajax")
     * @Method("GET")
     */
    public function ajaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $pagination = $this->pagination($request);
            $serializer = $this->get('app.my_serialize')->serializer();

            return new JsonResponse($serializer->serialize($pagination, 'json', array('groups' => array('user'))));
        }
    }

    public function pagination(Request $request)
    {
        $query = $this->get('pagination')->paginator($request, 'User');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $this->limitPerPage);

        return $pagination;
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/register", name="register")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRole('ROLE_USER');
            $user->setIsActive(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->setFlash('Registration completed successfully. You can log in using your username and password.');

            return $this->redirectToRoute('login');
        }

        return $this->render('ItraBundle:Page:register.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    private function setFlash($message)
    {
        $session = new Session();

        return $session
            ->getFlashBag()
            ->add('Reset', $message);
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/user/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('ItraBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/user/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush($user);
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
