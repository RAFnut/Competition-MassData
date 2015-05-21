<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/app")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="user_home", options={"expose": true})
     */
    public function indexAction()
    {
        return $this->render('AppBundle:User:index.html.twig');
    }
}
