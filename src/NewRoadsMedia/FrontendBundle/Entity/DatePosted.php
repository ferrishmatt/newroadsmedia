<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatePosted
 *
 * @ORM\Table(name="DatePosted")
 * @ORM\Entity
 */
class DatePosted
{
    /**
     * @var integer
     *
     * @ORM\Column(name="DatePostedID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="DatePosted", type="string", length=30, nullable=false)
     */
    protected $datePosted;

    /**
     * @var integer
     *
     * @ORM\Column(name="RangeStart", type="integer", nullable=false)
     */
    protected $rangeStart;

    /**
     * @var integer
     *
     * @ORM\Column(name="RangeEnd", type="integer", nullable=false)
     */
    protected $rangeEnd;

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
     * Set datePosted
     *
     * @param string $datePosted
     * @return DatePosted
     */
    public function setDatePosted($datePosted)
    {
        $this->datePosted = $datePosted;

        return $this;
    }

    /**
     * Get datePosted
     *
     * @return string 
     */
    public function getDatePosted()
    {
        return $this->datePosted;
    }

    /**
     * Set rangeStart
     *
     * @param integer $rangeStart
     * @return DatePosted
     */
    public function setRangeStart($rangeStart)
    {
        $this->rangeStart = $rangeStart;

        return $this;
    }

    /**
     * Get rangeStart
     *
     * @return integer 
     */
    public function getRangeStart()
    {
        return $this->rangeStart;
    }

    /**
     * Set rangeEnd
     *
     * @param integer $rangeEnd
     * @return DatePosted
     */
    public function setRangeEnd($rangeEnd)
    {
        $this->rangeEnd = $rangeEnd;

        return $this;
    }

    /**
     * Get rangeEnd
     *
     * @return integer 
     */
    public function getRangeEnd()
    {
        return $this->rangeEnd;
    }
}
