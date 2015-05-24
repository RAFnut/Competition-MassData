<?php
namespace AppBundle\Provider;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

/**
 * Class AdminChecker
 * @package AppBundle\Provider
 */
class AdminChecker{

    /**
     * @var Doctrine
     */
    protected $doctrine;

    /**
     * @var array
     */
    protected $admins;

    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->admins = array();
    }

    /***
     * @param User $user
     * @return bool
     */
    public function check($user)
    {
        if($user == null){
            $this->container->get('security.context')->setToken(NULL);
            $this->container->get('session')->remove('user');

            return $this->redirectToRoute('home');
        }
        $isIt = false;

        $isIt = $isIt || (isset($this->admins['facebook']) && in_array($user->getFid(), $this->admins['facebook']));
        $isIt = $isIt || (isset($this->admins['google']) && in_array($user->getGid(), $this->admins['google']));
        $isIt = $isIt || (isset($this->admins['live']) && in_array($user->getLid(), $this->admins['live']));
        $isIt = $isIt || (isset($this->admins['twitter']) && in_array($user->getTid(), $this->admins['twitter']));

        return $isIt;
    }
}