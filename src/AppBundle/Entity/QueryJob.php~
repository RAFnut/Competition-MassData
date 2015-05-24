<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QueryJob
 */
class QueryJob
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $start_date;

    /**
     * @var \DateTime
     */
    private $end_date;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $query;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->query = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set start_date
     *
     * @param \DateTime $startDate
     * @return QueryJob
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;

        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set end_date
     *
     * @param \DateTime $endDate
     * @return QueryJob
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;

        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Add query
     *
     * @param \AppBundle\Entity\Query $query
     * @return QueryJob
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return QueryJob
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
