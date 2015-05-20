<?php

namespace AppBundle\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Validator\Validator;

use AppBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/app/register", name="register")
     */
    public function registerAction()
    {
    	$person = new User();
        $json = $request->request->get('');
        $encoder = new JsonEncoder();
		$normalizer = new GetSetMethodNormalizer();

		$serializer = new Serializer(array($normalizer), array($encoder));

		$person = $serializer->deserialize($json, '', 'json');

		$validator = $this->get('validator');
    	$errors = $validator->validate($author);

    	if (count($errors) > 0) {
        	return new JsonResponse(print_r($errors, true));
    	} else {
    		$em = $this->getDoctrine()->getManager();
			$em->persist($person);
			$em->flush();
        	return new JsonResponse('OK');
    	}
    }
}