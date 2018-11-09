<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ResumeEducation")
 * @ORM\Entity()
 */
class ResumeEducation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ResumeEducationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Resume
     *
     * @ORM\ManyToOne(targetEntity="Resume", cascade={"persist"}, inversedBy="educations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", nullable=false, onDelete="CASCADE")
     * })
     */
    protected $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=100, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Major", type="string", length=300, nullable=true)
     */
    protected $major;

    /**
     * @var string
     *
     * @ORM\Column(name="GPA", type="string", length=5, nullable=true)
     */
    protected $gpa;

    /**
     * @var string
     *
     * @ORM\Column(name="Degree", type="string", length=100, nullable=true)
     */
    protected $degree;

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
     * Set name
     *
     * @param string $name
     * @return ResumeEducation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set major
     *
     * @param string $major
     * @return ResumeEducation
     */
    public function setMajor($major)
    {
        $this->major = $major;

        return $this;
    }

    /**
     * Get major
     *
     * @return string 
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set gpa
     *
     * @param string $gpa
     * @return ResumeEducation
     */
    public function setGpa($gpa)
    {
        $this->gpa = $gpa;

        return $this;
    }

    /**
     * Get gpa
     *
     * @return string 
     */
    public function getGpa()
    {
        return $this->gpa;
    }

    /**
     * Set degree
     *
     * @param string $degree
     * @return ResumeEducation
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return string 
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set resume
     *
     * @param Resume $resume
     * @return ResumeEducation
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
}
