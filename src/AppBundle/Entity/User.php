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
    private $twitter;

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
     * Set twitter
     *
     * @param string $twitter
     * @return User
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Add query
     *
     * @param \AppBundle\Query $query
     * @return User
     */
    public function addQuery(\AppBundle\Query $query)
    {
        $this->query[] = $query;

        return $this;
    }

    /**
     * Remove query
     *
     * @param \AppBundle\Query $query
     */
    public function removeQuery(\AppBundle\Query $query)
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
     * @param \AppBundle\QueryJob $queryJob
     * @return User
     */
    public function addQueryJob(\AppBundle\QueryJob $queryJob)
    {
        $this->queryJob[] = $queryJob;

        return $this;
    }

    /**
     * Remove queryJob
     *
     * @param \AppBundle\QueryJob $queryJob
     */
    public function removeQueryJob(\AppBundle\QueryJob $queryJob)
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
    /**
     * @var string
     */
    private $tid;


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
}
