<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="user_home", options={"expose": true})
     */
    public function indexAction()
    {
        return $this->render('AppBundle:User:index.html.twig');
    }

    /**
     * @Route("/help", name="fica_help", options={"expose": true})
     * @Method({"GET", "POST"})
     */  
    public function helpAction(Request $request)
    {
    	return new Response('OK');
    }  
}
