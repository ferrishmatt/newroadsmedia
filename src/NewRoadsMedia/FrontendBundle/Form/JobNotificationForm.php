<?php

namespace NewRoadsMedia\FrontendBundle\Form;

use NewRoadsMedia\FrontendBundle\Entity\Focus;
use NewRoadsMedia\FrontendBundle\Entity\Industry;
use NewRoadsMedia\FrontendBundle\Entity\JobType;
use NewRoadsMedia\FrontendBundle\Entity\Location;
use NewRoadsMedia\FrontendBundle\Entity\Position;
use Symfony\Component\Validator\Constraints as Assert;

class JobNotificationForm
{
    /**
     * @var Industry[]
     * @Assert\Count(min=1, minMessage="At least one industry must be selected.", groups={"job-notification-subscribe"})
     */
    protected $industries;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var JobType
     */
    protected $jobType;

    /**
     * @var Position
     */
    protected $position;

    /**
     * @var string
     *
     */
    //@Assert\NotBlank(message="You must select a duration.", groups={"job-notification-subscribe"})
    protected $expiration;

    /**
     * @var string
     * @Assert\NotBlank(message="You must select subscribe/unsubscribe.")
     */
    protected $subscriptionStatus;

    /**
     * @var Focus[]
     */
    protected $focus;

    /**
     * @var bool
     */
    protected $nonFocused = false;

    /**
     * @return \NewRoadsMedia\FrontendBundle\Entity\Industry[]
     */
    public function getIndustries()
    {
        return $this->industries;
    }

    /**
     * @param \NewRoadsMedia\FrontendBundle\Entity\Industry[] $industries
     */
    public function setIndustries($industries)
    {
        $this->industries = $industries;
    }

    /**
     * @return \NewRoadsMedia\FrontendBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \NewRoadsMedia\FrontendBundle\Entity\Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return \NewRoadsMedia\FrontendBundle\Entity\JobType
     */
    public function getJobType()
    {
        return $this->jobType;
    }

    /**
     * @param \NewRoadsMedia\FrontendBundle\Entity\JobType $jobType
     */
    public function setJobType($jobType)
    {
        $this->jobType = $jobType;
    }

    /**
     * @return string
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @param string $expiration
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * @return string
     */
    public function getSubscriptionStatus()
    {
        return $this->subscriptionStatus;
    }

    /**
     * @param string $subscriptionStatus
     */
    public function setSubscriptionStatus($subscriptionStatus)
    {
        $this->subscriptionStatus = $subscriptionStatus;
    }

    /**
     * @return \NewRoadsMedia\FrontendBundle\Entity\Focus[]
     */
    public function getFocus()
    {
        return $this->focus;
    }

    /**
     * @param \NewRoadsMedia\FrontendBundle\Entity\Focus[] $focus
     */
    public function setFocus($focus)
    {
        $this->focus = $focus;
    }

    /**
     * @return boolean
     */
    public function getNonFocused()
    {
        return $this->nonFocused;
    }

    /**
     * @param boolean $nonFocused
     */
    public function setNonFocused($nonFocused)
    {
        $this->nonFocused = $nonFocused;
    }

    /**
     * @return \NewRoadsMedia\FrontendBundle\Entity\Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param \NewRoadsMedia\FrontendBundle\Entity\Position $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}