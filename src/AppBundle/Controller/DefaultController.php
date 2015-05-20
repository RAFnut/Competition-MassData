<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/app/home", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
    /**
     * @Route("/app/show/{id}", name="ent_show")
     */
    public function entShowAction()
    {
        return $this->render('default/index.html.twig');
    }
}
