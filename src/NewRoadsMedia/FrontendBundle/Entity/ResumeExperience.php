<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ResumeExperience")
 * @ORM\Entity()
 */
class ResumeExperience
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
     * @ORM\ManyToOne(targetEntity="Resume", cascade={"persist"}, inversedBy="resumeExperiences")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", nullable=false, onDelete="CASCADE")
     * })
     */
    protected $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="Type", type="string", length=30, nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="Designation", type="string", length=100, nullable=false)
     */
    protected $designation;

    /**
     * @ORM\ManyToOne(targetEntity="Specialty", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SpecialtyID", referencedColumnName="SpecialtyID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $specialty;

    /**
     * @ORM\Column(name="Years", type="string", length=20, nullable=true)
     */
    protected $years;

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
     * Set type
     *
     * @param string $type
     * @return ResumeExperience
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set designation
     *
     * @param string $designation
     * @return ResumeExperience
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string 
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set years
     *
     * @param string $years
     * @return ResumeExperience
     */
    public function setYears($years)
    {
        $this->years = $years;

        return $this;
    }

    /**
     * Get years
     *
     * @return string 
     */
    public function getYears()
    {
        return $this->years;
    }

    /**
     * Set resume
     *
     * @param Resume $resume
     * @return ResumeExperience
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
     * Set specialty
     *
     * @param Specialty $specialty
     * @return ResumeExperience
     */
    public function setSpecialty(Specialty $specialty = null)
    {
        $this->specialty = $specialty;

        return $this;
    }

    /**
     * Get specialty
     *
     * @return Specialty
     */
    public function getSpecialty()
    {
        return $this->specialty;
    }
}
