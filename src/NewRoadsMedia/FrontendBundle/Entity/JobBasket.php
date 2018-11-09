<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="JobBasket"
 *     , uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_job_basket_resume_job", columns={"ResumeID", "JobID"})
 *     }
 * )
 * @ORM\Entity
 */
class JobBasket
{
    /**
     * @var integer
     *
     * @ORM\Column(name="JobBasketID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Resume
     *
     * @ORM\ManyToOne(targetEntity="Resume", inversedBy="jobBaskets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", nullable=false)
     * })
     */
    protected $resume;

    /**
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="Job")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="JobID", referencedColumnName="JobID", nullable=false, onDelete="CASCADE")
     * })
     */
    protected $job;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Applied", type="boolean", nullable=false)
     */
    protected $hasApplied = false;

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
     * Set hasApplied
     *
     * @param boolean $hasApplied
     * @return JobBasket
     */
    public function setHasApplied($hasApplied)
    {
        $this->hasApplied = $hasApplied;

        return $this;
    }

    /**
     * Get hasApplied
     *
     * @return boolean
     */
    public function getHasApplied()
    {
        return $this->hasApplied;
    }

    /**
     * Set resume
     *
     * @param Resume $resume
     * @return JobBasket
     */
    public function setResume(Resume $resume)
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
     * @return JobBasket
     */
    public function setJob(Job $job)
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