<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="Employer", indexes={
 *     @ORM\Index(columns={"Email"})
 * })
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\EmployerRepository")
 */
class Employer implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="EmployerID", type="integer", nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=400, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=100, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Address", type="string", length=510, nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="City", type="string", length=100, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="State", type="string", length=75, nullable=true)
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
     * @ORM\Column(name="Company", type="string", length=400, nullable=true)
     */
    protected $company;

    /**
     * @var string
     *
     * @ORM\Column(name="PhoneArea", type="string", length=60, nullable=true)
     */
    protected $phoneArea;

    /**
     * @ORM\Column(name="Phone", type="string", length=200, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="FaxNumber", type="string", length=200, nullable=true)
     */
    protected $faxNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=100, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=100, nullable=true)
     */
    protected $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="MembershipExpirationDate", type="datetime", nullable=true)
     */
    protected $membershipExpirationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="SearchExpirationDate", type="datetime", nullable=true)
     */
    protected $searchExpirationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="special_billing_instructions", type="string", length=800, nullable=true)
     */
    protected $specialBillingInstructions;

    /**
     * @var string
     *
     * @ORM\Column(name="billingcompany", type="string", length=200, nullable=true)
     */
    protected $billingCompany;

    /**
     * @var string
     *
     * @ORM\Column(name="packageplan", type="string", length=2, nullable=true)
     */
    protected $packagePlan;

    /**
     * @var integer
     *
     * @ORM\Column(name="adsremaining", type="integer", nullable=true)
     */
    protected $adsRemaining;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ResumeAccess", type="boolean")
     */
    protected $resumeAccess = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreatedDate", type="datetime", nullable=true)
     */
    protected $createdDate;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_email", type="string", length=100, nullable=true)
     */
    protected $billingEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_option", type="string", length=50, nullable=true)
     */
    protected $invoiceOption;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="LastLoginDate", type="datetime", nullable=true)
     */
    protected $lastLoginDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="free_option", type="boolean")
     */
    protected $freeOption = false;

    public function __toString()
    {
        return $this->getName() ?: '';
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
     * Set name
     *
     * @param string $name
     * @return Employer
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
     * Set title
     *
     * @param string $title
     * @return Employer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Employer
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
     * @return Employer
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
     * @return Employer
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
     * @return Employer
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
     * @return Employer
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
     * Set company
     *
     * @param string $company
     * @return Employer
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
     * Set phoneArea
     *
     * @param string $phoneArea
     * @return Employer
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
     * @return Employer
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
     * Set faxNumber
     *
     * @param string $faxNumber
     * @return Employer
     */
    public function setFaxNumber($faxNumber)
    {
        $this->faxNumber = $faxNumber;

        return $this;
    }

    /**
     * Get faxNumber
     *
     * @return string
     */
    public function getFaxNumber()
    {
        return $this->faxNumber;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Employer
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
     * @return Employer
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
     * Set membershipExpirationDate
     *
     * @param \DateTime $membershipExpirationDate
     * @return Employer
     */
    public function setMembershipExpirationDate($membershipExpirationDate)
    {
        $this->membershipExpirationDate = $membershipExpirationDate;

        return $this;
    }

    /**
     * Get membershipExpirationDate
     *
     * @return \DateTime
     */
    public function getMembershipExpirationDate()
    {
        return $this->membershipExpirationDate;
    }

    /**
     * Set searchExpirationDate
     *
     * @param \DateTime $searchExpirationDate
     * @return Employer
     */
    public function setSearchExpirationDate($searchExpirationDate)
    {
        $this->searchExpirationDate = $searchExpirationDate;

        return $this;
    }

    /**
     * Get searchExpirationDate
     *
     * @return \DateTime
     */
    public function getSearchExpirationDate()
    {
        return $this->searchExpirationDate;
    }

    /**
     * Set specialBillingInstructions
     *
     * @param string $specialBillingInstructions
     * @return Employer
     */
    public function setSpecialBillingInstructions($specialBillingInstructions)
    {
        $this->specialBillingInstructions = $specialBillingInstructions;

        return $this;
    }

    /**
     * Get specialBillingInstructions
     *
     * @return string
     */
    public function getSpecialBillingInstructions()
    {
        return $this->specialBillingInstructions;
    }

    /**
     * Set billingCompany
     *
     * @param string $billingCompany
     * @return Employer
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
     * Set packagePlan
     *
     * @param string $packagePlan
     * @return Employer
     */
    public function setPackagePlan($packagePlan)
    {
        $this->packagePlan = $packagePlan;

        return $this;
    }

    /**
     * Get packagePlan
     *
     * @return string
     */
    public function getPackagePlan()
    {
        return $this->packagePlan;
    }

    /**
     * Set adsRemaining
     *
     * @param integer $adsRemaining
     * @return Employer
     */
    public function setAdsRemaining($adsRemaining)
    {
        $this->adsRemaining = $adsRemaining;

        return $this;
    }

    public function addAdsRemaining($add)
    {
        $adsRemaining = $this->getAdsRemaining() + $add;
        $this->setAdsRemaining($adsRemaining);

        return $this;
    }

    /**
     * Get adsRemaining
     *
     * @return integer
     */
    public function getAdsRemaining()
    {
        return $this->adsRemaining;
    }

    /**
     * Set resumeAccess
     *
     * @param boolean $resumeAccess
     * @return Employer
     */
    public function setResumeAccess($resumeAccess)
    {
        if ($resumeAccess && !$this->resumeAccess) {
            $this->setSearchExpirationDate(new \DateTime('+5 weeks'));
        }

        $this->resumeAccess = $resumeAccess;

        return $this;
    }

    /**
     * Get resumeAccess
     *
     * @return boolean
     */
    public function getResumeAccess()
    {
        return $this->resumeAccess;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Employer
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set billingEmail
     *
     * @param string $billingEmail
     * @return Employer
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

    public function deductAdsRemaining($deduction = 1)
    {
        $adsRemaining = $this->getAdsRemaining();
        $adsRemaining -= $deduction;
        if ($adsRemaining < 0) {
            $adsRemaining = 0;
        }
        $this->setAdsRemaining($adsRemaining);
    }

    public function getRoles()
    {
        return array('ROLE_EMPLOYER');
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

    public function hasPackagePlan()
    {
        return strtoupper($this->getPackagePlan()) == 'Y';
    }

    public function setHasPackagePlan($hasPackagePlan)
    {
        $this->packagePlan = $hasPackagePlan ? 'Y' : null;
    }

    /**
     * @return string
     */
    public function getInvoiceOption()
    {
        return $this->invoiceOption;
    }

    /**
     * @param string $invoiceOption
     */
    public function setInvoiceOption($invoiceOption)
    {
        $this->invoiceOption = $invoiceOption;
    }

    public function hasInvoiceOption()
    {
        return strtolower($this->getInvoiceOption()) == 'allow';
    }

    /**
     * @return \DateTime
     */
    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }

    /**
     * @param \DateTime $lastLoginDate
     */
    public function setLastLoginDate($lastLoginDate)
    {
        $this->lastLoginDate = $lastLoginDate;
    }

    /**
     * @return boolean
     */
    public function getFreeOption()
    {
        return $this->freeOption;
    }

    /**
     * @param boolean $freeOption
     */
    public function setFreeOption($freeOption)
    {
        $this->freeOption = $freeOption;
    }

    public function getDisplayName()
    {
        if ($this->getName()) {
            return $this->getName();
        } else if ($this->getEmail()) {
            return $this->getEmail();
        }

        return 'Employer';
    }

    public function getDisplayCompany()
    {
        if ($this->getCompany()) {
            return $this->getCompany();
        } else if ($this->getBillingCompany()) {
            return $this->getBillingCompany();
        }

        return $this->getName();
    }
}