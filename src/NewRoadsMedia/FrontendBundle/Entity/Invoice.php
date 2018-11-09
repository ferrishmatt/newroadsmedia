<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Invoices
 *
 * @ORM\Table(name="Invoices")
 * @ORM\Entity
 */
class Invoice
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=100, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Address", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="State", type="string", length=10, nullable=true)
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(name="Zip", type="string", length=10, nullable=true)
     */
    protected $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="Company", type="string", length=100, nullable=true)
     */
    protected $company;

    /**
     * @ORM\Column(name="phone_area", type="string", length=20, nullable=true)
     */
    protected $phoneArea;

    /**
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="SpecialNotes", type="string", length=500, nullable=true)
     */
    protected $specialNotes;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="AmountPaid", type="integer", nullable=true)
     */
    protected $amountPaid;

    /**
     * @var string
     *
     * @ORM\Column(name="Invoice", type="string", length=255, nullable=true)
     */
    protected $invoice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="WhenPaid", type="datetime", nullable=true)
     */
    protected $whenPaid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     */
    protected $creationDate;

    /**
     * Values: 'creditcard', 'check'
     * @ORM\Column(name="billing_method", type="string", nullable=true)
     */
    protected $billingMethod;

    /**
     * @ORM\Column(name="is_deleted", type="boolean")
     */
    protected $isDeleted = false;

    /**
     * @var Job
     *
     * @ORM\Column(name="job_id", type="string", nullable=true)
     */
    protected $job;

    public function __toString()
    {
        return $this->getInvoice() ?: '';
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
     * @return Invoice
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
     * @return Invoice
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
     * @return Invoice
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
     * @return Invoice
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
     * @return Invoice
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
     * Set company
     *
     * @param string $company
     * @return Invoice
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
     * @return Invoice
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
     * @return Invoice
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
     * Set specialNotes
     *
     * @param string $specialNotes
     * @return Invoice
     */
    public function setSpecialNotes($specialNotes)
    {
        $this->specialNotes = $specialNotes;

        return $this;
    }

    /**
     * Get specialNotes
     *
     * @return string
     */
    public function getSpecialNotes()
    {
        return $this->specialNotes;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Invoice
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amountPaid
     *
     * @param integer $amountPaid
     * @return Invoice
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amountPaid = $amountPaid;

        return $this;
    }

    /**
     * Get amountPaid
     *
     * @return integer
     */
    public function getAmountPaid()
    {
        return $this->amountPaid;
    }

    /**
     * Set invoice
     *
     * @param string $invoice
     * @return Invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return string
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set whenPaid
     *
     * @param \DateTime $whenPaid
     * @return Invoice
     */
    public function setWhenPaid($whenPaid)
    {
        $this->whenPaid = $whenPaid;

        return $this;
    }

    /**
     * Get whenPaid
     *
     * @return \DateTime
     */
    public function getWhenPaid()
    {
        return $this->whenPaid;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Invoice
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set billingMethod
     *
     * @param string $billingMethod
     * @return Invoice
     */
    public function setBillingMethod($billingMethod)
    {
        $this->billingMethod = $billingMethod;

        return $this;
    }

    /**
     * Get billingMethod
     *
     * @return string
     */
    public function getBillingMethod()
    {
        return $this->billingMethod;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Invoice
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @return \NewRoadsMedia\FrontendBundle\Entity\Job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param \NewRoadsMedia\FrontendBundle\Entity\Job $job
     */
    public function setJob($job)
    {
        $this->job = $job;
    }
}