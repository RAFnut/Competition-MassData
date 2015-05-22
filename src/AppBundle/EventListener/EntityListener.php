<?php
namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Acme\StoreBundle\Entity\Product;

use AppBundle\Entity\User;

class EntityListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof User) {
            // ... do something with the Product
            $entity->setSalt("123431");
            $em->persist($entity);
            $em->flush();
        }
    }
}