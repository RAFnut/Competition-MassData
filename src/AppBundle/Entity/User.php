<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 */
class User
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $premium;

    /**
     * @var string
     */
    private $tid;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $token;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $query;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $queryJob;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->query = new \Doctrine\Common\Collections\ArrayCollection();
        $this->queryJob = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setPremium(true);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set premium
     *
     * @param boolean $premium
     * @return User
     */
    public function setPremium($premium)
    {
        $this->premium = $premium;

        return $this;
    }

    /**
     * Get premium
     *
     * @return boolean 
     */
    public function getPremium()
    {
        return $this->premium;
    }

    /**
     * Set tid
     *
     * @param string $tid
     * @return User
     */
    public function setTid($tid)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * Get tid
     *
     * @return string 
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Set secret
     *
     * @param string $secret
     * @return User
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string 
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Add query
     *
     * @param \AppBundle\Entity\Query $query
     * @return User
     */
    public function addQuery(\AppBundle\Entity\Query $query)
    {
        $this->query[] = $query;

        return $this;
    }

    /**
     * Remove query
     *
     * @param \AppBundle\Entity\Query $query
     */
    public function removeQuery(\AppBundle\Entity\Query $query)
    {
        $this->query->removeElement($query);
    }

    /**
     * Get query
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Add queryJob
     *
     * @param \AppBundle\Entity\QueryJob $queryJob
     * @return User
     */
    public function addQueryJob(\AppBundle\Entity\QueryJob $queryJob)
    {
        $this->queryJob[] = $queryJob;

        return $this;
    }

    /**
     * Remove queryJob
     *
     * @param \AppBundle\Entity\QueryJob $queryJob
     */
    public function removeQueryJob(\AppBundle\Entity\QueryJob $queryJob)
    {
        $this->queryJob->removeElement($queryJob);
    }

    /**
     * Get queryJob
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQueryJob()
    {
        return $this->queryJob;
    }
}