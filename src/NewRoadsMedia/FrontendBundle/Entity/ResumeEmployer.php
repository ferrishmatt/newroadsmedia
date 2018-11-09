<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ResumeEmployer")
 * @ORM\Entity()
 */
class ResumeEmployer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ResumeEmployerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Resume
     *
     * @ORM\ManyToOne(targetEntity="Resume", cascade={"persist"}, inversedBy="employers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", nullable=false, onDelete="CASCADE")
     * })
     */
    protected $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="Company", type="string", length=300, nullable=false)
     */
    protected $company;

    /**
     * @var string
     *
     * @ORM\Column(name="Address", type="string", length=250, nullable=false)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=30, nullable=false)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=300, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Designation", type="string", length=100, nullable=false)
     */
    protected $designation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateFrom", type="datetime", nullable=false)
     */
    protected $dateFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateTo", type="datetime", nullable=false)
     */
    protected $dateTo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="IsCurrent", type="boolean", nullable=true)
     */
    protected $isCurrent;

    /**
     * @var boolean
     *
     * @ORM\Column(name="IsContactable", type="boolean", nullable=true)
     */
    protected $isContactable;

    /**
     * @var string
     *
     * @ORM\Column(name="JobDetails", type="text", nullable=false)
     */
    protected $jobDetails;

    /**
     * @var string
     *
     * @ORM\Column(name="Type", type="string", length=30, nullable=true)
     */
    protected $type;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|Specialty[]
     *
     * @ORM\OneToMany(targetEntity="ResumeEmployerSpecialty", mappedBy="resumeEmployer", cascade={"all"}, orphanRemoval=true)
     */
    protected $specialties;

    public function __construct()
    {
        $this->specialties = new ArrayCollection();
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
     * Set company
     *
     * @param string $company
     * @return ResumeEmployer
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return ResumeEmployer
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return ResumeEmployer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set dateFrom
     *
     * @param \DateTime $dateFrom
     * @return ResumeEmployer
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * Get dateFrom
     *
     * @return \DateTime 
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * Set dateTo
     *
     * @param \DateTime $dateTo
     * @return ResumeEmployer
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * Get dateTo
     *
     * @return \DateTime 
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * Set isCurrent
     *
     * @param boolean $isCurrent
     * @return ResumeEmployer
     */
    public function setIsCurrent($isCurrent)
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    /**
     * Get isCurrent
     *
     * @return boolean 
     */
    public function getIsCurrent()
    {
        return $this->isCurrent;
    }

    /**
     * Set isContactable
     *
     * @param boolean $isContactable
     * @return ResumeEmployer
     */
    public function setIsContactable($isContactable)
    {
        $this->isContactable = $isContactable;

        return $this;
    }

    /**
     * Get isContactable
     *
     * @return boolean 
     */
    public function getIsContactable()
    {
        return $this->isContactable;
    }

    /**
     * Set resume
     *
     * @param Resume $resume
     * @return ResumeEmployer
     */
    public function setResume(Resume $resume)
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
     * Add specialties
     *
     * @param ResumeEmployerSpecialty $specialty
     * @return ResumeEmployer
     */
    public function addSpecialty(ResumeEmployerSpecialty $specialty)
    {
        $specialty->setResumeEmployer($this);
        $this->specialties[] = $specialty;

        return $this;
    }

    /**
     * Remove specialties
     *
     * @param ResumeEmployerSpecialty $specialty
     */
    public function removeSpecialty(ResumeEmployerSpecialty $specialty)
    {
        $this->specialties->removeElement($specialty);
    }

    /**
     * Get specialties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSpecialties()
    {
        return $this->specialties;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getJobDetails()
    {
        return $this->jobDetails;
    }

    /**
     * @param string $jobDetails
     */
    public function setJobDetails($jobDetails)
    {
        $this->jobDetails = $jobDetails;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * @param string $designation
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }
}
