<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserPremiumController extends Controller
{
    /**
     * @Route("/premium/get", name="get_premium", options={"expose": true})
     */
    public function getPremiumAction()
    {
        return $this->redirect($this->generateUrl('payment_prepare'));
    }

     /**
     * @Route("/premium/listJobs", name="jobs_list", options={"expose": true})
     */
     public function listJobsAction()
     {
        $usr = $this->get('security.context')->getToken()->getUser();
        $lista = $usr->getQueryJob();
        return $this->render('AppBundle:User:jobs-list.html.twig', 
            array(
                'queries' => $lista,
            ));
     }   

     /**
     * @Route("/premium/history", name="queries_list", options={"expose": true})
     */
     public function listJobsAction()
     {
        $usr = $this->get('security.context')->getToken()->getUser();
        $lista = $usr->getQuery();

        foreach($lista as $query){
            if ($query->getQueryJob() == NULL){
                $niz[] = $query;            }
        }

        return $this->render('AppBundle:User:queries-list.html.twig', 
            array(
                'queries' => $niz,
            ));
     }        

}
