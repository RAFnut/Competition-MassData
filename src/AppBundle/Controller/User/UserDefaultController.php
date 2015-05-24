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
     * @Route("/info/query/{id}", name="info_query", options={"expose": true})
     */
     public function infoQueryAction($id)
     {
        $user = $this->get('security.context')->getToken()->getUser()->getUser();
        $query = $this->getDoctrine()->getManager()->getRepository('AppBundle:Query')->findOneBy(array('id'=>$id));
        return $this->render('AppBundle:User:info-query.html.twig', array(
            'query' => $query,
            'premium' => $user->getPremium(),
            ));
     }   

     /**
     * @Route("/info/query/{id}", name="info_query", options={"expose": true})
     */
     public function infoQueryAction($id)
     {
        $user = $this->get('security.context')->getToken()->getUser()->getUser();
        $query = $this->getDoctrine()->getManager()->getRepository('AppBundle:Query')->findOneBy(array('id'=>$id));
        return $this->render('AppBundle:User:info-query.html.twig', array(
            'query' => $query,
            'premium' => $user->getPremium(),
            ));
     }
}
