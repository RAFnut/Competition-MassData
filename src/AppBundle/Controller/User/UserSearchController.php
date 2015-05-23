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
use AppBundle\Form\QueryJobType;

use AppBundle\Entity\QueryJob;
use AppBundle\Entity\User;

use AppBundle\Controller\TwitterAPIExchange;

class UserSearchController extends Controller
{
    /**
     * @Route("/query/search/{qj}", name="search_query", options={"expose": true})
     * @Route("/query/search/", name="search_query", options={"expose": true})
     * @Route("/profile", name="search_query", options={"expose": true})
     * @Route("/", name="profile", options={"expose": true})
     */
    public function searchQueryAction(Request $request, QueryJob $queryJob=null)
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

            $this->callRequest($query);

            $em->persist($query);
            $em->flush();
        }

        return $this->render('AppBundle:User:search-queries.html.twig', array('form' => $form->createView()));
    }

    private function createSearchForm(Query $query)
    {
        $form = $this->createForm(new QueryType(), $query, array(
            'action' => $this->generateUrl('search_query'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Search'));

        return $form;
    }

    /**
     * @Route("/job/new", name="job_new", options={"expose": true})
     */
    public function searchJobAction(Request $request)
    {
        $query = new QueryJob();
        $user = $this->get('security.context')->getToken()->getUser()->getUser();
        $form = $this->createJobForm($query);
        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($query);
            $em->flush();
        }

        return $this->render('AppBundle:User:search-jobs.html.twig', array('form' => $form->createView()));
    }

    private function createJobForm(QueryJob $query)
    {
        $form = $this->createForm(new QueryJobType(), $query, array(
            'action' => $this->generateUrl('search_query'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Search'));

        return $form;
    }


    private function callRequest(Query $query)
    {
        $usr = $this->get('security.context')->getToken()->getUser()->getUser();
        $settings = array(
            'oauth_access_token' => $usr->getToken(),
            'oauth_access_token_secret' => $usr->getSecret(),
            'consumer_key' => "O9lrX7A0iVOifwFhrtrfY40PF",
            'consumer_secret' => "LTnannEhUBCpKd84aZjRtsCmXBIZN6JnYUscp9FCYNU3n6m8Zc"
        );

        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $requestMethod = 'GET';
        $postfields = 
            'q' ."=". $query->getText()."&".
            'geocode' ."=". $query->getLat().",".
             $query->getLng().",".
             $query->getRadius()."km"."&".
            'count' ."=".'100'."&".
            'result_type' ."=".'recent'."&".
            'include_entities'."=".'true'
        ;
        
        $twitter = new TwitterAPIExchange($settings);
        echo $twitter
            ->setGetfield($postfields)
            ->buildOauth($url, $requestMethod)
            ->performRequest();
        return;
    }
}
