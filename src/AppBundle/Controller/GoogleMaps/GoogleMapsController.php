<?php

namespace AppBundle\Controller\GoogleMaps;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Security\Core\SecurityContext;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\GoogleMaps;

class GoogleMapsController extends Controller
{

    /**
     * @Route("/googleMaps/get", name="map_get", options={"expose": true})
     * @Method({"POST"})
     */
    public function getAllAction()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:GoogleMaps');
        $entities = $repo->findAll();
        foreach ($entities as $entity) {
            $niz[] = array(
                'title' => $entity->getTitle(), 
                'lat' => $entity->getLat(), 
                'lng' => $entity->getLng(), 
                'description' => $entity->getDescription(),
                );
        }

        return new JsonResponse($niz);
    }

    /**
     * @Route("/googleMaps/create", name="map_create", options={"expose": true})
     * @Method({"POST"})
     */
     public function saveAllAction()
     {
        $pin = new GoogleMaps();
        $jsonData = $this->get("request")->getContent();
        $jsonData = json_decode($jsonData, true);
        $pin->setTitle($jsonData['title']);
        $pin->setLng($jsonData['lng']);
        $pin->setLat($jsonData['lat']);
        $pin->setDescription($jsonData['description']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($pin);
        $em->flush();

        return new JsonResponse('Ok');
     }    
}