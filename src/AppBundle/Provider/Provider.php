<?php
namespace AppBundle\Provider;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use AppBundle\Entity\User;
use AppBundle\Provider\OAuthUser;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use AppBundle\Provider\AdminChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;


/***
 * Class Provider
 * @package AppBundle\Provider
 *
 *
 */
class Provider extends OAuthUserProvider
{
    /***
     * @var Session
     */
    protected $session;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Doctrine
     */
    protected $doctrine;

    /***
     * @param Session $session
     * @param Doctrine $doctrine
     * @param AdminChecker $adminChecker
     * @param RequestStack $requestStack
     */
    public function __construct(Session $session, Doctrine $doctrine, AdminChecker $adminChecker, RequestStack $requestStack) {
        $this->session = $session;
        $this->doctrine = $doctrine;
        $this->adminChecker = $adminChecker;
        $this->request   = $requestStack->getCurrentRequest();
    }

    private function getUserById($id) {
        return $this->doctrine
            ->getRepository('AppBundle\Entity\User')
            ->findOneById($id);
    }

    /***
     * @param string $id
     * @return OAuthUser|\HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser|\Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($id)
    {
        $user = $this->getUserById($id);
        return new OAuthUser($id, $user, $this->adminChecker->check($user));
    }

    /***
     * @param UserResponseInterface $response
     * @return OAuthUser|\HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser|\Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $uri = $this->request->getUri();

        $isFacebook = false;
        $isGoogle = false;
        $isLive = false;
        $isTwitter = false;

        if(strpos($uri, '/login_google') !== false)
            $isGoogle = true;
        if(strpos($uri, '/login_facebook') !== false)
            $isFacebook = true;
        if(strpos($uri, '/login_live') !== false)
            $isLive = true;
        if(strpos($uri, '/login/check-twitter') !== false)
            $isTwitter = true;

        if($isGoogle === false && $isFacebook === false && $isLive === false && $isTwitter === false)
            throw new \Exception("Invalid social network login attempt");

        $social = "";
        if($isGoogle)
            $social = "google";
        if($isFacebook)
            $social = "facebook";
        if($isLive)
            $social = "live";
        if($isTwitter)
            $social = "twitter";

        //check to see if the user is logged in and if she is logged in with the same social network
        $isLoggedInAlready = $this->session->has('user');
        $isLoggedInAlreadyId = $this->session->get('user')['id'];
        if($isLoggedInAlready && $this->session->get('user')['social'] == $social)
            return $this->loadUserByUsername($isLoggedInAlreadyId);

        $social_id = $response->getUsername();
        $nickname = $response->getNickname();
        $realName = $response->getRealName();
        $email = $response->getEmail();
        $avatar   = $response->getProfilePicture();

        //set data in session. upon logging out we just erase the whole array.
        $sessionData = array();
        $sessionData['social_id'] = $social_id;
        $sessionData['nickname'] = $nickname;
        $sessionData['realName'] = $realName;
        $sessionData['email'] = $email;
        $sessionData['avatar'] = $avatar;
        $sessionData['social'] = $social;

        $user = null;
        if($isLoggedInAlready)
            $user = $this->doctrine
                ->getRepository('AppBundle\Entity\User')
                ->findOneById($isLoggedInAlreadyId);
        else if($isFacebook)
            $user = $this->doctrine
                ->getRepository('AppBundle\Entity\User')
                ->findOneByFid($social_id);
        else if($isGoogle)
            $user = $this->doctrine
                ->getRepository('AppBundle\Entity\User')
                ->findOneByGid($social_id);
        else if($isLive)
            $user = $this->doctrine
                ->getRepository('AppBundle\Entity\User')
                ->findOneByLid($social_id);
        else if($isTwitter)
            $user = $this->doctrine
                ->getRepository('AppBundle\Entity\User')
                ->findOneByTid($social_id);

        if ($user == null) {
            $user = new User();

            $user->setSecret($response->getTokenSecret());
            $user->setToken($response->getAccessToken());        
            //change these only the user hasn't been registered before.

        }


        if($isFacebook)
            $user->setFid($social_id);
        else if($isGoogle)
            $user->setGid($social_id);
        else if($isLive)
            $user->setLid($social_id);
        else if($isTwitter)
            $user->setTid($social_id);


        //$user->setLastLogin(new \DateTime('now'));
        //$user->setSocial($social);

        // SET E-MAIL
        //if all emails are empty, set the first one to this one.
        

        //save all changes
        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();

        $id = $user->getId();

        //set id
        $sessionData['id'] = $id;
        $sessionData['is_admin'] = $this->adminChecker->check($user);

        $this->session->set('user', $sessionData);
        return $this->loadUserByUsername($user->getId());
    }

    /***
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'AppBundle\\Provider\\OAuthUser';
    }
}