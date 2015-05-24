<?php
// src/Acme/PaymentBundle/Controller/PaymentController.php

namespace AppBundle\Controller\Payment;

use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;

class PaymentController extends Controller 
{
    /**
    * @Route("/preparePayment", name="payment_prepare" )
    */
    public function prepareAction(Request $request)
    {
        $paymentName = 'paypal2';
        $money = 129 * 100;

        $storage = $this->get('payum')->getStorage('AppBundle\Entity\Payment');

        $user = $this->get('security.context')->getToken()->getUser()->getUser();

        $order = $storage->create();
        $order->setNumber(uniqid());
        $order->setCurrencyCode('EUR');
        $order->setTotalAmount($money); // 1.23 EUR
        $order->setDescription('You are going to buy premium account.');
        $order->setClientId($user->getId());
        $order->setClientEmail('foo@example.com');

        $storage->update($order);

        $captureToken = $this->get('payum.security.token_factory')->createCaptureToken(
            $paymentName, 
            $order, 
            'payment_done' // the route to redirect after capture
        );

        return $this->redirect($captureToken->getTargetUrl());    
    }
    /**
    * @Route("/donePayment", name="payment_done" )
    */
    public function doneAction(Request $request)
    {
        $token = $this->get('payum.security.http_request_verifier')->verify($request);

        $gateway = $this->get('payum')->getGateway($token->getGatewayName());

        // you can invalidate the token. The url could not be requested any more.
        // $this->get('payum.security.http_request_verifier')->invalidate($token);

        // Once you have token you can get the model from the storage directly. 
        //$identity = $token->getDetails();
        //$payment = $payum->getStorage($identity->getClass())->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();

        // you have order and payment status 
        // so you can do whatever you want for example you can just print status and payment details.
        if ($status->getValue() === "pending" || $status->getValue() === "success" || $status->getValue() === "captured"){
            $em = $this->getDoctrine()->getManager();
            $usr = $this->get('security.context')->getToken()->getUser()->getUser();
            $usr->setPremium(true);
            $em->persist($usr);
            $em->flush();
            return $this->redirect($this->generateUrl('profile'));
        }else{
            //var_dump($status->getValue());
            return $this->redirect($this->generateUrl('get_premium'));
        }
    }
}