<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ResumeEmployerSpecialty")
 * @ORM\Entity()
 */
class ResumeEmployerSpecialty
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ResumeEmployerSpecialtyID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Resume
     *
     * @ORM\ManyToOne(targetEntity="ResumeEmployer", cascade={"persist"}, inversedBy="specialties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeEmployerID", referencedColumnName="ResumeEmployerID", nullable=false, onDelete="CASCADE")
     * })
     */
    protected $resumeEmployer;

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
     * Set years
     *
     * @param string $years
     * @return ResumeEmployerSpecialty
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
     * Set resumeEmployer
     *
     * @param ResumeEmployer $resumeEmployer
     * @return ResumeEmployerSpecialty
     */
    public function setResumeEmployer(ResumeEmployer $resumeEmployer)
    {
        $this->resumeEmployer = $resumeEmployer;

        return $this;
    }

    /**
     * Get resumeEmployer
     *
     * @return ResumeEmployer
     */
    public function getResumeEmployer()
    {
        return $this->resumeEmployer;
    }

    /**
     * Set specialty
     *
     * @param Specialty $specialty
     * @return ResumeEmployerSpecialty
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
