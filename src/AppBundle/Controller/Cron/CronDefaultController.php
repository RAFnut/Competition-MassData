<?php

namespace AppBundle\Controller\Cron;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Query;
use AppBundle\Entity\QueryJob;
use AppBundle\Entity\User;
use AppBundle\Entity\Tweet;

use AppBundle\Controller\TwitterAPIExchange;

class CronDefaultController extends Controller
{

    /**
    * @Route("/", name="cron_home" )
    */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('AppBundle:QueryJob')->createQueryBuilder('q');
        $qb->andWhere('q.end_date >= :now')
            ->setParameter('now', new \DateTime());

        $qjobs = $qb->getQuery()->getResult();

        foreach ($qjobs as $j) {
           $qu = $j->getQuery()->last();
           var_dump($qu->getDate());
           var_dump (new \DateTime("16 minutes ago"));
           if($qu->getDate() < new \DateTime("10 minutes ago") ){
            var_dump($qu->getText());
            $this->queryNew($j, $qu);
           }
        }

        return $this->render(
            'AppBundle:Cron:index.html.twig',
            array(
                )
        );
    }

    public function queryNew(QueryJob $qj, Query $q)
    {
        $query = new Query();
        $user = $qj->getUser();
        
        $em = $this->getDoctrine()->getManager();
        $query->setUser($user);
        $query->setQueryJob($qj);
        $query->setDate(new \DateTime('now'));

        $query->setText( $q->getText() );
        $query->setLat( $q->getLat() );
        $query->setLng( $q->getLng() );
        $query->setRadius( $q->getRadius() );

        $this->callRequest($query);
        $em->persist($query);
        $em->flush();
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
        'result_type' ."=".'recent'."&".
        'include_entities'."=".'true'
        ;
        $postfields = $firstPostfield;

        for ($i=0; $i<4; $i++){    
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
        
        $twts = array();
        $n = 0;
        $scTotal = 0;

        $data = array();
        foreach ($query->getTweet() as $t) {
            $n++;
            $data[$t->getId()] = $t->getText();
            $twts[] = $t;
            if($n % 25 == 0){
                $result = shell_exec("C:\Python27\python C:\wamp\www\\rafaton\src\AppBundle\Controller\User\app.py " . base64_encode(json_encode($data)));
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
                $result = shell_exec("C:\Python27\python C:\wamp\www\\rafaton\src\AppBundle\Controller\User\app.py " . base64_encode(json_encode($data)));
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