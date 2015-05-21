<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserDefaultController extends Controller
{
    /**
     * @Route("/", name="user_home", options={"expose": true})
     */
    public function indexAction()
    {
        return $this->render('AppBundle:User:index.html.twig');
    }

    /**
     * @Route("/profile", defaults={"id" = -1})
     * @Route("/profile/{id}", name="user_profile", options={"expose": true})
     */
    public function profileAction($id)
    {
        if($id == -1){
            $usr = $this->get('security.context')->getToken()->getUser();
        }else{
            $usr = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->getOneById($id);
        }

        return $this->render('AppBundle:User:profile.html.twig', 
            array(
                'user' => $usr,
            ));
    }

}
