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
     * @Route("/", name="profile", options={"expose": true})
     */
    public function profileAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser()->getUser();
        return $this->render('AppBundle:User:profile.html.twig', array(
            'user' => $usr,
            'premium' => $usr->getPremium()
            ));
    }



}
