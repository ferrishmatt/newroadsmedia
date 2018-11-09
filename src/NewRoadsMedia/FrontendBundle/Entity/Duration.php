<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Duration
 *
 * @ORM\Table(name="Duration")
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\DurationRepository")
 */
class Duration
{
    /**
     * @var integer
     *
     * @ORM\Column(name="DurationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Duration", type="string", length=100, nullable=true)
     */
    protected $Duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="DisplayOrder", type="integer", nullable=true)
     */
    protected $displayOrder;

    public function __toString()
    {
        return $this->getDuration() ?: '';
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
     * Set Duration
     *
     * @param string $Duration
     * @return Duration
     */
    public function setDuration($Duration)
    {
        $this->Duration = $Duration;

        return $this;
    }

    /**
     * Get Duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->Duration;
    }

    /**
     * Set displayOrder
     *
     * @param integer $displayOrder
     * @return Duration
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return integer
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }
}