<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Position
 *
 * @ORM\Table(name="Position", indexes={
 *     @ORM\Index(columns={"Media"})
 * })
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\PositionRepository")
 */
class Position
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PositionID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Position", type="string", length=100, nullable=true)
     */
    protected $position;

    /**
     * @var string
     *
     * @ORM\Column(name="Media", type="string", length=100, nullable=true)
     */
    protected $media = 'All';

    /**
     * @var float
     *
     * @ORM\Column(name="DisplayOrder", type="float", nullable=true)
     */
    protected $displayOrder;

    /**
     * @var PositionGroup
     *
     * @ORM\ManyToOne(targetEntity="PositionGroup", cascade={"persist", "merge"}, inversedBy="positions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PositionGroupID", referencedColumnName="PositionGroupID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $positionGroup;

    public function __toString()
    {
        return $this->getPosition() ?: '';
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
     * Set position
     *
     * @param string $position
     * @return Position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set media
     *
     * @param string $media
     * @return Position
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set displayOrder
     *
     * @param float $displayOrder
     * @return Position
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return float
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * Set positionGroup
     *
     * @param PositionGroup $positionGroup
     * @return Position
     */
    public function setPositionGroup(PositionGroup $positionGroup = null)
    {
        $this->positionGroup = $positionGroup;

        return $this;
    }

    /**
     * Get positionGroup
     *
     * @return PositionGroup
     */
    public function getPositionGroup()
    {
        return $this->positionGroup;
    }
}
