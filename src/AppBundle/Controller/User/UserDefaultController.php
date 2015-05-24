<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Query;
use AppBundle\Entity\Tweet;

class UserDefaultController extends Controller
{
    /**
     * @Route("/marsupickumaterinuakoprobasovo", name="profile", options={"expose": true})
     */
    public function profileAction()
    {
        $usr = $this->get('security.context')->getToken()->getUser()->getUser();
        return $this->render('AppBundle:User:profile.html.twig', array(
            'user' => $usr,
            'premium' => $usr->getPremium()
            ));
    }
     /**
     * @Route("/info/query/{id}", name="info_query", options={"expose": true})
     */
     public function infoQueryAction($id)
     {
        $query = $this->getDoctrine()->getManager()->getRepository('AppBundle:Query')->findOneBy($id);
        return $this->render('AppBundle:User:info-query.html.twig', array(
            'tweets' => $query->getTweet(),
            'premium' => $usr->getPremium(),
            ));
     }   
}
