<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Query
 */
class Query
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $lng;

    /**
     * @var string
     */
    private $lat;

    /**
     * @var string
     */
    private $radius;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tweet;

    /**
     * @var \AppBundle\QueryJob
     */
    private $queryJob;

    /**
     * @var \AppBundle\SampleEntity
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tweet = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set text
     *
     * @param string $text
     * @return Query
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Query
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return Query
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return Query
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set radius
     *
     * @param string $radius
     * @return Query
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * Get radius
     *
     * @return string 
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * Add tweet
     *
     * @param \AppBundle\Tweet $tweet
     * @return Query
     */
    public function addTweet(\AppBundle\Tweet $tweet)
    {
        $this->tweet[] = $tweet;

        return $this;
    }

    /**
     * Remove tweet
     *
     * @param \AppBundle\Tweet $tweet
     */
    public function removeTweet(\AppBundle\Tweet $tweet)
    {
        $this->tweet->removeElement($tweet);
    }

    /**
     * Get tweet
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTweet()
    {
        return $this->tweet;
    }

    /**
     * Set queryJob
     *
     * @param \AppBundle\QueryJob $queryJob
     * @return Query
     */
    public function setQueryJob(\AppBundle\QueryJob $queryJob = null)
    {
        $this->queryJob = $queryJob;

        return $this;
    }

    /**
     * Get queryJob
     *
     * @return \AppBundle\QueryJob 
     */
    public function getQueryJob()
    {
        return $this->queryJob;
    }

    /**
     * Set user
     *
     * @param \AppBundle\SampleEntity $user
     * @return Query
     */
    public function setUser(\AppBundle\SampleEntity $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\SampleEntity 
     */
    public function getUser()
    {
        return $this->user;
    }
}
