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

use AppBundle\Entity\Tweet;

use AppBundle\Controller\TwitterAPIExchange;

class UserSearchController extends Controller
{
    /**
     * @Route("/query/new/{{jobId}}", name="query_new", options={"expose": true})
     * @Route("/", name="profile", options={"expose": true})
     */
    public function searchQueryAction(Request $request, QueryJob $qj = null)
    {
        $query = new Query();
        $user = $this->get('security.context')->getToken()->getUser()->getUser();
        $form = $this->createSearchForm($query);
        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $query->setUser($user);
            $query->setQueryJob($qj);
            $query->setDate(new \DateTime('now'));

            $this->callRequest($query);

            $em->persist($query);
            $em->flush();

            return $this->redirect($this->generateUrl('info_query', array('id' -> $query->getId())));
        }

        return $this->render('AppBundle:User:search-queries.html.twig', array('form' => $form->createView(), 'premium' => $user->getPremium()));
    }

    private function createSearchForm(Query $query)
    {
        $form = $this->createForm(new QueryType(), $query, array(
            'action' => $this->generateUrl('query_new'),
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
        $queryJob = new QueryJob();
        $user = $this->get('security.context')->getToken()->getUser()->getUser();
        $form = $this->createJobForm($queryJob);
        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $queryJob->setUser($user);

            $query = new Query();
            $query->setQueryJob($queryJob);
            $query->setUser($user);
            $query->setDate(new \DateTime('now'));

            $query->setText( $form["text"]->getData() );
            $query->setLat( $form["lat"]->getData() );
            $query->setLng( $form["lng"]->getData() );
            $query->setRadius( $form["radius"]->getData() );

            $this->callRequest($query);

            $em->persist($queryJob);
            $em->flush();
        }

        return $this->render('AppBundle:User:search-jobs.html.twig', array('form' => $form->createView()));
    }

    private function createJobForm(QueryJob $query)
    {
        $form = $this->createForm(new QueryJobType(), $query, array(
            'action' => $this->generateUrl('job_new'),
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
        $firstPostfield = 
        'q' ."=". $query->getText()."&".
        'geocode' ."=". $query->getLat().",".
        $query->getLng().",".
        $query->getRadius()."km"."&".
        'count' ."=".'3'."&".
        'result_type' ."=".'recent'."&".
        'include_entities'."=".'true'
        ;
        $postfields = $firstPostfield;

        for ($i=0; $i<5; $i++){    
            $twitter = new TwitterAPIExchange($settings);  
            $titer = $twitter->setGetfield($postfields)->buildOauth($url, $requestMethod)->performRequest();

            $tilter = json_decode($titer, true);
            $max_id = "99999999999999999999";

            $statusi = $tilter["statuses"];
            foreach ($statusi as $status) {
                $tweet = new Tweet();
                $tweet->setText($status["text"]);
                $tweet->setLng($status["coordinates"]["coordinates"][0]);
                $tweet->setLat($status["coordinates"]["coordinates"][1]);
                $tweet->setQuery($query);
                $query->addTweet($tweet);
                if ($tweet->getLat() == null){
                    $tweet->setLat($query->getLat() + rand(1, 100)/10000);
                }
                if ($tweet->getLng() == null){
                    $tweet->setLng($query->getLng() + rand(1, 100)/10000);
                }
                
                $max_id = min($max_id, $status["id_str"]);
            } 
            $postfields = $firstPostfield . "&max_id=" .$max_id;
        }
        $this->semantic($query);
        return;
    }
    function do_post($url, $data)
    {
      $params = array('http' => array(
                  'method' => 'POST',
                  'content' => $data
                ));

      $ctx = stream_context_create($params);
      $fp = @fopen($url, 'rb', false, $ctx);
      if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
      }
      $response = @stream_get_contents($fp);
      if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
      }
      return $response;
    }

    private function semantic(Query $query){
        $em = $this->getDoctrine()->getManager();
        $em->persist($query);
        $em->flush();
        $em->refresh($query);
        $url = "https://api.repustate.com/v3/9aa2d832af4e0fec4c5da9a40dc5356c15c010c2/bulk-score.json?lang=en";
        $data = array();
        $twts = array();
        $i = 1;
        $n = 0;
        $scTotal = 0;

        foreach ($query->getTweet() as $t) {
            $n++;
            if($i % 500 == 0){
                $i = 0;
                $str = "";
                foreach ($data as $key => $value) {
                    if($i == 0){
                        $str = "text" . $key . "=" . urlencode($value);
                        $i++;
                    }else{
                        $str .= "&text" . $key . "=" . urlencode($value);
                    }
                }
                $response = $this->do_post($url, $str);
                $tilter = json_decode($response, true);
                $results = $tilter["results"];
                foreach ($results as $t ) {
                    $tid = substr($t['id'], 4);
                    $sc = $t['score'];
                    $scTotal += $sc;
                    $twts[$tid]->setImpression($sc);
                }
                $data = array();
            }

            $data[$t->getId()] = $t->getText();
            $twts[$t->getId()] = $t;
            $i++;
        }

        if($i > 0){
            $i = 0;
            $str = "";
            foreach ($data as $key => $value) {
                if($i == 0){
                    $str = "text" . $key . "=" . urlencode($value);
                    $i++;
                }else{
                    $str .= "&text" . $key . "=" . urlencode($value);
                }
            }
            $response = $this->do_post($url, $str);
            $tilter = json_decode($response, true);
            $results = $tilter["results"];
            foreach ($results as $t ) {
                $tid = substr($t['id'], 4);
                $sc = $t['score'];
                $scTotal += $sc;
                $twts[$tid]->setImpression($sc);
            }
            $data = array();
        }

        if($n == 0)
            $query->setImpression( 0 );
        else
            $query->setImpression( $scTotal/$n );

    }
}


//'q=obama&geocode=42.930719,-75.254394,100000km&count=100&result_type=recent&include_entities=1'
//'q=obama&geocode=42.930719,-75.254394,100000km&count=100&include_entities=1&result_type=recent'
