<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="Location")
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\LocationRepository")
 */
class Location
{
    /**
     * @var integer
     *
     * @ORM\Column(name="LocationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LocationAbbreviation", type="string", length=15, nullable=true)
     */
    protected $locationAbbreviation;

    /**
     * @var string
     *
     * @ORM\Column(name="LocationDescription", type="string", length=100, nullable=true)
     */
    protected $locationDescription;

    /**
     * @var integer
     *
     * @ORM\Column(name="DisplayOrder", type="integer", nullable=true)
     */
    protected $displayOrder;

    /**
     * @var Job[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Job", mappedBy="location", fetch="EXTRA_LAZY")
     */
    protected $jobs;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLocationDescription() ?: '';
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
     * Set locationAbbreviation
     *
     * @param string $locationAbbreviation
     * @return Location
     */
    public function setLocationAbbreviation($locationAbbreviation)
    {
        $this->locationAbbreviation = $locationAbbreviation;

        return $this;
    }

    /**
     * Get locationAbbreviation
     *
     * @return string
     */
    public function getLocationAbbreviation()
    {
        return $this->locationAbbreviation;
    }

    /**
     * Set locationDescription
     *
     * @param string $locationDescription
     * @return Location
     */
    public function setLocationDescription($locationDescription)
    {
        $this->locationDescription = $locationDescription;

        return $this;
    }

    /**
     * Get locationDescription
     *
     * @return string
     */
    public function getLocationDescription()
    {
        return $this->locationDescription;
    }

    /**
     * Set displayOrder
     *
     * @param integer $displayOrder
     * @return Location
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

    /**
     * Add jobs
     *
     * @param Job $jobs
     * @return Location
     */
    public function addJob(Job $jobs)
    {
        $this->jobs[] = $jobs;

        return $this;
    }

    /**
     * Remove jobs
     *
     * @param Job $jobs
     */
    public function removeJob(Job $jobs)
    {
        $this->jobs->removeElement($jobs);
    }

    /**
     * Get jobs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJobs()
    {
        return $this->jobs;
    }
}
