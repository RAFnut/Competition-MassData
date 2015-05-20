<?php

namespace AppBundle\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Validator;

use AppBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/app/register", name="register", options={"expose"=true} )
     */
    public function registerAction()
    {
    	$person = new User();
        $json = $this->get("request")->getContent();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

		$person = $serializer->deserialize($json, 'AppBundle\Entity\User', 'json');

		$validator = $this->get('validator');
    	$errors = $validator->validate($person);

    	if (count($errors) > 0) {
        	return new JsonResponse($serializer->serialize($errors, 'json'));
    	} else {
    		$em = $this->getDoctrine()->getManager();
			$em->persist($person);
			$em->flush();
        	return new JsonResponse('OK');
    	}
    }
}