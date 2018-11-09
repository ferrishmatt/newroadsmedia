<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application
 *
 * @ORM\Table(name="Application")
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\ApplicationRepository")
 */
class Application
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ApplicationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ApplicationDate", type="datetime", nullable=true)
     */
    protected $applicationDate;

    /**
     * @var Resume
     *
     * @ORM\ManyToOne(targetEntity="Resume")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $resume;

    /**
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="applications")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="JobId", referencedColumnName="JobID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $job;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=100, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="IntroLetter", type="text", nullable=true)
     */
    protected $introLetter;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeText", type="text", nullable=true)
     */
    protected $resumeText;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeFile", type="string", length=60, nullable=true)
     */
    protected $resumeFile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="IsViewed", type="boolean", nullable=false)
     */
    protected $isViewed = false;

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
     * Set applicationDate
     *
     * @param \DateTime $applicationDate
     * @return Application
     */
    public function setApplicationDate($applicationDate)
    {
        $this->applicationDate = $applicationDate;

        return $this;
    }

    /**
     * Get applicationDate
     *
     * @return \DateTime
     */
    public function getApplicationDate()
    {
        return $this->applicationDate;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Application
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set introLetter
     *
     * @param string $introLetter
     * @return Application
     */
    public function setIntroLetter($introLetter)
    {
        $this->introLetter = $introLetter;

        return $this;
    }

    /**
     * Get introLetter
     *
     * @return string
     */
    public function getIntroLetter()
    {
        return $this->introLetter;
    }

    /**
     * Set resume
     *
     * @param Resume $resume
     * @return Application
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
     * Set job
     *
     * @param Job $job
     * @return Application
     */
    public function setJob(Job $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return Job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @return string
     */
    public function getResumeText()
    {
        return $this->resumeText;
    }

    /**
     * @param string $resumeText
     */
    public function setResumeText($resumeText)
    {
        $this->resumeText = $resumeText;
    }

    public function isResumeTextHtml()
    {
        $resumeText = $this->getResumeText();

        return $resumeText != strip_tags($resumeText);
    }

    /**
     * @return boolean
     */
    public function getIsViewed()
    {
        return $this->isViewed;
    }

    /**
     * @param boolean $isViewed
     */
    public function setIsViewed($isViewed)
    {
        $this->isViewed = $isViewed;
    }

    /**
     * @return string
     */
    public function getResumeFile()
    {
        return $this->resumeFile;
    }

    /**
     * @param string $resumeFile
     */
    public function setResumeFile($resumeFile)
    {
        $this->resumeFile = $resumeFile;
    }
}