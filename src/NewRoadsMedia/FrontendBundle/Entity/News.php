<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * News
 *
 * @ORM\Table(name="News", indexes={
 *     @ORM\Index(columns={"entrydate"}),
 *     @ORM\Index(columns={"entrytype"})
 * })
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\NewsRepository")
 */
class News
{
    /**
     * @var integer
     *
     * @ORM\Column(name="linkID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=500, nullable=true)
     */
    protected $link;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="linkbody", type="text", nullable=true)
     */
    protected $linkBody;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="entrydate", type="datetime", nullable=true)
     */
    protected $entryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="imagename", type="string", length=100, nullable=true)
     */
    protected $imageName;

    /**
     * @var string
     *
     * @ORM\Column(name="imagealign", type="string", length=10, nullable=true)
     */
    protected $imageAlign;

    /**
     * @var string
     *
     * @ORM\Column(name="imagecredit", type="string", length=100, nullable=true)
     */
    protected $imageCredit;

    /**
     * @var string
     *
     * @ORM\Column(name="WhichSite", type="string", length=50, nullable=true)
     */
    protected $whichSite;

    /**
     * @var string
     *
     * @ORM\Column(name="Industry", type="string", length=50, nullable=true)
     */
    protected $industry;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=50, nullable=true)
     */
    protected $source;

    /**
     * @var string
     *
     * @ORM\Column(name="entrytype", type="string", length=50, nullable=true)
     */
    protected $entryType;

    /**
     * @var UploadedFile
     */
    private $file;

    public function __construct()
    {
        $this->setEntryDate(new \DateTime());
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
     * Set link
     *
     * @param string $link
     * @return News
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return News
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
     * Set linkBody
     *
     * @param string $linkBody
     * @return News
     */
    public function setLinkBody($linkBody)
    {
        $this->linkBody = $linkBody;

        return $this;
    }

    /**
     * Get linkBody
     *
     * @return string
     */
    public function getLinkBody()
    {
        return $this->linkBody;
    }

    /**
     * Set entryDate
     *
     * @param \DateTime $entryDate
     * @return News
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * Get entryDate
     *
     * @return \DateTime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return News
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set imageAlign
     *
     * @param string $imageAlign
     * @return News
     */
    public function setImageAlign($imageAlign)
    {
        $this->imageAlign = $imageAlign;

        return $this;
    }

    /**
     * Get imageAlign
     *
     * @return string
     */
    public function getImageAlign()
    {
        return $this->imageAlign;
    }

    /**
     * Set imageCredit
     *
     * @param string $imageCredit
     * @return News
     */
    public function setImageCredit($imageCredit)
    {
        $this->imageCredit = $imageCredit;

        return $this;
    }

    /**
     * Get imageCredit
     *
     * @return string
     */
    public function getImageCredit()
    {
        return $this->imageCredit;
    }

    /**
     * Set whichSite
     *
     * @param string $whichSite
     * @return News
     */
    public function setWhichSite($whichSite)
    {
        $this->whichSite = $whichSite;

        return $this;
    }

    /**
     * Get whichSite
     *
     * @return string
     */
    public function getWhichSite()
    {
        return $this->whichSite;
    }

    /**
     * Set industry
     *
     * @param string $industry
     * @return News
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;

        return $this;
    }

    /**
     * Get industry
     *
     * @return string
     */
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return News
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set entryType
     *
     * @param string $entryType
     * @return News
     */
    public function setEntryType($entryType)
    {
        $this->entryType = $entryType;

        return $this;
    }

    /**
     * Get entryType
     *
     * @return string
     */
    public function getEntryType()
    {
        return $this->entryType;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return $this
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        return $this;
    }
}