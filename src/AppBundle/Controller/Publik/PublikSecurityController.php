<?php

namespace AppBundle\Controller\Publik;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class PublikSecurityController extends Controller
{
    /**
    * @Route("/register", name="register")
    */
    public function registerAction(Request $request)
    {
        $user = new User();      

        $form = $this->createCreateForm($user);
        $form->handleRequest($request); 

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('user_login'));
        }

        return $this->render('AppBundle:Publik:register.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    private function createCreateForm(User $user)
    {
        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('register'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Register!'));

        return $form;
    }

    /**
    * @Route("/app/login", name="user_login" )
    */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'AppBundle:Publik:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
    /**
    * @Route("/", name="home" )
    */
    public function indexAction()
    {


        return $this->render(
            'AppBundle:Publik:index.html.twig'
        );
    }
}