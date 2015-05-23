<?php
namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Acme\StoreBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\User;

class EntityListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $mailer = $this->container->get('mailer');
        $templating = $this->container->get('templating');

        $entity = $args->getEntity();
        $em = $args->getEntityManager();
/*
        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof User) {

            $message = \Swift_Message::newInstance()
            ->setSubject('Success!')
            ->setFrom('milance.jovanovic92@gmail.com')
            ->setTo($entity->getEmail())
            ->setBody(
                $templating->render(
                    'AppBundle:Emails:user-created.html.twig',
                    array('user' => $entity)
                ),
                'text/html'
            )
            
            //  If you also want to include a plaintext version of the message
            // ->addPart(
            //     $this->renderView(
            //         'Emails/registration.txt.twig',
            //         array('name' => $name)
            //     ),
            //     'text/plain'
            // )
            
            ;
            $mailer->send($message); 

        }
        */
            
    }
}