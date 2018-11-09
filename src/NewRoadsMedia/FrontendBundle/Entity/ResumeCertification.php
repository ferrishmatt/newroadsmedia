<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Resume
 *
 * @ORM\Table(name="ResumeCertification")
 * @ORM\Entity()
 */
class ResumeCertification
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ResumeCertificationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Resume
     *
     * @ORM\OneToOne(targetEntity="Resume", cascade={"persist"}, mappedBy="certification")
     */
    protected $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="Teaching", type="text", nullable=true)
     */
    protected $teaching;

    /**
     * @var string
     *
     * @ORM\Column(name="Subjects", type="text", nullable=true)
     */
    protected $subjects;

    /**
     * @var string
     *
     * @ORM\Column(name="SecurityClearances", type="text", nullable=true)
     */
    protected $securityClearances;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeReferences", type="text", nullable=true)
     */
    protected $references;

    /**
     * @var string
     *
     * @ORM\Column(name="Organization", type="string", length=100, nullable=true)
     */
    protected $organization;

    /**
     * @var Location[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="Location")
     * @ORM\JoinTable(name="ResumeCertificationToLocation"
     *     , joinColumns={@ORM\JoinColumn(name="ResumeCertificationID", referencedColumnName="ResumeCertificationID", onDelete="CASCADE")}
     *     , inverseJoinColumns={@ORM\JoinColumn(name="LocationID", referencedColumnName="LocationID", onDelete="CASCADE")}
     * )
     */
    protected $locations;

    /**
     * @var string
     *
     * @ORM\Column(name="Certifications", type="text", nullable=true)
     */
    protected $certifications;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->locations = new ArrayCollection();
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
     * Set teaching
     *
     * @param string $teaching
     * @return ResumeCertification
     */
    public function setTeaching($teaching)
    {
        $this->teaching = $teaching;

        return $this;
    }

    /**
     * Get teaching
     *
     * @return string 
     */
    public function getTeaching()
    {
        return $this->teaching;
    }

    /**
     * Set subjects
     *
     * @param string $subjects
     * @return ResumeCertification
     */
    public function setSubjects($subjects)
    {
        $this->subjects = $subjects;

        return $this;
    }

    /**
     * Get subjects
     *
     * @return string 
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Set resume
     *
     * @param Resume $resume
     * @return ResumeCertification
     */
    public function setResume(Resume $resume = null)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return Resume
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * @return string
     */
    public function getSecurityClearances()
    {
        return $this->securityClearances;
    }

    /**
     * @param string $securityClearances
     */
    public function setSecurityClearances($securityClearances)
    {
        $this->securityClearances = $securityClearances;
    }

    /**
     * @return string
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @param string $references
     */
    public function setReferences($references)
    {
        $this->references = $references;
    }

    /**
     * Set organization
     *
     * @param string $organization
     * @return ResumeCertification
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return string 
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Add locations
     *
     * @param Location $location
     * @return ResumeCertification
     */
    public function addLocation(Location $location)
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * Remove locations
     *
     * @param Location $location
     */
    public function removeLocation(Location $location)
    {
        $this->locations->removeElement($location);
    }

    /**
     * Get locations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @return string
     */
    public function getCertifications()
    {
        return $this->certifications;
    }

    /**
     * @param string $certifications
     */
    public function setCertifications($certifications)
    {
        $this->certifications = $certifications;
    }
}
