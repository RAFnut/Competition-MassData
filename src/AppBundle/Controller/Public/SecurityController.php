<?php

namespace AppBundle\Controller\Public;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;

class SecurityController extends Controller
{
	/*
	* @Route("/register", name="register")
	*/
	public function registerAction(Request $request)
    {
		$user = new User();      

        $form = $this->createCreateForm($poll);
        $form->handleRequest($request); 

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('success', array('id' => $poll->getId())));
        }

        return $this->render('AppBundle:Public:register.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    private function createCreateForm(User $user)
    {
        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl(''),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Register!'));

        return $form;
    }
    /*
    * @Route("/success", name="success" )
    */
    public function successAction($id)
    {
    	$user = $em->getRepository('AppBundle:User')->find($id);
    	return $this->render('AppBundle:Public:success.html.twig', array(
            'user'   => $user,
        ));
    }

    /*
    * @Route("/login", name="userLogin" )
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
            'AppBundle:Public:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
}