<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Resume
 *
 * @ORM\Table(name="Resume"
 *     , indexes={
 *         @ORM\Index(columns={"eMail"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\ResumeRepository")
 */
class Resume implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ResumeID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="eMail", type="string", length=100, nullable=true)
     * @Assert\Email(message="Please enter a valid email address.", groups={"post-resume"})
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="ContactEmail", type="string", length=100, nullable=true)
     */
    protected $contactEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=100, nullable=true)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="ViewAll", type="string", length=100, nullable=true)
     */
    protected $viewAll = 'all';

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=100, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="fname", type="string", length=100, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lname", type="string", length=100, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=100, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Company", type="string", length=100, nullable=true)
     */
    protected $company;

    /**
     * @var string
     *
     * @ORM\Column(name="Address", type="string", length=250, nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="City", type="string", length=250, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="State", type="string", length=100, nullable=true)
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(name="Zip", type="string", length=100, nullable=true)
     */
    protected $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="ZipPlus", type="string", length=100, nullable=true)
     */
    protected $zipPlus;

    /**
     * @var string
     *
     * @ORM\Column(name="PhoneArea", type="string", length=100, nullable=true)
     */
    protected $phoneArea;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=100, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="FaxArea", type="string", length=100, nullable=true)
     */
    protected $faxArea;

    /**
     * @var string
     *
     * @ORM\Column(name="Fax", type="string", length=100, nullable=true)
     */
    protected $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="ScreenName", type="string", length=100, nullable=true)
     */
    protected $screenName;

    /**
     * @var Industry|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Industry")
     * @ORM\JoinTable(name="IndustryTOResume"
     *     , joinColumns={@ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", onDelete="CASCADE")}
     *     , inverseJoinColumns={@ORM\JoinColumn(name="IndustryID", referencedColumnName="IndustryID", onDelete="CASCADE")}
     * )
     * @Assert\Count(min=1, minMessage="At least one industry must be selected.", groups={"post-resume", "edit-profile"})
     */
    protected $industries;

    /**
     * @var Focus[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Focus")
     * @ORM\JoinTable(name="FocusTOResume"
     *     , joinColumns={@ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", onDelete="CASCADE")}
     *     , inverseJoinColumns={@ORM\JoinColumn(name="FocusID", referencedColumnName="FocusID")}
     * )
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    protected $focus;

    /**
     * @var Position
     *
     * @ORM\ManyToOne(targetEntity="Position")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PositionID", referencedColumnName="PositionID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $position;

    /**
     * @var Specialty
     *
     * @ORM\ManyToOne(targetEntity="Specialty")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SpecialtyID", referencedColumnName="SpecialtyID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $specialty;

    /**
     * @var Market
     *
     * @ORM\ManyToOne(targetEntity="Market")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MarketID", referencedColumnName="MarketID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $market;

    /**
     * @var Experience
     *
     * @ORM\ManyToOne(targetEntity="Experience")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ExperienceID", referencedColumnName="ExperienceID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $experience;

    /**
     * @var Education
     *
     * @ORM\ManyToOne(targetEntity="Education")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EducationID", referencedColumnName="EducationID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $education;

    /**
     * @var JobType
     *
     * @ORM\ManyToOne(targetEntity="JobType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="JobTypeID", referencedColumnName="JobTypeID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $jobType;

    /**
     * @var Salary
     *
     * @ORM\ManyToOne(targetEntity="Salary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SalaryID", referencedColumnName="SalaryID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $salary;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CurrentStateID", referencedColumnName="LocationID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $currentState;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $desiredCity;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="DesiredStateID", referencedColumnName="LocationID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $desiredState;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="DesiredRegionID", referencedColumnName="RegionID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $desiredRegion;

    /**
     * @var string
     *
     * @ORM\Column(name="WebSite", type="string", length=250, nullable=true)
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeBody", type="text", nullable=true)
     */
    protected $resumeBody;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeJobObjective", type="text", nullable=true)
     */
    protected $resumeJobObjective;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeWorkExperienceOLD", type="text", nullable=true)
     */
    protected $resumeWorkExperienceOld;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeWorkExperience", type="text", nullable=true)
     */
    protected $resumeWorkExperience;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeEducation", type="text", nullable=true)
     */
    protected $resumeEducation;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeHonors", type="text", nullable=true)
     */
    protected $resumeHonors;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateEntered", type="datetime", nullable=true)
     */
    protected $dateEntered;

    /**
     * @var boolean
     *
     * @ORM\Column(name="OKd", type="boolean", nullable=true)
     */
    protected $okd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Expiration", type="datetime", nullable=true)
     */
    protected $expiration;

    /**
     * @var integer
     *
     * @ORM\Column(name="ViewCount", type="integer", nullable=false)
     */
    protected $viewCount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="resumecontract", type="text", nullable=true)
     */
    protected $resumeContract;

    /**
     * @var float
     *
     * @ORM\Column(name="senddemotape", type="float", nullable=true)
     */
    protected $sendDemoTape;

    /**
     * @var string
     *
     * @ORM\Column(name="avi", type="string", length=100, nullable=true)
     */
    protected $avi;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=100, nullable=true)
     */
    protected $language;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|Notification[]
     *
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="resume", cascade={"all"})
     */
    protected $notifications;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|JobBasket[]
     *
     * @ORM\OneToMany(targetEntity="JobBasket", mappedBy="resume", cascade={"all"})
     */
    protected $jobBaskets;

    /**
     * @var ResumeCertification
     *
     * @ORM\OneToOne(targetEntity="ResumeCertification", inversedBy="resume", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeCertificationID", referencedColumnName="ResumeCertificationID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $certification;

    /**
     * @var ResumeSocialMedia
     *
     * @ORM\OneToOne(targetEntity="ResumeSocialMedia", inversedBy="resume", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeSocialMediaID", referencedColumnName="ResumeSocialMediaID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $socialMedia;

    /**
     * @var ResumeEmployer[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ResumeEmployer", mappedBy="resume", cascade={"all"}, orphanRemoval=true)
     */
    protected $employers;

    /**
     * @var ResumeEducation[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ResumeEducation", mappedBy="resume", cascade={"all"}, orphanRemoval=true)
     */
    protected $educations;

    /**
     * @var ResumeReference[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ResumeReference", mappedBy="resume", cascade={"all"}, orphanRemoval=true)
     */
    protected $references;

    /**
     * @var ResumeCredential[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ResumeCredential", mappedBy="resume", cascade={"all"}, orphanRemoval=true)
     */
    protected $credentials;

    /**
     * @var ResumeWorkSample[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ResumeWorkSample", mappedBy="resume", cascade={"all"}, orphanRemoval=true)
     */
    protected $workSamples;

    /**
     * @var ResumeExperience[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ResumeExperience", mappedBy="resume", cascade={"all"}, orphanRemoval=true)
     */
    protected $resumeExperiences;

    /**
     * @var boolean
     *
     * @ORM\Column(name="IsAvailableImmediately", type="boolean", nullable=false)
     */
    protected $isAvailableImmediately = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateAvailable", type="datetime", nullable=true)
     */
    protected $dateAvailable;

    /**
     * @var string
     *
     * @ORM\Column(name="ProfilePicture", type="string", length=255, nullable=true)
     */
    protected $profilePicture;

    /**
     * @var string
     *
     * @ORM\Column(name="CurrentJobStatus", type="string", length=100, nullable=true)
     */
    protected $currentJobStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="IsPublic", type="boolean", nullable=false)
     */
    protected $isPublic = true;

    /**
     * @var string
     *
     * @ORM\Column(name="ResumeFile", type="string", length=60, nullable=true)
     */
    protected $resumeFile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="useTemplate", type="boolean", nullable=false)
     */
    protected $useTemplate = false;

    /**
     * @var string
     *
     * @ORM\Column(name="Duration", type="string", length=10, nullable=true)
     */
    protected $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="LastActivity", type="datetime", nullable=true)
     */
    protected $lastActivity;

    public function __construct()
    {
        $this->industries = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->jobBaskets = new ArrayCollection();
        $this->employers = new ArrayCollection();
        $this->educations = new ArrayCollection();
        $this->references = new ArrayCollection();
        $this->credentials = new ArrayCollection();
        $this->workSamples = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getDisplayName() ?: '';
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
     * Set email
     *
     * @param string $email
     * @return Resume
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
     * Set password
     *
     * @param string $password
     * @return Resume
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set viewAll
     *
     * @param string $viewAll
     * @return Resume
     */
    public function setViewAll($viewAll)
    {
        $this->viewAll = $viewAll;

        return $this;
    }

    /**
     * Get viewAll
     *
     * @return string
     */
    public function getViewAll()
    {
        return $this->viewAll;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Resume
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
     * Set address
     *
     * @param string $address
     * @return Resume
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
     * Set city
     *
     * @param string $city
     * @return Resume
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Resume
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return Resume
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set zipPlus
     *
     * @param string $zipPlus
     * @return Resume
     */
    public function setZipPlus($zipPlus)
    {
        $this->zipPlus = $zipPlus;

        return $this;
    }

    /**
     * Get zipPlus
     *
     * @return string
     */
    public function getZipPlus()
    {
        return $this->zipPlus;
    }

    /**
     * Set phoneArea
     *
     * @param string $phoneArea
     * @return Resume
     */
    public function setPhoneArea($phoneArea)
    {
        $this->phoneArea = $phoneArea;

        return $this;
    }

    /**
     * Get phoneArea
     *
     * @return string
     */
    public function getPhoneArea()
    {
        return $this->phoneArea;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Resume
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
     * Set faxArea
     *
     * @param string $faxArea
     * @return Resume
     */
    public function setFaxArea($faxArea)
    {
        $this->faxArea = $faxArea;

        return $this;
    }

    /**
     * Get faxArea
     *
     * @return string
     */
    public function getFaxArea()
    {
        return $this->faxArea;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Resume
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set screenName
     *
     * @param string $screenName
     * @return Resume
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;

        return $this;
    }

    /**
     * Get screenName
     *
     * @return string
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Resume
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set resumeBody
     *
     * @param string $resumeBody
     * @return Resume
     */
    public function setResumeBody($resumeBody)
    {
        $this->resumeBody = $resumeBody;

        return $this;
    }

    /**
     * Get resumeBody
     *
     * @return string
     */
    public function getResumeBody()
    {
        return $this->resumeBody;
    }

    /**
     * Set resumeJobObjective
     *
     * @param string $resumeJobObjective
     * @return Resume
     */
    public function setResumeJobObjective($resumeJobObjective)
    {
        $this->resumeJobObjective = $resumeJobObjective;

        return $this;
    }

    /**
     * Get resumeJobObjective
     *
     * @return string
     */
    public function getResumeJobObjective()
    {
        return $this->resumeJobObjective;
    }

    /**
     * Set resumeWorkExperienceOld
     *
     * @param string $resumeWorkExperienceOld
     * @return Resume
     */
    public function setResumeWorkExperienceOld($resumeWorkExperienceOld)
    {
        $this->resumeWorkExperienceOld = $resumeWorkExperienceOld;

        return $this;
    }

    /**
     * Get resumeWorkExperienceOld
     *
     * @return string
     */
    public function getResumeWorkExperienceOld()
    {
        return $this->resumeWorkExperienceOld;
    }

    /**
     * Set resumeWorkExperience
     *
     * @param string $resumeWorkExperience
     * @return Resume
     */
    public function setResumeWorkExperience($resumeWorkExperience)
    {
        $this->resumeWorkExperience = $resumeWorkExperience;

        return $this;
    }

    /**
     * Get resumeWorkExperience
     *
     * @return string
     */
    public function getResumeWorkExperience()
    {
        return $this->resumeWorkExperience;
    }

    /**
     * Set resumeEducation
     *
     * @param string $resumeEducation
     * @return Resume
     */
    public function setResumeEducation($resumeEducation)
    {
        $this->resumeEducation = $resumeEducation;

        return $this;
    }

    /**
     * Get resumeEducation
     *
     * @return string
     */
    public function getResumeEducation()
    {
        return $this->resumeEducation;
    }

    /**
     * Set resumeHonors
     *
     * @param string $resumeHonors
     * @return Resume
     */
    public function setResumeHonors($resumeHonors)
    {
        $this->resumeHonors = $resumeHonors;

        return $this;
    }

    /**
     * Get resumeHonors
     *
     * @return string
     */
    public function getResumeHonors()
    {
        return $this->resumeHonors;
    }

    /**
     * Set dateEntered
     *
     * @param \DateTime $dateEntered
     * @return Resume
     */
    public function setDateEntered($dateEntered)
    {
        $this->dateEntered = $dateEntered;

        return $this;
    }

    /**
     * Get dateEntered
     *
     * @return \DateTime
     */
    public function getDateEntered()
    {
        return $this->dateEntered;
    }

    /**
     * Set okd
     *
     * @param integer $okd
     * @return Resume
     */
    public function setOkd($okd)
    {
        $this->okd = $okd;

        return $this;
    }

    /**
     * Get okd
     *
     * @return boolean
     */
    public function getOkd()
    {
        return $this->okd;
    }

    /**
     * Set expiration
     *
     * @param \DateTime $expiration
     * @return Resume
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
     * Set viewCount
     *
     * @param integer $viewCount
     * @return Resume
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * Get viewCount
     *
     * @return integer
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * Set resumeContract
     *
     * @param string $resumeContract
     * @return Resume
     */
    public function setResumeContract($resumeContract)
    {
        $this->resumeContract = $resumeContract;

        return $this;
    }

    /**
     * Get resumeContract
     *
     * @return string
     */
    public function getResumeContract()
    {
        return $this->resumeContract;
    }

    /**
     * Set sendDemoTape
     *
     * @param float $sendDemoTape
     * @return Resume
     */
    public function setSendDemoTape($sendDemoTape)
    {
        $this->sendDemoTape = $sendDemoTape;

        return $this;
    }

    /**
     * Get sendDemoTape
     *
     * @return float
     */
    public function getSendDemoTape()
    {
        return $this->sendDemoTape;
    }

    /**
     * Set avi
     *
     * @param string $avi
     * @return Resume
     */
    public function setAvi($avi)
    {
        $this->avi = $avi;

        return $this;
    }

    /**
     * Get avi
     *
     * @return string
     */
    public function getAvi()
    {
        return $this->avi;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Resume
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set position
     *
     * @param Position $position
     * @return Resume
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
     * Set specialty
     *
     * @param Specialty $specialty
     * @return Resume
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
     * Set market
     *
     * @param Market $market
     * @return Resume
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
     * Set experience
     *
     * @param Experience $experience
     * @return Resume
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
     * Set education
     *
     * @param Education $education
     * @return Resume
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
     * @return Resume
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
     * Set salary
     *
     * @param Salary $salary
     * @return Resume
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
     * Set currentState
     *
     * @param Location $currentState
     * @return Resume
     */
    public function setCurrentState(Location $currentState = null)
    {
        $this->currentState = $currentState;

        return $this;
    }

    /**
     * Get currentState
     *
     * @return Location
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * Set desiredCity
     *
     * @param string $desiredCity
     * @return Resume
     */
    public function setDesiredCity($desiredCity)
    {
        $this->desiredCity = $desiredCity;

        return $this;
    }

    /**
     * Get desiredCity
     *
     * @return string
     */
    public function getDesiredCity()
    {
        return $this->desiredCity;
    }

    /**
     * Set desiredState
     *
     * @param Location $desiredState
     * @return Resume
     */
    public function setDesiredState(Location $desiredState = null)
    {
        $this->desiredState = $desiredState;

        return $this;
    }

    /**
     * Get desiredState
     *
     * @return Location
     */
    public function getDesiredState()
    {
        return $this->desiredState;
    }

    /**
     * Set desiredRegion
     *
     * @param Region $desiredRegion
     * @return Resume
     */
    public function setDesiredRegion(Region $desiredRegion = null)
    {
        $this->desiredRegion = $desiredRegion;

        return $this;
    }

    /**
     * Get desiredRegion
     *
     * @return Region
     */
    public function getDesiredRegion()
    {
        return $this->desiredRegion;
    }

    /**
     * Add industries
     *
     * @param Industry $industries
     * @return Resume
     */
    public function addIndustry(Industry $industries)
    {
        $this->industries[] = $industries;

        return $this;
    }

    /**
     * Remove industries
     *
     * @param Industry $industries
     */
    public function removeIndustry(Industry $industries)
    {
        $this->industries->removeElement($industries);
    }

    /**
     * Get industries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIndustries()
    {
        return $this->industries;
    }

    public function getRoles()
    {
        return array('ROLE_JOB_SEEKER');
    }

    public function setUsername($username)
    {
        $this->setEmail($username);

        return $this;
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function hasRole($checkRole)
    {
        foreach ($this->getRoles() as $role) {
            if ($role == $checkRole) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add notifications
     *
     * @param Notification $notification
     * @return Resume
     */
    public function addNotification(Notification $notification)
    {
        $notification->setResume($this);
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notifications
     *
     * @param Notification $notification
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection|Notification[]
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add jobBaskets
     *
     * @param JobBasket $jobBaskets
     * @return Resume
     */
    public function addJobBasket(JobBasket $jobBaskets)
    {
        $this->jobBaskets[] = $jobBaskets;

        return $this;
    }

    /**
     * Remove jobBaskets
     *
     * @param JobBasket $jobBaskets
     */
    public function removeJobBasket(JobBasket $jobBaskets)
    {
        $this->jobBaskets->removeElement($jobBaskets);
    }

    /**
     * Get jobBaskets
     *
     * @return \Doctrine\Common\Collections\Collection|JobBasket[]
     */
    public function getJobBaskets()
    {
        return $this->jobBaskets;
    }

    public function incrementViewCount($inc = 1)
    {
        $this->setViewCount($this->getViewCount() + $inc);
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
        ) = unserialize($serialized);
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getDisplayName()
    {
        if ($this->getName()) {
            return $this->getName();
        } else if ($this->getFirstName()) {
            return $this->getFirstName();
        } else if ($this->getEmail()) {
            return $this->getEmail();
        }

        return 'Job Seeker';
    }

    public function getDisplayFullName()
    {
        if ($this->getName()) {
            return $this->getName();
        } else if ($this->getFirstName() && $this->getLastName()) {
            return $this->getFirstName() . ' ' . $this->getLastName();
        } else if ($this->getFirstName()) {
            return $this->getFirstName();
        } else if ($this->getLastName()) {
            return $this->getLastName();
        } else if ($this->getEmail()) {
            return $this->getEmail();
        }

        return null;
    }

    /**
     * Add focus
     *
     * @param Focus $focus
     * @return Resume
     */
    public function addFocus(Focus $focus)
    {
        if (!$this->focus->contains($focus)) {
            $this->focus[] = $focus;
        }

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
     * @return \NewRoadsMedia\FrontendBundle\Entity\ResumeCertification
     */
    public function getCertification()
    {
        return $this->certification;
    }

    /**
     * @param \NewRoadsMedia\FrontendBundle\Entity\ResumeCertification $certification
     */
    public function setCertification(ResumeCertification $certification = null)
    {
        if ($certification) {
            $certification->setResume($this);
        }
        $this->certification = $certification;
    }

    /**
     * Add employers
     *
     * @param ResumeEmployer $employer
     * @return Resume
     */
    public function addEmployer(ResumeEmployer $employer)
    {
        $employer->setResume($this);
        $this->employers[] = $employer;

        return $this;
    }

    /**
     * Remove employers
     *
     * @param ResumeEmployer $employer
     */
    public function removeEmployer(ResumeEmployer $employer)
    {
        $this->employers->removeElement($employer);
    }

    /**
     * Get employers
     *
     * @return \Doctrine\Common\Collections\Collection|ResumeEmployer[]
     */
    public function getEmployers()
    {
        return $this->employers;
    }

    /**
     * Add educations
     *
     * @param ResumeEducation $education
     * @return Resume
     */
    public function addEducation(ResumeEducation $education)
    {
        $education->setResume($this);
        $this->educations[] = $education;

        return $this;
    }

    /**
     * Remove educations
     *
     * @param ResumeEducation $education
     */
    public function removeEducation(ResumeEducation $education)
    {
        $this->educations->removeElement($education);
    }

    /**
     * Get educations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEducations()
    {
        return $this->educations;
    }

    /**
     * Add reference
     *
     * @param ResumeReference $reference
     * @return Resume
     */
    public function addReference(ResumeReference $reference)
    {
        $reference->setResume($this);
        $this->references[] = $reference;

        return $this;
    }

    /**
     * Remove reference
     *
     * @param ResumeReference $reference
     */
    public function removeReference(ResumeReference $reference)
    {
        $this->references->removeElement($reference);
    }

    /**
     * Get references
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferences()
    {
        return $this->references;
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
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * Add credentials
     *
     * @param ResumeCredential $credential
     * @return Resume
     */
    public function addCredential(ResumeCredential $credential)
    {
        $credential->setResume($this);
        $this->credentials[] = $credential;

        return $this;
    }

    /**
     * Remove credentials
     *
     * @param ResumeCredential $credential
     */
    public function removeCredential(ResumeCredential $credential)
    {
        $this->credentials->removeElement($credential);
    }

    /**
     * Get credentials
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @return boolean
     */
    public function getIsAvailableImmediately()
    {
        return $this->isAvailableImmediately;
    }

    /**
     * @param boolean $isAvailableImmediately
     */
    public function setIsAvailableImmediately($isAvailableImmediately)
    {
        $this->isAvailableImmediately = $isAvailableImmediately;
    }

    /**
     * @return \DateTime
     */
    public function getDateAvailable()
    {
        return $this->dateAvailable;
    }

    /**
     * @param \DateTime $dateAvailable
     */
    public function setDateAvailable($dateAvailable)
    {
        $this->dateAvailable = $dateAvailable;
    }

    public function getDateAvailableText()
    {
        if ($this->getIsAvailableImmediately()) {
            return 'Immediately';
        }

        $dateAvailable = $this->getDateAvailable();
        if ($dateAvailable) {
            return $dateAvailable->format('M j, Y');
        }

        return null;
    }

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param string $profilePicture
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    /**
     * @return string
     */
    public function getCurrentJobStatus()
    {
        return $this->currentJobStatus;
    }

    /**
     * @param string $currentJobStatus
     */
    public function setCurrentJobStatus($currentJobStatus)
    {
        $this->currentJobStatus = $currentJobStatus;
    }

    /**
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contactEmail
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    public function isLive()
    {
        if (!$this->getOkd()) {
            return false;
        }

        $expiration = $this->getExpiration();
        if (!$expiration) {
            return false;
        }

        $now = new \DateTime('now', $expiration->getTimezone());

        return $now < $expiration;
    }

    /**
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * @param boolean $isPublic
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
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

    /**
     * @return boolean
     */
    public function getUseTemplate()
    {
        return $this->useTemplate;
    }

    /**
     * @param boolean $useTemplate
     */
    public function setUseTemplate($useTemplate)
    {
        $this->useTemplate = $useTemplate;
    }

    /**
     * Add workSamples
     *
     * @param ResumeWorkSample $workSample
     * @return Resume
     */
    public function addWorkSample(ResumeWorkSample $workSample)
    {
        $workSample->setResume($this);
        $this->workSamples[] = $workSample;

        return $this;
    }

    /**
     * Remove workSamples
     *
     * @param ResumeWorkSample $workSample
     */
    public function removeWorkSample(ResumeWorkSample $workSample)
    {
        $this->workSamples->removeElement($workSample);
    }

    /**
     * Get workSamples
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkSamples()
    {
        return $this->workSamples;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Add resumeExperiences
     *
     * @param ResumeExperience $resumeExperiences
     * @return Resume
     */
    public function addResumeExperience(ResumeExperience $resumeExperiences)
    {
        $resumeExperiences->setResume($this);
        $this->resumeExperiences[] = $resumeExperiences;

        return $this;
    }

    /**
     * Remove resumeExperiences
     *
     * @param ResumeExperience $resumeExperiences
     */
    public function removeResumeExperience(ResumeExperience $resumeExperiences)
    {
        $this->resumeExperiences->removeElement($resumeExperiences);
    }

    /**
     * Get resumeExperiences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResumeExperiences()
    {
        return $this->resumeExperiences;
    }

    /**
     * Set socialMedia
     *
     * @param ResumeSocialMedia $socialMedia
     * @return $this
     */
    public function setSocialMedia(ResumeSocialMedia $socialMedia = null)
    {
        if ($socialMedia) {
            $socialMedia->setResume($this);
        }
        $this->socialMedia = $socialMedia;

        return $this;
    }

    /**
     * Get socialMedia
     *
     * @return ResumeSocialMedia
     */
    public function getSocialMedia()
    {
        return $this->socialMedia;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     * @return Resume
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }
}
