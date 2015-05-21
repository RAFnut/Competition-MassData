<?php

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserUpdateController extends Controller
{
    /**
     * @Route("/api/changePhoto", name="user_photo_change", options={"expose": true})
     * @Method({"POST"})
     */
    public function changePhotoAction()
    {
        $photo = $this->get('request')->request->get('data');
        $usr = $this->get('security.context')->getToken()->getUser();
        $usr->setPhoto($photo);

        return JsonResponse('Ok');
    }

}
