<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ResumeWorkSample")
 * @ORM\Entity()
 */
class ResumeWorkSample
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ResumeWorkSampleID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Resume
     *
     * @ORM\ManyToOne(targetEntity="Resume", cascade={"persist"}, inversedBy="workSamples")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", nullable=false, onDelete="CASCADE")
     * })
     */
    protected $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="WebSite", type="string", length=250, nullable=false)
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=500, nullable=true)
     */
    protected $description;

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
     * Set website
     *
     * @param string $website
     * @return ResumeWorkSample
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
     * Set resume
     *
     * @param \NewRoadsMedia\FrontendBundle\Entity\Resume $resume
     * @return ResumeWorkSample
     */
    public function setResume(\NewRoadsMedia\FrontendBundle\Entity\Resume $resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return \NewRoadsMedia\FrontendBundle\Entity\Resume 
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
