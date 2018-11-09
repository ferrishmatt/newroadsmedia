<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="JobViews"
 *     , uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"UserGUID", "JobID"})
 *     }
 *     , indexes={
 *         @ORM\Index(columns={"dt"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\JobViewRepository")
 */
class JobView
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
     * @var Employer
     *
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="jobViews")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="JobID", referencedColumnName="JobID", onDelete="CASCADE")
     * })
     */
    protected $job;

    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=400, nullable=true)
     */
    protected $ref;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt", type="datetime", nullable=true)
     */
    protected $createDate;

    /**
     * @var string
     *
     * @ORM\Column(name="UserGUID", type="string", length=64, nullable=false)
     */
    protected $userGuid;

    /**
     * @var string
     *
     * @ORM\Column(name="test", type="string", length=100, nullable=true)
     */
    protected $test;

    /**
     * @var string
     *
     * @ORM\Column(name="newtest", type="text", nullable=true)
     */
    protected $newTest;

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
     * Set ref
     *
     * @param string $ref
     * @return JobView
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string 
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return JobView
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
     * Set userGuid
     *
     * @param string $userGuid
     * @return JobView
     */
    public function setUserGuid($userGuid)
    {
        $this->userGuid = $userGuid;

        return $this;
    }

    /**
     * Get userGuid
     *
     * @return string 
     */
    public function getUserGuid()
    {
        return $this->userGuid;
    }

    /**
     * Set test
     *
     * @param string $test
     * @return JobView
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get test
     *
     * @return string 
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Set newTest
     *
     * @param string $newTest
     * @return JobView
     */
    public function setNewTest($newTest)
    {
        $this->newTest = $newTest;

        return $this;
    }

    /**
     * Get newTest
     *
     * @return string 
     */
    public function getNewTest()
    {
        return $this->newTest;
    }

    /**
     * Set job
     *
     * @param Job $job
     * @return JobView
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
