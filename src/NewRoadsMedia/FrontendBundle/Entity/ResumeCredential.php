<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Resume
 *
 * @ORM\Table(name="ResumeCredential")
 * @ORM\Entity()
 */
class ResumeCredential
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ResumeCredentialID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Resume
     *
     * @ORM\ManyToOne(targetEntity="Resume", cascade={"persist"}, inversedBy="credentials")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", nullable=false, onDelete="CASCADE")
     * })
     */
    protected $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="Type", type="string", length=100, nullable=true)
     */
    protected $type;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LocationID", referencedColumnName="LocationID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $location;

    /**
     * @var boolean
     *
     * @ORM\Column(name="IsLifetime", type="boolean", nullable=false)
     */
    protected $isLifetime = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateExpiration", type="datetime", nullable=true)
     */
    protected $dateExpiration;

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
     * Set type
     *
     * @param string $type
     * @return ResumeCredential
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isLifetime
     *
     * @param boolean $isLifetime
     * @return ResumeCredential
     */
    public function setIsLifetime($isLifetime)
    {
        $this->isLifetime = $isLifetime;

        return $this;
    }

    /**
     * Get isLifetime
     *
     * @return boolean 
     */
    public function getIsLifetime()
    {
        return $this->isLifetime;
    }

    /**
     * Set dateExpiration
     *
     * @param \DateTime $dateExpiration
     * @return ResumeCredential
     */
    public function setDateExpiration($dateExpiration)
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    /**
     * Get dateExpiration
     *
     * @return \DateTime 
     */
    public function getDateExpiration()
    {
        return $this->dateExpiration;
    }

    /**
     * Set resume
     *
     * @param Resume $resume
     * @return ResumeCredential
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
     * Set location
     *
     * @param Location $location
     * @return ResumeCredential
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

    public function getDateExpirationText()
    {
        if ($this->getIsLifetime()) {
            return 'Lifetime';
        }

        $dateExpiration = $this->getDateExpiration();
        if ($dateExpiration) {
            return $dateExpiration->format('M j, Y');
        }

        return null;
    }
}
