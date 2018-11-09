<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Job
 *
 * @ORM\Table(name="SimplyHired"
 *     , uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"JobKey"})
 *     }
 * )
 * @ORM\Entity()
 */
class SimplyHired
{
    /**
     * @var integer
     *
     * @ORM\Column(name="SimplyHiredID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="JobTitle", type="string", length=200, nullable=false)
     */
    protected $jobTitle;

    /**
     * @ORM\Column(name="Company", type="string", length=100, nullable=false)
     */
    protected $company;

    /**
     * @ORM\Column(name="Description", type="text", nullable=false)
     */
    protected $description;

    /**
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false)
     */
    protected $createDate;

    /**
     * @ORM\Column(name="JobCity", type="string", length=100, nullable=false)
     */
    protected $jobCity;

    /**
     * @ORM\Column(name="LocationDescription", type="string", length=100, nullable=false)
     */
    protected $locationDescription;

    /**
     * @ORM\Column(name="Region", type="string", length=100, nullable=false)
     */
    protected $region;

    /**
     * @ORM\Column(name="URL", type="text", nullable=false)
     */
    protected $url;

    /**
     * @ORM\Column(name="State", type="string", length=50, nullable=false)
     */
    protected $state;

    /**
     * @ORM\Column(name="Postal", type="string", length=50, nullable=false)
     */
    protected $postal;

    /**
     * @ORM\Column(name="County", type="string", length=100, nullable=false)
     */
    protected $county;

    /**
     * @var Job
     *
     * @ORM\OneToOne(targetEntity="Job", cascade={"persist"}, mappedBy="simplyHired")
     */
    protected $job;

    /**
     * @ORM\Column(name="JobKey", type="string", length=100, nullable=true)
     */
    protected $jobKey;

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
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return SimplyHired
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string 
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return SimplyHired
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
     * Set description
     *
     * @param string $description
     * @return SimplyHired
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return SimplyHired
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
     * Set jobCity
     *
     * @param string $jobCity
     * @return SimplyHired
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
     * Set locationDescription
     *
     * @param string $locationDescription
     * @return SimplyHired
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
     * Set region
     *
     * @param string $region
     * @return SimplyHired
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return SimplyHired
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return SimplyHired
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
     * Set postal
     *
     * @param string $postal
     * @return SimplyHired
     */
    public function setPostal($postal)
    {
        $this->postal = $postal;

        return $this;
    }

    /**
     * Get postal
     *
     * @return string 
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * Set county
     *
     * @param string $county
     * @return SimplyHired
     */
    public function setCounty($county)
    {
        $this->county = $county;

        return $this;
    }

    /**
     * Get county
     *
     * @return string 
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * Set jobKey
     *
     * @param string $jobKey
     * @return SimplyHired
     */
    public function setJobKey($jobKey)
    {
        $this->jobKey = $jobKey;

        return $this;
    }

    /**
     * Get jobKey
     *
     * @return string 
     */
    public function getJobKey()
    {
        return $this->jobKey;
    }

    /**
     * Set job
     *
     * @param Job $job
     * @return SimplyHired
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
}