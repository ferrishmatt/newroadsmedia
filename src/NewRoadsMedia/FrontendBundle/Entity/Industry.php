<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Industry
 *
 * @ORM\Table(name="Industry")
 * @ORM\Entity(repositoryClass="NewRoadsMedia\FrontendBundle\Repository\IndustryRepository")
 */
class Industry
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IndustryID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Industry", type="string", length=100, nullable=true)
     */
    protected $industry;

    /**
     * @var string
     *
     * @ORM\Column(name="Slug", type="string", length=100, nullable=true)
     */
    protected $slug;

    /**
     * @var integer
     *
     * @ORM\Column(name="DisplayOrder", type="integer", nullable=true)
     */
    protected $displayOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="Related", type="integer", nullable=true)
     */
    protected $related;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=100, nullable=true)
     */
    protected $title;

    /**
     * @var Job[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Job", mappedBy="industries", cascade={"persist"})
     */
    protected $jobs;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getIndustry() ?: '';
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
     * Set industry
     *
     * @param string $industry
     * @return Industry
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;
        if (!$this->getSlug()) {
            $slug = strtolower($industry);
            $slug = preg_replace('/[^a-z0-9_\s-]/', '', $slug);
            //Clean up multiple dashes or whitespaces
            $slug = preg_replace('/[\s-]+/', ' ', $slug);
            //Convert whitespaces and underscore to dash
            $slug = preg_replace('/[\s_]/', '-', $slug);
            $this->setSlug($slug);
        }

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
     * Set displayOrder
     *
     * @param integer $displayOrder
     * @return Industry
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return integer
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * Set related
     *
     * @param integer $related
     * @return Industry
     */
    public function setRelated($related)
    {
        $this->related = $related;

        return $this;
    }

    /**
     * Get related
     *
     * @return integer
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Add jobs
     *
     * @param Job $jobs
     * @return Industry
     */
    public function addJob(Job $jobs)
    {
        $this->jobs[] = $jobs;

        return $this;
    }

    /**
     * Remove jobs
     *
     * @param Job $jobs
     */
    public function removeJob(Job $jobs)
    {
        $this->jobs->removeElement($jobs);
    }

    /**
     * Get jobs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJobs()
    {
        return $this->jobs;
    }
}
