<?php
namespace ItraBundle\Controller;

use ItraBundle\Entity\User;
use ItraBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $message = $this->isFlash();

        return $this->render('ItraBundle:Page:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'message'       => $message,
        ));
    }

    /**
     * @Route("/reset_password", name="reset_password")
     */
    public function resetPasswordAction(Request $request)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $token = md5($request->getContent());
            $email = $form['email']->getData();
            $username = $form['username']->getData();
            $user = $this->getDoctrine()->getRepository('ItraBundle:User')->saveToken($token, $username, $email);
            if(!$user) {
                $notice = 'Invalid Name or Email';
            } else {
                $url = 'http://127.0.0.1:8000/new_password'.'?token='.$token;
                $message = \Swift_Message::newInstance()
                    ->setSubject('Reset Password')
                    ->setFrom('itra@lol.by')
                    ->setTo($email)
                    ->setBody($this->renderView('ItraBundle:Mail:reset_password.txt.twig', array(
                        'resetUrl' => $url,
                        'username' => $username,
                    )));

                $this->get('mailer')->send($message);

                $notice = 'Message was sent to Email';
            }

            return $this->render('ItraBundle:Notice:send_link_reset_pass.html.twig', array(
                'message' => $notice,
            ));
        }

        return $this->render('ItraBundle:Page:reset_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/new_password", name="new_password")
     */
    public function newPassswordAction(Request $request)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        $token=$request->get('token');
        $em = $this->getDoctrine()->getRepository('ItraBundle:User');
        $user = $em->findOneBy(array('token' => $token));

        if(!$user){
            $this->setFlash('Invalid token');
            return $this->redirectToRoute('login');
        }

        if($form->isSubmitted() && $form->isValid()) {


            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em->saveNewPassword();
            $em->removeToken($user);

            $this->setFlash('Password has been changed');

            return $this->redirectToRoute('login');
        }

        return $this->render('ItraBundle:Page:new_password.html.twig', array(
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

    private function isFlash()
    {
        $session = new Session();
        if($session->getFlashBag()->has('Reset')){
            foreach ($session->getFlashBag()->get('Reset', array()) as $message) {
            }
        } else {
            $message = null;
        }

        return $message;
    }
}