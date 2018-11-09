<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Job
 *
 * @ORM\Table(name="Job"
 *     , indexes={
 *         @ORM\Index(columns={"CreateDate"}),
 *         @ORM\Index(columns={"ExpirationDate"}),
 *         @ORM\Index(columns={"TitleOfPositionOpen"}),
 *         @ORM\Index(columns={"diversity"}),
 *         @ORM\Index(columns={"incomplete"}),
 *         @ORM\Index(columns={"FrontPageExpirationDate"})
 *     }
 *     , uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"JobKey"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\JobRepository")
 */
class Job
{
    /**
     * @var integer
     *
     * @ORM\Column(name="JobID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="EmployerID", type="integer", nullable=false)
     */
    protected $employerID;

    /**
     * @var Employer
     *
     * @ORM\ManyToOne(targetEntity="Employer", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EmployerID", referencedColumnName="EmployerID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $employer;

    /**
     * @var string
     *
     * @ORM\Column(name="CompanyOrOrganization", type="string", length=510, nullable=true)
     */
    protected $company;

    /**
     * @var string
     *
     * @ORM\Column(name="Website", type="string", length=500, nullable=true)
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=400, nullable=true)
     */
    protected $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=400, nullable=true)
     */
    protected $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city_state_country", type="string", length=400, nullable=true)
     */
    protected $cityStateCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="accountzip", type="string", length=400, nullable=true)
     */
    protected $accountZip;

    /**
     * @var string
     *
     * @ORM\Column(name="accountzipplus", type="string", length=200, nullable=true)
     */
    protected $accountZipPlus;

    /**
     * @var string
     *
     * @ORM\Column(name="accountphonearea", type="string", length=200, nullable=true)
     */
    protected $accountPhoneArea;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_email", type="string", length=200, nullable=true)
     */
    protected $contactEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_title", type="string", length=200, nullable=true)
     */
    protected $contactTitle;

    /**
     * @var string
     *
     * 'a' => Do nothing. Contact info in job ad.
     * 'c', 'b' => Using JJ.com online application feature.
     * 'email' => forward resumes to specified email address.
     *
     * @ORM\Column(name="applytype", type="string", length=200, nullable=true)
     */
    protected $applyType = 'a';

    /**
     * @var string
     *
     * @ORM\Column(name="apply", type="string", length=200, nullable=true)
     */
    protected $apply;

    /**
     * @var string
     *
     * @ORM\Column(name="accountphone", type="string", length=400, nullable=true)
     */
    protected $accountPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=200, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="TitleOfPositionOpen", type="string", length=400, nullable=true)
     */
    protected $titleOfPositionOpen;

    /**
     * @var string
     *
     * @ORM\Column(name="AdBody", type="text", nullable=true)
     */
    protected $adBody;

    /**
     * @var string
     *
     * @ORM\Column(name="AdDescription", type="text", nullable=true)
     * @Assert\NotBlank(message="Please enter a description for this job ad.", groups={"post-new-job"})
     */
    protected $adDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="AdQualifications", type="text", nullable=true)
     */
    protected $adQualifications;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=true)
     */
    protected $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ExpirationDate", type="datetime", nullable=true)
     */
    protected $expirationDate;

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
     * @var Experience
     *
     * @ORM\ManyToOne(targetEntity="Experience")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ExperienceID", referencedColumnName="ExperienceID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $experience;

    /**
     * @var Industry
     *
     * @ORM\ManyToOne(targetEntity="Industry")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IndustryID", referencedColumnName="IndustryID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $industry;

    /**
     * @var Industry[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Industry", inversedBy="jobs")
     * @ORM\JoinTable(name="IndustryTOJob"
     *     , joinColumns={@ORM\JoinColumn(name="JobID", referencedColumnName="JobID", onDelete="CASCADE")}
     *     , inverseJoinColumns={@ORM\JoinColumn(name="IndustryID", referencedColumnName="IndustryID", onDelete="CASCADE")}
     * )
     * @Assert\Count(min=1, minMessage="At least one industry must be selected.", groups="post-new-job")
     */
    protected $industries;

    /**
     * @var Focus[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Focus")
     * @ORM\JoinTable(name="FocusTOJob"
     *     , joinColumns={@ORM\JoinColumn(name="JobID", referencedColumnName="JobID", onDelete="CASCADE")}
     *     , inverseJoinColumns={@ORM\JoinColumn(name="FocusID", referencedColumnName="FocusID", onDelete="CASCADE")}
     * )
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    protected $focus;

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
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="jobs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LocationID", referencedColumnName="LocationID", nullable=true, onDelete="SET NULL")
     * })
     * @Assert\NotBlank(message="Location is required!", groups={"post-new-job"})
     */
    protected $location;

    /**
     * @var string
     *
     * @ORM\Column(name="MajorMarket", type="string", length=2, nullable=true)
     */
    protected $majorMarket;

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
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Media")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MediaID", referencedColumnName="MediaID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $media;

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
     * @var string
     *
     * @ORM\Column(name="position_type", type="string", length=30, nullable=true)
     */
    protected $positionType;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RegionID", referencedColumnName="RegionID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $region;

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
     * @var Salary
     *
     * @ORM\ManyToOne(targetEntity="Salary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SalaryID", referencedColumnName="SalaryID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $salary;

    /**
     * @var string
     *
     * @ORM\Column(name="CustomSalary", type="string", length=200, nullable=true)
     */
    protected $customSalary;

    /**
     * @var boolean
     *
     * @ORM\Column(name="BillPaid", type="boolean", nullable=true)
     */
    protected $billPaid;

    /**
     * @var string
     *
     * @ORM\Column(name="Billing_fax", type="string", length=200, nullable=true)
     */
    protected $billingFax;

    /**
     * @var string
     *
     * @ORM\Column(name="Billing", type="string", length=200, nullable=true)
     */
    protected $billing;

    /**
     * @var string
     *
     * @ORM\Column(name="Billing_email", type="string", length=200, nullable=true)
     */
    protected $billingEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="Billing_instructions", type="text", nullable=true)
     */
    protected $billingInstructions;

    /**
     * @var string
     *
     * @ORM\Column(name="diversity", type="string", length=10, nullable=true)
     */
    protected $diversity;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_company", type="string", length=250, nullable=true)
     */
    protected $billingCompany;

    /**
     * @var string
     *
     * @ORM\Column(name="is_delayed", type="string", length=2, nullable=true)
     */
    protected $delayed;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="jobcity", type="string", length=100, nullable=true)
     */
    protected $jobCity;

    /**
     * @var string
     *
     * @ORM\Column(name="incomplete", type="string", length=1, nullable=true)
     */
    protected $incomplete = 'Y';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="featureexpirationdate", type="datetime", nullable=true)
     */
    protected $featureExpirationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ChangeLog", type="text", nullable=true)
     */
    protected $changeLog;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="AddedFeaturesDate", type="datetime", nullable=true)
     */
    protected $addedFeaturesDate;

    /**
     * @var string
     *
     * @ORM\Column(name="apply_email", type="string", length=200, nullable=true)
     * @Assert\NotBlank(message="You must enter an email address for email forwarding.", groups={"post-new-job-apply-type-email"})
     * @Assert\Email(message="You must enter a valid email address for email forwarding.", groups={"post-new-job-apply-type-email"})
     */
    protected $applyEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="apply_url", type="string", length=500, nullable=true)
     * @Assert\NotBlank(message="You must enter an application url.", groups={"post-new-job-apply-type-url"})
     * @Assert\Url(message="You must enter a valid application url.", groups={"post-new-job-apply-type-url"})
     */
    protected $applyUrl;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|Application[]
     *
     * @ORM\OneToMany(targetEntity="Application", mappedBy="job", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"applicationDate" = "DESC"})
     */
    protected $applications;

    /**
     * @var Payment[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Payment", cascade={"persist"}, inversedBy="jobs")
     * @ORM\JoinTable(name="JobToPayment"
     *     , joinColumns={@ORM\JoinColumn(name="JobID", referencedColumnName="JobID", onDelete="CASCADE")}
     *     , inverseJoinColumns={@ORM\JoinColumn(name="PaymentID", referencedColumnName="PaymentID", onDelete="CASCADE")}
     * )
     */
    protected $payments;

    /**
     * @var InvoiceTrack[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="InvoiceTrack", cascade={"persist"}, inversedBy="jobs")
     * @ORM\JoinTable(name="JobToInvoiceTrack"
     *     , joinColumns={@ORM\JoinColumn(name="JobID", referencedColumnName="JobID", onDelete="CASCADE")}
     *     , inverseJoinColumns={@ORM\JoinColumn(name="InvoiceTrackID", referencedColumnName="ID", onDelete="CASCADE")}
     * )
     */
    protected $invoiceTracks;

    /**
     * @var integer
     *
     * @ORM\Column(name="ApplicantCount", type="integer", nullable=false)
     */
    protected $emailApplicantCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="InvoiceID", type="integer", nullable=true)
     */
    protected $oldInvoiceID;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|JobView[]
     *
     * @ORM\OneToMany(targetEntity="JobView", mappedBy="job", fetch="EXTRA_LAZY")
     */
    protected $jobViews;

    /**
     * @var SimplyHired
     *
     * @ORM\OneToOne(targetEntity="SimplyHired", cascade={"persist"}, inversedBy="job")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SimplyHiredID", referencedColumnName="SimplyHiredID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $simplyHired;

    /**
     * @var string
     *
     * @ORM\Column(name="Duration", type="string", length=100, nullable=true)
     */
    protected $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FrontPageExpirationDate", type="datetime", nullable=true)
     */
    protected $frontPageExpirationDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="IsExternal", type="boolean", nullable=false)
     */
    protected $isExternal = false;

    /**
     * @ORM\Column(name="JobKey", type="string", length=100, nullable=true)
     */
    protected $jobKey;

    /**
     * @var string
     *
     * @ORM\Column(name="CertificatesNeeded", type="text", nullable=true)
     */
    protected $certificatesNeeded;

    /**
     * @var string
     *
     * @ORM\Column(name="RequiredEducation", type="text", nullable=true)
     */
    protected $requiredEducation;

    /**
     * @var string
     *
     * @ORM\Column(name="CertificationNeeded", type="string", length=15, nullable=true)
     */
    protected $certificationNeeded;

    /**
     * @var string
     *
     * @ORM\Column(name="CompanyImage", type="string", length=255, nullable=true)
     */
    protected $companyImage;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->focus = new ArrayCollection();
        $this->industries = new ArrayCollection();
        $this->invoiceTracks = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitleOfPositionOpen() ?: '';
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
     * @return Job
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
     * Set website
     *
     * @param string $website
     * @return Job
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
        $website = $this->website;
        if ($website && !preg_match('/^https?:\/\//i', $website)) {
            $website = 'http://' . $website;
        }
        if ($website == 'http://' || $website == 'https://') {
            $website = '';
        }

        return $website;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return Job
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return Job
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set accountZip
     *
     * @param string $accountZip
     * @return Job
     */
    public function setAccountZip($accountZip)
    {
        $this->accountZip = $accountZip;

        return $this;
    }

    /**
     * Get accountZip
     *
     * @return string
     */
    public function getAccountZip()
    {
        return $this->accountZip;
    }

    /**
     * Set accountZipPlus
     *
     * @param string $accountZipPlus
     * @return Job
     */
    public function setAccountZipPlus($accountZipPlus)
    {
        $this->accountZipPlus = $accountZipPlus;

        return $this;
    }

    /**
     * Get accountZipPlus
     *
     * @return string
     */
    public function getAccountZipPlus()
    {
        return $this->accountZipPlus;
    }

    /**
     * Set accountPhoneArea
     *
     * @param string $accountPhoneArea
     * @return Job
     */
    public function setAccountPhoneArea($accountPhoneArea)
    {
        $this->accountPhoneArea = $accountPhoneArea;

        return $this;
    }

    /**
     * Get accountPhoneArea
     *
     * @return string
     */
    public function getAccountPhoneArea()
    {
        return $this->accountPhoneArea;
    }

    /**
     * Set contactEmail
     *
     * @param string $contactEmail
     * @return Job
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * Get contactEmail
     *
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * Set contactTitle
     *
     * @param string $contactTitle
     * @return Job
     */
    public function setContactTitle($contactTitle)
    {
        $this->contactTitle = $contactTitle;

        return $this;
    }

    /**
     * Get contactTitle
     *
     * @return string
     */
    public function getContactTitle()
    {
        return $this->contactTitle;
    }

    /**
     * Set applyType
     *
     * @param string $applyType
     * @return Job
     */
    public function setApplyType($applyType)
    {
        $this->applyType = $applyType;

        return $this;
    }

    /**
     * Get applyType
     *
     * @return string
     */
    public function getApplyType()
    {
        return $this->applyType;
    }

    /**
     * Set apply
     *
     * @param string $apply
     * @return Job
     */
    public function setApply($apply)
    {
        $this->apply = $apply;

        return $this;
    }

    /**
     * Get apply
     *
     * @return string
     */
    public function getApply()
    {
        return $this->apply;
    }

    /**
     * Set accountPhone
     *
     * @param string $accountPhone
     * @return Job
     */
    public function setAccountPhone($accountPhone)
    {
        $this->accountPhone = $accountPhone;

        return $this;
    }

    /**
     * Get accountPhone
     *
     * @return string
     */
    public function getAccountPhone()
    {
        return $this->accountPhone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Job
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
     * Set titleOfPositionOpen
     *
     * @param string $titleOfPositionOpen
     * @return Job
     */
    public function setTitleOfPositionOpen($titleOfPositionOpen)
    {
        $this->titleOfPositionOpen = $titleOfPositionOpen;

        return $this;
    }

    /**
     * Get titleOfPositionOpen
     *
     * @return string
     */
    public function getTitleOfPositionOpen()
    {
        return $this->titleOfPositionOpen;
    }

    /**
     * Set adBody
     *
     * @param string $adBody
     * @return Job
     */
    public function setAdBody($adBody)
    {
        $this->adBody = $adBody;

        return $this;
    }

    /**
     * Get adBody
     *
     * @return string
     */
    public function getAdBody()
    {
        return $this->adBody;
    }

    /**
     * Set adDescription
     *
     * @param string $adDescription
     * @return Job
     */
    public function setAdDescription($adDescription)
    {
        $this->adDescription = $adDescription;

        return $this;
    }

    /**
     * Get adDescription
     *
     * @return string
     */
    public function getAdDescription()
    {
        return $this->adDescription;
    }

    /**
     * Set adQualifications
     *
     * @param string $adQualifications
     * @return Job
     */
    public function setAdQualifications($adQualifications)
    {
        $this->adQualifications = $adQualifications;

        return $this;
    }

    /**
     * Get adQualifications
     *
     * @return string
     */
    public function getAdQualifications()
    {
        return $this->adQualifications;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Job
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     * @return Job
     */
    public function setExpirationDate($expirationDate)
    {
        if ($expirationDate instanceof \DateTime) {
            $expirationDate = clone $expirationDate;
            $expirationDate->setTime(3, 0, 0);
            $expirationDate->modify('+1 day');
        }

        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Get applicantCount
     *
     * @return integer
     */
    public function getApplicantCount()
    {
        return $this->getApplications()->count();
    }

    /**
     * Set majorMarket
     *
     * @param string $majorMarket
     * @return Job
     */
    public function setMajorMarket($majorMarket)
    {
        $this->majorMarket = $majorMarket;

        return $this;
    }

    /**
     * Get majorMarket
     *
     * @return string
     */
    public function getMajorMarket()
    {
        return $this->majorMarket;
    }

    /**
     * Set billPaid
     *
     * @param boolean $billPaid
     * @return Job
     */
    public function setBillPaid($billPaid)
    {
        $this->billPaid = $billPaid;

        return $this;
    }

    /**
     * Get billPaid
     *
     * @return boolean
     */
    public function getBillPaid()
    {
        return $this->billPaid;
    }

    /**
     * Set billingFax
     *
     * @param string $billingFax
     * @return Job
     */
    public function setBillingFax($billingFax)
    {
        $this->billingFax = $billingFax;

        return $this;
    }

    /**
     * Get billingFax
     *
     * @return string
     */
    public function getBillingFax()
    {
        return $this->billingFax;
    }

    /**
     * Set billing
     *
     * @param string $billing
     * @return Job
     */
    public function setBilling($billing)
    {
        $this->billing = $billing;

        return $this;
    }

    /**
     * Get billing
     *
     * @return string
     */
    public function getBilling()
    {
        return $this->billing;
    }

    /**
     * Set billingEmail
     *
     * @param string $billingEmail
     * @return Job
     */
    public function setBillingEmail($billingEmail)
    {
        $this->billingEmail = $billingEmail;

        return $this;
    }

    /**
     * Get billingEmail
     *
     * @return string
     */
    public function getBillingEmail()
    {
        return $this->billingEmail;
    }

    /**
     * Set billingInstructions
     *
     * @param string $billingInstructions
     * @return Job
     */
    public function setBillingInstructions($billingInstructions)
    {
        $this->billingInstructions = $billingInstructions;

        return $this;
    }

    /**
     * Get billingInstructions
     *
     * @return string
     */
    public function getBillingInstructions()
    {
        return $this->billingInstructions;
    }

    /**
     * Set diversity
     *
     * @param string $diversity
     * @return Job
     */
    public function setDiversity($diversity)
    {
        $this->diversity = $diversity;

        return $this;
    }

    /**
     * Get diversity
     *
     * @return string
     */
    public function getDiversity()
    {
        return $this->diversity;
    }

    /**
     * Set billingCompany
     *
     * @param string $billingCompany
     * @return Job
     */
    public function setBillingCompany($billingCompany)
    {
        $this->billingCompany = $billingCompany;

        return $this;
    }

    /**
     * Get billingCompany
     *
     * @return string
     */
    public function getBillingCompany()
    {
        return $this->billingCompany;
    }

    /**
     * Set delayed
     *
     * @param string $delayed
     * @return Job
     */
    public function setDelayed($delayed)
    {
        $this->delayed = $delayed;

        return $this;
    }

    /**
     * Get delayed
     *
     * @return string
     */
    public function getDelayed()
    {
        return $this->delayed;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Job
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
     * Set jobCity
     *
     * @param string $jobCity
     * @return Job
     */
    public function setJobCity($jobCity)
    {
        $this->jobCity = $jobCity;

        return $this;
    }

    /**
     * Get jobCity
     *
     * @return string
     */
    public function getJobCity()
    {
        return $this->jobCity;
    }

    /**
     * Set incomplete
     *
     * @param string $incomplete
     * @return Job
     */
    public function setIncomplete($incomplete)
    {
        $this->incomplete = $incomplete;

        return $this;
    }

    /**
     * Get incomplete
     *
     * @return string
     */
    public function getIncomplete()
    {
        return $this->incomplete;
    }

    /**
     * Set featureExpirationDate
     *
     * @param \DateTime $featureExpirationDate
     * @return Job
     */
    public function setFeatureExpirationDate($featureExpirationDate)
    {
        $this->featureExpirationDate = $featureExpirationDate;

        return $this;
    }

    /**
     * Get featureExpirationDate
     *
     * @return \DateTime
     */
    public function getFeatureExpirationDate()
    {
        return $this->featureExpirationDate;
    }

    /**
     * Set changeLog
     *
     * @param string $changeLog
     * @return Job
     */
    public function setChangeLog($changeLog)
    {
        $this->changeLog = $changeLog;

        return $this;
    }

    public function addToChangeLog($log)
    {
        $now = new \DateTime('now');
        $changeLog = $this->getChangeLog() ?: '';
        $changeLog .= $now->format('m-d-Y H:i:s A') . ' ' . $log . '<br/>';
        $this->setChangeLog($changeLog);

        return $this;
    }

    /**
     * Get changeLog
     *
     * @return string
     */
    public function getChangeLog()
    {
        return $this->changeLog;
    }

    /**
     * Set addedFeaturesDate
     *
     * @param \DateTime $addedFeaturesDate
     * @return Job
     */
    public function setAddedFeaturesDate($addedFeaturesDate)
    {
        $this->addedFeaturesDate = $addedFeaturesDate;

        return $this;
    }

    /**
     * Get addedFeaturesDate
     *
     * @return \DateTime
     */
    public function getAddedFeaturesDate()
    {
        return $this->addedFeaturesDate;
    }

    /**
     * Set applyEmail
     *
     * @param string $applyEmail
     * @return Job
     */
    public function setApplyEmail($applyEmail)
    {
        $this->applyEmail = $applyEmail;

        return $this;
    }

    /**
     * Get applyEmail
     *
     * @return string
     */
    public function getApplyEmail()
    {
        return $this->applyEmail;
    }

    /**
     * Set employer
     *
     * @param Employer $employer
     * @return Job
     */
    public function setEmployer(Employer $employer = null)
    {
        $this->employer = $employer;

        return $this;
    }

    /**
     * Get employer
     *
     * @return Employer
     */
    public function getEmployer()
    {
        return $this->employer;
    }

    /**
     * Set education
     *
     * @param Education $education
     * @return Job
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
     * Set experience
     *
     * @param Experience $experience
     * @return Job
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
     * Set industry
     *
     * @param Industry $industry
     * @return Job
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
     * Set jobType
     *
     * @param JobType $jobType
     * @return Job
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
     * Set location
     *
     * @param Location $location
     * @return Job
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
     * @return Job
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
     * Set media
     *
     * @param Media $media
     * @return Job
     */
    public function setMedia(Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set position
     *
     * @param Position $position
     * @return Job
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
     * Set region
     *
     * @param Industry $region
     * @return Job
     */
    public function setRegion(Industry $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return Industry
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set specialty
     *
     * @param Specialty $specialty
     * @return Job
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
     * Set salary
     *
     * @param Salary $salary
     * @return Job
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
     * Add industries
     *
     * @param Industry $industry
     * @return $this
     */
    public function addIndustry(Industry $industry)
    {
        if (!$this->industries->contains($industry)) {
            $this->industries[] = $industry;
        }

        return $this;
    }

    /**
     * @param Industry $industry
     */
    public function removeIndustry(Industry $industry)
    {
        $this->industries->removeElement($industry);
    }

    /**
     * Get industries
     *
     * @return Industry[]|\Doctrine\Common\Collections\Collection
     */
    public function getIndustries()
    {
        return $this->industries;
    }

    public function isFeaturedJob()
    {
        return $this->getFeatureExpirationDate() && $this->getFeatureExpirationDate() > (new \DateTime());
    }

    public function isDiversityJob()
    {
        return strtolower($this->getDiversity()) == 'both';
    }

    /**
     * Add applications
     *
     * @param Application $application
     * @return Job
     */
    public function addApplication(Application $application)
    {
        $application->setJob($this);
        $this->applications[] = $application;

        return $this;
    }

    /**
     * Remove applications
     *
     * @param Application $applications
     */
    public function removeApplication(Application $applications)
    {
        $this->applications->removeElement($applications);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    public function hasExpired()
    {
        $expirationDate = $this->getExpirationDate();
        if (!$expirationDate) {
            return true;
        }

        $now = new \DateTime('now');

        return $expirationDate < $now;
    }

    public function isActive()
    {
        return !$this->hasExpired();
    }

    /**
     * Add payment
     *
     * @param Payment $payment
     * @return Job
     */
    public function addPayment(Payment $payment)
    {
        $this->payments[] = $payment;
        if (!$payment->getJobs()->contains($this)) {
            $payment->addJob($this);
        }

        return $this;
    }

    /**
     * Remove payment
     *
     * @param Payment $payment
     */
    public function removePayment(Payment $payment)
    {
        $this->payments->removeElement($payment);
        if ($payment->getJobs()->contains($this)) {
            $payment->getJobs()->removeElement($this);
        }
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection|Payment[]
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Add invoiceTrack
     *
     * @param InvoiceTrack $invoiceTrack
     * @return Job
     */
    public function addInvoiceTrack(InvoiceTrack $invoiceTrack)
    {
        $this->invoiceTracks[] = $invoiceTrack;
        if (!$invoiceTrack->getJobs()->contains($this)) {
            $invoiceTrack->addJob($this);
        }

        return $this;
    }

    /**
     * Remove invoiceTrack
     *
     * @param InvoiceTrack $invoiceTrack
     */
    public function removeInvoiceTrack(InvoiceTrack $invoiceTrack)
    {
        $this->invoiceTracks->removeElement($invoiceTrack);
        if ($invoiceTrack->getJobs()->contains($this)) {
            $invoiceTrack->removeJob($this);
        }
    }

    /**
     * Get invoiceTracks
     *
     * @return \Doctrine\Common\Collections\Collection|InvoiceTrack[]
     */
    public function getInvoiceTracks()
    {
        return $this->invoiceTracks;
    }

    public function isComplete()
    {
        return $this->getIncomplete() != 'Y';
    }

    /**
     * @return string
     */
    public function getCityStateCountry()
    {
        return $this->cityStateCountry;
    }

    /**
     * @param string $cityStateCountry
     */
    public function setCityStateCountry($cityStateCountry)
    {
        $this->cityStateCountry = $cityStateCountry;
    }

    /**
     * @deprecated use getInvoiceTracks() instead.
     * @return int
     */
    public function getOldInvoiceID()
    {
        return $this->oldInvoiceID;
    }

    public function isIncomplete()
    {
        return $this->getIncomplete() == 'Y';
    }

    /**
     * Set oldInvoiceID
     *
     * @param integer $oldInvoiceID
     * @return Job
     */
    public function setOldInvoiceID($oldInvoiceID)
    {
        $this->oldInvoiceID = $oldInvoiceID;

        return $this;
    }

    /**
     * Add jobViews
     *
     * @param JobView $jobViews
     * @return Job
     */
    public function addJobView(JobView $jobViews)
    {
        $this->jobViews[] = $jobViews;

        return $this;
    }

    /**
     * Remove jobViews
     *
     * @param JobView $jobViews
     */
    public function removeJobView(JobView $jobViews)
    {
        $this->jobViews->removeElement($jobViews);
    }

    /**
     * Clear all jobViews
     *
     */
    public function clearJobViews()
    {
        $this->jobViews->clear();
    }

    /**
     * Get jobViews
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJobViews()
    {
        return $this->jobViews;
    }

    /**
     * @return integer
     */
    public function getJobViewCount()
    {
        return $this->getJobViews()->count();
    }

    /**
     * Set simplyHired
     *
     * @param SimplyHired $simplyHired
     * @return Job
     */
    public function setSimplyHired(SimplyHired $simplyHired = null)
    {
        if ($simplyHired) {
            $simplyHired->setJob($this);
        }
        $this->simplyHired = $simplyHired;

        return $this;
    }

    /**
     * Get simplyHired
     *
     * @return SimplyHired
     */
    public function getSimplyHired()
    {
        return $this->simplyHired;
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

    public function updateExpirationDate()
    {
        $duration = $this->getDuration() ?: '35 days';
        $expirationDate = new \DateTime('now');
        $expirationDate->modify('+' . $duration);
        $this->setExpirationDate($expirationDate);
    }

    public function complete()
    {
        $this->setIncomplete('N');
    }

    public function isNew()
    {
        return $this->getCreateDate() === null;
    }

    public function setIsFeatured($isFeatured)
    {
        if ($isFeatured) {
            if (!$this->getIsFeatured()) {
                $this->setFeatureExpirationDate(new \DateTime('+10 days'));
            }
        } else {
            $this->setFeatureExpirationDate(null);
        }
    }

    public function setIsDiversity($isDiversity)
    {
        if ($isDiversity) {
            $this->setDiversity('Both');
        } else {
            $this->setDiversity(null);
        }
    }

    public function getIsDiversity()
    {
        return $this->isDiversityJob();
    }

    public function getIsFeatured()
    {
        return $this->isFeaturedJob();
    }

    public function getIsFrontPage()
    {
        return $this->getFrontPageExpirationDate() > (new \DateTime());
    }

    public function setIsFrontPage($isFrontPage)
    {
        if ($isFrontPage) {
            if (!$this->getIsFrontPage()) {
                $this->setFrontPageExpirationDate(new \DateTime('+5 days'));
            }
        } else {
            $this->setFrontPageExpirationDate(null);
        }
    }

    /**
     * @return \DateTime
     */
    public function getFrontPageExpirationDate()
    {
        return $this->frontPageExpirationDate;
    }

    /**
     * @param \DateTime $frontPageExpirationDate
     */
    public function setFrontPageExpirationDate($frontPageExpirationDate)
    {
        $this->frontPageExpirationDate = $frontPageExpirationDate;
    }

    /**
     * @return boolean
     */
    public function getIsExternal()
    {
        return $this->isExternal;
    }

    /**
     * @param boolean $isExternal
     */
    public function setIsExternal($isExternal)
    {
        $this->isExternal = $isExternal;
    }

    /**
     * @return mixed
     */
    public function getJobKey()
    {
        return $this->jobKey;
    }

    /**
     * @param mixed $jobKey
     */
    public function setJobKey($jobKey)
    {
        $this->jobKey = $jobKey;
    }

    /**
     * Add focuses
     *
     * @param Focus $focus
     * @return Job
     */
    public function addFocus(Focus $focus)
    {
        if (!$this->focus->contains($focus)) {
            $this->focus[] = $focus;
        }

        return $this;
    }

    /**
     * Remove focuses
     *
     * @param Focus $focus
     */
    public function removeFocus(Focus $focus)
    {
        $this->focus->removeElement($focus);
    }

    /**
     * Get focuses
     *
     * @return \Doctrine\Common\Collections\Collection|Focus[]
     */
    public function getFocus()
    {
        return $this->focus;
    }

    /**
     * @return string
     */
    public function getApplyUrl()
    {
        return $this->applyUrl;
    }

    /**
     * @param string $applyUrl
     */
    public function setApplyUrl($applyUrl)
    {
        $this->applyUrl = $applyUrl;
    }

    /**
     * @return string
     */
    public function getPositionType()
    {
        return $this->positionType;
    }

    /**
     * @param string $positionType
     */
    public function setPositionType($positionType)
    {
        $this->positionType = $positionType;
    }

    public function hasIndustry($industryId)
    {
        foreach ($this->getIndustries() as $industry) {
            if ($industry->getId() == $industryId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCertificatesNeeded()
    {
        return $this->certificatesNeeded;
    }

    /**
     * @param string $certificatesNeeded
     */
    public function setCertificatesNeeded($certificatesNeeded)
    {
        $this->certificatesNeeded = $certificatesNeeded;
    }

    /**
     * @return string
     */
    public function getRequiredEducation()
    {
        return $this->requiredEducation;
    }

    /**
     * @param string $requiredEducation
     */
    public function setRequiredEducation($requiredEducation)
    {
        $this->requiredEducation = $requiredEducation;
    }

    /**
     * @return string
     */
    public function getCustomSalary()
    {
        return $this->customSalary;
    }

    /**
     * @param string $customSalary
     */
    public function setCustomSalary($customSalary)
    {
        $this->customSalary = $customSalary;
    }

    public function getSalaryText()
    {
        $customSalary = $this->getCustomSalary();
        if ($customSalary) {
            return $customSalary;
        }

        $salary = $this->getSalary();
        if ($salary) {
            return $salary->getSalary();
        }

        return null;
    }

    public function isNewToUser($timestamp)
    {
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($timestamp);

        return $this->getCreateDate() > $dateTime;
    }

    public function wasPostedToday()
    {
        $createDate = $this->getCreateDate();
        if (!$createDate) {
            return false;
        }

        $timezone = new \DateTimeZone('America/Los_Angeles');
        $now = new \DateTime('now', $timezone);
        $createDate = clone $createDate;
        $createDate->setTimezone($timezone);

        return $createDate->format('Y-m-d') == $now->format('Y-m-d');
    }

    /**
     * @return string
     */
    public function getCertificationNeeded()
    {
        return $this->certificationNeeded;
    }

    /**
     * @param string $certificationNeeded
     */
    public function setCertificationNeeded($certificationNeeded)
    {
        $this->certificationNeeded = $certificationNeeded;
    }

    /**
     * @return int
     */
    public function getEmailApplicantCount()
    {
        return $this->emailApplicantCount;
    }

    /**
     * @param int $emailApplicantCount
     */
    public function setEmailApplicantCount($emailApplicantCount)
    {
        $this->emailApplicantCount = $emailApplicantCount;
    }

    /**
     * @return string
     */
    public function getCompanyImage()
    {
        return $this->companyImage;
    }

    /**
     * @param string $companyImage
     */
    public function setCompanyImage($companyImage)
    {
        $this->companyImage = $companyImage;
    }

    public function getLocationDisplay()
    {
        $location = '';
        if ($this->getJobCity()) {
            $location .= $this->getJobCity();
            if ($this->getLocation()) {
                $location .= ', ';
            }
        }
        if ($this->getLocation()) {
            $location .= $this->getLocation()->getLocationDescription();
        }

        return $location;
    }


    public function getPermLink(){
        $link = preg_replace('|[^a-z\s\-0-9]|', '', strtolower($this->titleOfPositionOpen . ' ' . $this->company));
        $link = $this->id . '-' . str_replace(' ', '-', $link);
        return $link;
    }
}
