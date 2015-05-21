<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;

class SecurityController extends Controller
{
	/*
	* @Route("/app/register", name="register")
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

        return $this->render('AppBundle::register.html.twig', array(
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
    * @Route("app/success", name="success" )
    */
    public function successAction($id)
    {
    	$user = $em->getRepository('AppBundle:User')->find($id);
    	return $this->render('AppBundle::success.html.twig', array(
            'user'   => $user,
        ));
    }
}