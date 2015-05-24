<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tweet
 */
class Tweet
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
     * @var float
     */
    private $lng;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $impression;

    /**
     * @var \AppBundle\Entity\Query
     */
    private $query;


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
     * @return Tweet
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
     * Set lng
     *
     * @param float $lng
     * @return Tweet
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Tweet
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set impression
     *
     * @param float $impression
     * @return Tweet
     */
    public function setImpression($impression)
    {
        $this->impression = $impression;

        return $this;
    }

    /**
     * Get impression
     *
     * @return float 
     */
    public function getImpression()
    {
        return $this->impression;
    }

    /**
     * Set query
     *
     * @param \AppBundle\Entity\Query $query
     * @return Tweet
     */
    public function setQuery(\AppBundle\Entity\Query $query = null)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query
     *
     * @return \AppBundle\Entity\Query 
     */
    public function getQuery()
    {
        return $this->query;
    }
}
