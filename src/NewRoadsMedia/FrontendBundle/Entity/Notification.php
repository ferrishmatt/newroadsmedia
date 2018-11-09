<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="Notification"
 *     , indexes={
 *         @ORM\Index(columns={"Email"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="NotificationID", type="integer", nullable=false)
     */
    protected $notificationId;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=100, nullable=false)
     */
    protected $email;

    /**
     * @var Industry
     *
     * @ORM\ManyToOne(targetEntity="Industry")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IndustryID", referencedColumnName="IndustryID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $industry;

    /**
     * @var Specialty
     *
     * @ORM\ManyToOne(targetEntity="Specialty")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SpecialtyID", referencedColumnName="SpecialtyID", nullable=true)
     * })
     */
    protected $specialty;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LocationID", referencedColumnName="LocationID", nullable=true)
     * })
     */
    protected $location;

    /**
     * @var Market
     *
     * @ORM\ManyToOne(targetEntity="Market")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MarketID", referencedColumnName="MarketID", nullable=true)
     * })
     */
    protected $market;

    /**
     * @var Position
     *
     * @ORM\ManyToOne(targetEntity="Position")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PositionID", referencedColumnName="PositionID", nullable=true)
     * })
     */
    protected $position;

    /**
     * @var Experience
     *
     * @ORM\ManyToOne(targetEntity="Experience")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ExperienceID", referencedColumnName="ExperienceID", nullable=true)
     * })
     */
    protected $experience;

    /**
     * @var Salary
     *
     * @ORM\ManyToOne(targetEntity="Salary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SalaryID", referencedColumnName="SalaryID", nullable=true)
     * })
     */
    protected $salary;

    /**
     * @var Education
     *
     * @ORM\ManyToOne(targetEntity="Education")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EducationID", referencedColumnName="EducationID", nullable=true)
     * })
     */
    protected $education;

    /**
     * @var JobType
     *
     * @ORM\ManyToOne(targetEntity="JobType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="JobTypeID", referencedColumnName="JobTypeID", nullable=true)
     * })
     */
    protected $jobType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Expiration", type="datetime", nullable=false)
     */
    protected $expiration;

    /**
     * @var Resume
     *
     * @ORM\ManyToOne(targetEntity="Resume", inversedBy="notifications")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", nullable=true)
     * })
     */
    protected $resume;

    /**
     * @var Focus[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Focus")
     * @ORM\JoinTable(name="FocusTONotification"
     *     , joinColumns={@ORM\JoinColumn(name="NotificationID", referencedColumnName="id", onDelete="CASCADE")}
     *     , inverseJoinColumns={@ORM\JoinColumn(name="FocusID", referencedColumnName="FocusID")}
     * )
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    protected $focus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="HasNonFocused", type="boolean", nullable=false)
     */
    protected $hasNonFocused = false;

    public function __construct()
    {
        $this->focus = new ArrayCollection();
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
     * Set notificationId
     *
     * @param integer $notificationId
     * @return Notification
     */
    public function setNotificationId($notificationId)
    {
        $this->notificationId = $notificationId;

        return $this;
    }

    /**
     * Get notificationId
     *
     * @return integer
     */
    public function getNotificationId()
    {
        return $this->notificationId;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Notification
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
     * Set expiration
     *
     * @param \DateTime $expiration
     * @return Notification
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Get expiration
     *
     * @return \DateTime
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Set industry
     *
     * @param Industry $industry
     * @return Notification
     */
    public function setIndustry(Industry $industry = null)
    {
        $this->industry = $industry;

        return $this;
    }

    /**
     * Get industry
     *
     * @return Industry
     */
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * Set specialty
     *
     * @param Specialty $specialty
     * @return Notification
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

    /**
     * Set location
     *
     * @param Location $location
     * @return Notification
     */
    public function setLocation(Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set market
     *
     * @param Market $market
     * @return Notification
     */
    public function setMarket(Market $market = null)
    {
        $this->market = $market;

        return $this;
    }

    /**
     * Get market
     *
     * @return Market
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * Set position
     *
     * @param Position $position
     * @return Notification
     */
    public function setPosition(Position $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set experience
     *
     * @param Experience $experience
     * @return Notification
     */
    public function setExperience(Experience $experience = null)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return Experience
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set salary
     *
     * @param Salary $salary
     * @return Notification
     */
    public function setSalary(Salary $salary = null)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get salary
     *
     * @return Salary
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set education
     *
     * @param Education $education
     * @return Notification
     */
    public function setEducation(Education $education = null)
    {
        $this->education = $education;

        return $this;
    }

    /**
     * Get education
     *
     * @return Education
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Set jobType
     *
     * @param JobType $jobType
     * @return Notification
     */
    public function setJobType(JobType $jobType = null)
    {
        $this->jobType = $jobType;

        return $this;
    }

    /**
     * Get jobType
     *
     * @return JobType
     */
    public function getJobType()
    {
        return $this->jobType;
    }

    /**
     * Set resume
     *
     * @param Resume $resume
     * @return Notification
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
     * Add focus
     *
     * @param Focus $focus
     * @return Notification
     */
    public function addFocus(Focus $focus)
    {
        $this->focus[] = $focus;

        return $this;
    }

    /**
     * Remove focus
     *
     * @param Focus $focus
     */
    public function removeFocus(Focus $focus)
    {
        $this->focus->removeElement($focus);
    }

    /**
     * Get focus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFocus()
    {
        return $this->focus;
    }

    /**
     * @return boolean
     */
    public function getHasNonFocused()
    {
        return $this->hasNonFocused;
    }

    /**
     * @param boolean $hasNonFocused
     */
    public function setHasNonFocused($hasNonFocused)
    {
        $this->hasNonFocused = $hasNonFocused;
    }
}