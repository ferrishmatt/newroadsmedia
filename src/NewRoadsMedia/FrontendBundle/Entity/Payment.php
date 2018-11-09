<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="Payment")
 * @ORM\Entity
 */
class Payment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PaymentID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Employer
     *
     * @ORM\ManyToOne(targetEntity="Employer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EmployerID", referencedColumnName="EmployerID", nullable=true, onDelete="CASCADE")
     * })
     */
    protected $employer;

    /**
     * @var string
     *
     * @ORM\Column(name="BankName", type="string", length=100, nullable=true)
     */
    protected $bankName;

    /**
     * @var string
     *
     * @ORM\Column(name="AccountNumber", type="string", length=100, nullable=true)
     */
    protected $accountNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="RoutingNumber", type="string", length=100, nullable=true)
     */
    protected $routingNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="CreditCard", type="string", length=100, nullable=true)
     */
    protected $creditCard;

    /**
     * @var string
     *
     * @ORM\Column(name="CreditCardNumber", type="string", length=100, nullable=true)
     */
    protected $creditCardNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="ExpirationDateMonth", type="integer", nullable=true)
     */
    protected $expirationDateMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="ExpirationDateYear", type="integer", nullable=true)
     */
    protected $expirationDateYear;

    /**
     * @var string
     *
     * @ORM\Column(name="NameOnCard", type="string", length=100, nullable=true)
     */
    protected $nameOnCard;

    /**
     * @var string
     *
     * @ORM\Column(name="PaymentMethod", type="string", length=100, nullable=true)
     */
    protected $paymentMethod;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Approval", type="boolean", nullable=false)
     */
    protected $approval;

    /**
     * @var integer
     *
     * @ORM\Column(name="ChargeAmount", type="integer", nullable=true)
     */
    protected $chargeAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="JobList", type="string", length=100, nullable=true)
     */
    protected $jobList;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="TransactionDate", type="datetime", nullable=true)
     */
    protected $transactionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="TransactionId", type="string", length=10, nullable=true)
     */
    protected $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=250, nullable=true)
     */
    protected $comment;

    /**
     * @var Job[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Job", mappedBy="payments", cascade={"persist"})
     */
    protected $jobs;

    /**
     * @var string
     *
     * @ORM\Column(name="AuthorizationCode", type="string", length=15, nullable=true)
     */
    protected $authorizationCode;

    /**
     * @var InvoiceTrack
     *
     * @ORM\ManyToOne(targetEntity="InvoiceTrack", inversedBy="payments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="InvoiceTrackID", referencedColumnName="ID", nullable=true)
     * })
     */
    protected $invoiceTrack;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
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
     * Set bankName
     *
     * @param string $bankName
     * @return Payment
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     * @return Payment
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set routingNumber
     *
     * @param string $routingNumber
     * @return Payment
     */
    public function setRoutingNumber($routingNumber)
    {
        $this->routingNumber = $routingNumber;

        return $this;
    }

    /**
     * Get routingNumber
     *
     * @return string
     */
    public function getRoutingNumber()
    {
        return $this->routingNumber;
    }

    /**
     * Set creditCard
     *
     * @param string $creditCard
     * @return Payment
     */
    public function setCreditCard($creditCard)
    {
        $this->creditCard = $creditCard;

        return $this;
    }

    /**
     * Get creditCard
     *
     * @return string
     */
    public function getCreditCard()
    {
        return $this->creditCard;
    }

    /**
     * Set creditCardNumber
     *
     * @param string $creditCardNumber
     * @return Payment
     */
    public function setCreditCardNumber($creditCardNumber)
    {
        $this->creditCardNumber = $creditCardNumber;

        return $this;
    }

    /**
     * Get creditCardNumber
     *
     * @return string
     */
    public function getCreditCardNumber()
    {
        return $this->creditCardNumber;
    }

    public function getCreditCardNumberLastFour()
    {
        $creditCardNumber = $this->getCreditCardNumber();
        $length = strlen($creditCardNumber);
        if ($length < 4) {
            return $creditCardNumber;
        }

        return substr($creditCardNumber, -4);
    }

    /**
     * Set expirationDateMonth
     *
     * @param integer $expirationDateMonth
     * @return Payment
     */
    public function setExpirationDateMonth($expirationDateMonth)
    {
        $this->expirationDateMonth = $expirationDateMonth;

        return $this;
    }

    /**
     * Get expirationDateMonth
     *
     * @return integer
     */
    public function getExpirationDateMonth()
    {
        return $this->expirationDateMonth;
    }

    /**
     * Set expirationDateYear
     *
     * @param integer $expirationDateYear
     * @return Payment
     */
    public function setExpirationDateYear($expirationDateYear)
    {
        $this->expirationDateYear = $expirationDateYear;

        return $this;
    }

    /**
     * Get expirationDateYear
     *
     * @return integer
     */
    public function getExpirationDateYear()
    {
        return $this->expirationDateYear;
    }

    /**
     * Set nameOnCard
     *
     * @param string $nameOnCard
     * @return Payment
     */
    public function setNameOnCard($nameOnCard)
    {
        $this->nameOnCard = $nameOnCard;

        return $this;
    }

    /**
     * Get nameOnCard
     *
     * @return string
     */
    public function getNameOnCard()
    {
        return $this->nameOnCard;
    }

    /**
     * Set paymentMethod
     *
     * @param string $paymentMethod
     * @return Payment
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set approval
     *
     * @param boolean $approval
     * @return Payment
     */
    public function setApproval($approval)
    {
        $this->approval = $approval;

        return $this;
    }

    /**
     * Get approval
     *
     * @return boolean
     */
    public function getApproval()
    {
        return $this->approval;
    }

    /**
     * Set chargeAmount
     *
     * @param integer $chargeAmount
     * @return Payment
     */
    public function setChargeAmount($chargeAmount)
    {
        $this->chargeAmount = $chargeAmount;

        return $this;
    }

    /**
     * Get chargeAmount
     *
     * @return integer
     */
    public function getChargeAmount()
    {
        return $this->chargeAmount;
    }

    /**
     * Set jobList
     *
     * @param string $jobList
     * @return Payment
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
     * Set transactionDate
     *
     * @param \DateTime $transactionDate
     * @return Payment
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * Get transactionDate
     *
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Payment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set employer
     *
     * @param Employer $employer
     * @return Payment
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
     * Add job
     *
     * @param Job $job
     * @return Payment
     */
    public function addJob(Job $job)
    {
        $this->jobs[] = $job;
        if (!$job->getPayments()->contains($this)) {
            $job->addPayment($this);
        }
    
        return $this;
    }

    /**
     * Remove job
     *
     * @param Job $job
     */
    public function removeJob(Job $job)
    {
        $this->jobs->removeElement($job);
        if ($job->getPayments()->contains($this)) {
            $job->removePayment($this);
        }
    }

    /**
     * Get jobs
     *
     * @return \Doctrine\Common\Collections\Collection|Job[]
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
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * Set invoiceTrack
     *
     * @param InvoiceTrack $invoiceTrack
     * @return Payment
     */
    public function setInvoiceTrack(InvoiceTrack $invoiceTrack = null)
    {
        $this->invoiceTrack = $invoiceTrack;

        return $this;
    }

    /**
     * Get invoiceTrack
     *
     * @return InvoiceTrack
     */
    public function getInvoiceTrack()
    {
        return $this->invoiceTrack;
    }
}
