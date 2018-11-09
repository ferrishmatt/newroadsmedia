<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceTrack
 *
 * @ORM\Table(name="Invoice_track")
 * @ORM\Entity
 */
class InvoiceTrack
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
     * @ORM\Column(name="billing_email", type="string", length=100, nullable=true)
     */
    protected $billingEmail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="billdate", type="datetime", nullable=true)
     */
    protected $billDate;

    /**
     * @var string
     *
     * @ORM\Column(name="joblist", type="string", length=100, nullable=true)
     */
    protected $jobList;

    /**
     * @var Employer
     *
     * @ORM\ManyToOne(targetEntity="Employer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employerid", referencedColumnName="EmployerID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $employer;

    /**
     * @var integer
     *
     * @ORM\Column(name="Amount", type="integer", nullable=true)
     */
    protected $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="paid", type="integer", nullable=true)
     */
    protected $paid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paiddate", type="date", nullable=true)
     */
    protected $paidDate;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_type", type="string", length=100, nullable=true)
     */
    protected $billingType;

    /**
     * @var string
     *
     * @ORM\Column(name="order_text", type="text", nullable=true)
     */
    protected $orderText;

    /**
     * @var Job[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Job", mappedBy="invoiceTracks", cascade={"persist"})
     */
    protected $jobs;

    /**
     * @var string
     *
     * @ORM\Column(name="AuthorizationCode", type="string", length=15, nullable=true)
     */
    protected $authorizationCode;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="invoiceTrack", cascade={"all"})
     */
    protected $payments;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('Invoice for $%s%s'
            , number_format($this->getAmount(), 2)
            , $this->getBillDate() ? $this->getBillDate()->format(' \\o\\n m/d/Y \\a\\t g:ia') : ''
        );
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
     * Set billingEmail
     *
     * @param string $billingEmail
     * @return InvoiceTrack
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
     * Set billDate
     *
     * @param \DateTime $billDate
     * @return InvoiceTrack
     */
    public function setBillDate($billDate)
    {
        $this->billDate = $billDate;
    
        return $this;
    }

    /**
     * Get billDate
     *
     * @return \DateTime 
     */
    public function getBillDate()
    {
        return $this->billDate;
    }

    /**
     * Set jobList
     *
     * @param string $jobList
     * @return InvoiceTrack
     */
    public function setJobList($jobList)
    {
        $this->jobList = $jobList;
    
        return $this;
    }

    /**
     * Get jobList
     *
     * @return string 
     */
    public function getJobList()
    {
        return $this->jobList;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return InvoiceTrack
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
     * Set paid
     *
     * @param integer $paid
     * @return InvoiceTrack
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    
        return $this;
    }

    /**
     * Get paid
     *
     * @return integer 
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set paidDate
     *
     * @param \DateTime $paidDate
     * @return InvoiceTrack
     */
    public function setPaidDate($paidDate)
    {
        $this->paidDate = $paidDate;
    
        return $this;
    }

    /**
     * Get paidDate
     *
     * @return \DateTime 
     */
    public function getPaidDate()
    {
        return $this->paidDate;
    }

    /**
     * Set billingType
     *
     * @param string $billingType
     * @return InvoiceTrack
     */
    public function setBillingType($billingType)
    {
        $this->billingType = $billingType;
    
        return $this;
    }

    /**
     * Get billingType
     *
     * @return string 
     */
    public function getBillingType()
    {
        return $this->billingType;
    }

    /**
     * Set orderText
     *
     * @param string $orderText
     * @return InvoiceTrack
     */
    public function setOrderText($orderText)
    {
        $this->orderText = $orderText;
    
        return $this;
    }

    public function addOrderText($orderText)
    {
        $this->setOrderText($this->getOrderText() . $orderText);
    }

    /**
     * Get orderText
     *
     * @return string 
     */
    public function getOrderText()
    {
        return $this->orderText;
    }

    /**
     * Set employer
     *
     * @param Employer $employer
     * @return InvoiceTrack
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
     * Add jobs
     *
     * @param Job $job
     * @return InvoiceTrack
     */
    public function addJob(Job $job)
    {
        $this->jobs[] = $job;
        if (!$job->getInvoiceTracks()->contains($this)) {
            $job->addInvoiceTrack($this);
        }
    
        return $this;
    }

    /**
     * Remove jobs
     *
     * @param Job $job
     */
    public function removeJob(Job $job)
    {
        $this->jobs->removeElement($job);
        if ($job->getInvoiceTracks()->contains($this)) {
            $job->removeInvoiceTrack($this);
        }
    }

    /**
     * Get jobs
     *
     * @return Job[]|\Doctrine\Common\Collections\Collection
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param string $authorizationCode
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
    }

    /**
     * Add payments
     *
     * @param Payment $payments
     * @return InvoiceTrack
     */
    public function addPayment(Payment $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param Payment $payments
     */
    public function removePayment(Payment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }
}
