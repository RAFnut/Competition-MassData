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

            return $this->redirect($this->generateUrl('info_query', array('id' => $query->getId())));
        }

        return $this->render('AppBundle:User:search-queries.html.twig', array('form' => $form->createView(), 'premium' => $user->getPremium()));
    } 

    private function createSearchForm(Query $query)
    {
        $form = $this->createForm(new QueryType(), $query, array(
            'action' => $this->generateUrl('profile'),
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
        $queryJob->setStartDate(new \DateTime('now'));
        $queryJob->setEndDate(new \DateTime('now'));
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
            return $this->redirect($this->generateUrl('info_query', array('id' => $query ->getId())));
        }

        return $this->render('AppBundle:User:search-jobs.html.twig', array(
            'form' => $form->createView(),
            ));
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

    public function callRequest(Query $query)
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
        'count' ."=".'100'."&".
        'result_type' ."=".'mixed'."&".
        'include_entities'."=".'true'
        ;
        $postfields = $firstPostfield;

        $num = $query->getRadius() * 2 / 100;
        if ($num == 0) $num=1;
        if ($num > 50) $num = 40;

        for ($i=0; $i<$num; $i++){    
            $twitter = new TwitterAPIExchange($settings);  
            $titer = $twitter->setGetfield($postfields)->buildOauth($url, $requestMethod)->performRequest();

            $tilter = json_decode($titer, true);

            $max_id = "99999999999999999999";

            $statusi = $tilter["statuses"];
            foreach ($statusi as $status) {
                $tweet = new Tweet();
                $tweet->setText($status["text"]);

                $tweet->setLat($status["coordinates"]["coordinates"][1]);
                if ($tweet->getLat() == null){
                    $tweet->setLat($query->getLat() + rand(1, 100)/10000);
                }

                $tweet->setLng($status["coordinates"]["coordinates"][0]);
                if ($tweet->getLng() == null){
                    $tweet->setLng($query->getLng() + rand(1, 100)/10000);
                }
                if ($status["user"]["name"] === null){
                    $tweet->setAuthor("Unknown user");
                }else{
                    $tweet->setAuthor($status["user"]["name"]);
                }

                $tweet->setFavoriteCount($status["favorite_count"]);

                $tweet->setRetweetCount($status["retweet_count"]);

                $tweet->setTwitterId($status["id_str"]);

                $tweet->setQuery($query);
                $query->addTweet($tweet);              
 
                $max_id = min($max_id, $status["id_str"]);
            } 
            if(count($statusi) < 100){
                break;
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
        throw new \Exception("Problem with $url, $ctx");
      }
      $response = @stream_get_contents($fp);
      if ($response === false) {
        throw new \Exception("Problem reading data from $url, $ctx");
      }
      return $response;
    }

    private function semantic(Query $query){

        $em = $this->getDoctrine()->getManager();
        $em->persist($query);
        $em->flush();
        $em->refresh($query);
        $url = "https://apiv2.indico.io/sentiment?key=359c5aa6ad9f97ae4e6458eeddd1a675";
        $twts = array();
        $n = 0;
        $scTotal = 0;
	$variable = __DIR__."/app.py ";
        $data = array();
        foreach ($query->getTweet() as $t) {
            $n++;
            $data[$t->getId()] = $t->getText();
            $twts[] = $t;
            if($n % 25 == 0){
                $result = shell_exec("python ". $variable . base64_encode(json_encode($data)));
                // var_dump($result);
                $resultData = json_decode($result, true);
                foreach ($twts as $t) {
                    $sc = $resultData[$t->getId()];
                    $scTotal += $sc;
                    $t->setImpression($sc);
                }
                $data = array();
                $twts = array();
            }
        }
            if($n % 25 != 0){
                $result = shell_exec("python ". $variable . base64_encode(json_encode($data)));
                $resultData = json_decode($result, true);
                foreach ($twts as $t) {
                    $sc = $resultData[$t->getId()];
                    $scTotal += $sc;
                    $t->setImpression($sc);
                }
                $data = array();
                $twts = array();
            }

        if($n == 0)
            $query->setImpression( 0 );
        else
            $query->setImpression( $scTotal/$n );
        
    }
}