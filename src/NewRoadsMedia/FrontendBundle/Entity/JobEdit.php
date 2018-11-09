<?php

namespace NewRoadsMedia\FrontendBundle\Entity;

class JobEdit
{
    /** @var boolean */
    protected $addedDiversity;

    /** @var boolean */
    protected $addedFeatured;

    /** @var boolean */
    protected $addedFrontPage;

    /** @var int[] */
    protected $industries = array();

    protected $originalIndustryCount = 0;

    /**
     * @return \int[]
     */
    public function getIndustries()
    {
        return $this->industries;
    }

    public function addIndustry(Industry $industry)
    {
        $id = $industry->getId();
        if (!in_array($id, $this->industries)) {
            $this->industries[] = $id;
        }
    }

    public function removeIndustry(Industry $industry)
    {
        foreach ($this->industries as $index => $id) {
            if ($industry->getId() == $id) {
                unset($this->industries[$index]);
            }
        }
    }

    /**
     * @return boolean
     */
    public function getAddedDiversity()
    {
        return $this->addedDiversity;
    }

    /**
     * @param boolean $addedDiversity
     */
    public function setAddedDiversity($addedDiversity)
    {
        $this->addedDiversity = $addedDiversity;
    }

    /**
     * @return boolean
     */
    public function getAddedFeatured()
    {
        return $this->addedFeatured;
    }

    /**
     * @param boolean $addedFeatured
     */
    public function setAddedFeatured($addedFeatured)
    {
        $this->addedFeatured = $addedFeatured;
    }

    /**
     * @return boolean
     */
    public function getAddedFrontPage()
    {
        return $this->addedFrontPage;
    }

    /**
     * @param boolean $addedFrontPage
     */
    public function setAddedFrontPage($addedFrontPage)
    {
        $this->addedFrontPage = $addedFrontPage;
    }

    /**
     * @return int
     */
    public function getOriginalIndustryCount()
    {
        return $this->originalIndustryCount;
    }

    /**
     * @param int $originalIndustryCount
     */
    public function setOriginalIndustryCount($originalIndustryCount)
    {
        $this->originalIndustryCount = $originalIndustryCount;
    }
}