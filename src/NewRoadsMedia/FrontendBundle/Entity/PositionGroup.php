<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Position
 *
 * @ORM\Table(name="PositionGroup")
 * @ORM\Entity
 */
class PositionGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PositionGroupID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var ArrayCollection|Position[]
     *
     * @ORM\OneToMany(targetEntity="Position", mappedBy="positionGroup", cascade={"persist", "merge"})
     * @ORM\OrderBy({"displayOrder" = "ASC"})
     */
    protected $positions;

    /**
     * @var string
     *
     * @ORM\Column(name="PositionGroup", type="string", length=100, nullable=false)
     */
    protected $positionGroup;

    /**
     * @var float
     *
     * @ORM\Column(name="DisplayOrder", type="float", nullable=true)
     */
    protected $displayOrder;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getPositionGroup() ?: '';
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
     * Set positionGroup
     *
     * @param string $positionGroup
     * @return PositionGroup
     */
    public function setPositionGroup($positionGroup)
    {
        $this->positionGroup = $positionGroup;

        return $this;
    }

    /**
     * Get positionGroup
     *
     * @return string 
     */
    public function getPositionGroup()
    {
        return $this->positionGroup;
    }

    /**
     * Add positions
     *
     * @param Position $positions
     * @return PositionGroup
     */
    public function addPosition(Position $positions)
    {
        $this->positions[] = $positions;

        return $this;
    }

    /**
     * Remove positions
     *
     * @param Position $positions
     */
    public function removePosition(Position $positions)
    {
        $this->positions->removeElement($positions);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @return float
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * @param float $displayOrder
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;
    }
}
