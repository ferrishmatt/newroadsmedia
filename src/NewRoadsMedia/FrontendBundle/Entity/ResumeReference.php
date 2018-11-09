<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ResumeReference")
 * @ORM\Entity()
 */
class ResumeReference
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ResumeReferenceID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Resume
     *
     * @ORM\ManyToOne(targetEntity="Resume", cascade={"persist"}, inversedBy="references")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ResumeID", referencedColumnName="ResumeID", nullable=false, onDelete="CASCADE")
     * })
     */
    protected $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=100, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Company", type="string", length=100, nullable=false)
     */
    protected $company;

    /**
     * @var string
     *
     * @ORM\Column(name="Relationship", type="string", length=100, nullable=false)
     */
    protected $relationship;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=15, nullable=false)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=150, nullable=false)
     */
    protected $email;

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
     * @return ResumeReference
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
     * @return ResumeReference
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
     * Set company
     *
     * @param string $company
     * @return ResumeReference
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
     * Set relationship
     *
     * @param string $relationship
     * @return ResumeReference
     */
    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;

        return $this;
    }

    /**
     * Get relationship
     *
     * @return string 
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return ResumeReference
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
     * Set email
     *
     * @param string $email
     * @return ResumeReference
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
     * Set resume
     *
     * @param Resume $resume
     * @return ResumeReference
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
}
