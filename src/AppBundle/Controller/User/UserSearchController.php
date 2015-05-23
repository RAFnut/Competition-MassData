<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Query;
use AppBundle\Form\QueryType;

use AppBundle\Entity\QueryJob;
use AppBundle\Entity\User;

class UserSearchontroller extends Controller
{
    /**
     * @Route("/query/search/{qj}", name="search_query", options={"expose": true})
     */
    public function searchQueryAction(Request $request, QueryJob $queryJob = NULL)
    {
    	$query = new Query();
        $user = $this->get('security.context')->getToken()->getUser()->getUser();
        $form = $this->createSearchForm($query);
        $form->handleRequest($request);

        if ($form->isValid()){
        	$em = $this->getDoctrine()->getManager();
        	$query->setQueryJob($queryJob);
        	$query->setUser($user);
        	$query->setDate(new \DateTime('now'));

        	callRequest($query);

            $em->persist($query);
            $em->flush();
        }

        return $this->render('search-query.html.twig',);
    }

    private function createSearchForm(Query $query)
    {
        $form = $this->createForm(new QueryType(), $query, array(
            'action' => $this->generateUrl('search_query'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Register!'));

        return $form;
    }

    private function callRequest(Query $query)
    {
    	
    }
}
