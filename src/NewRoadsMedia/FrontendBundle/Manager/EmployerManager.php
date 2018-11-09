<?php

namespace NewRoadsMedia\FrontendBundle\Manager;

use Doctrine\ORM\EntityManager;
use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use NewRoadsMedia\FrontendBundle\Entity\Employer;

/**
 * @method \NewRoadsMedia\FrontendBundle\Entity\Employer create()
 * @method \NewRoadsMedia\FrontendBundle\Entity\Employer find()
 */
class EmployerManager extends ObjectManager
{
    /** @var JobManager */
    protected $jobManager;

    public function __construct(EntityManager $entityManager, $class, $jobManager)
    {
        parent::__construct($entityManager, $class);
        $this->jobManager = $jobManager;
    }

    public function hasResumeAccess(Employer $employer)
    {
        if (!$employer->getResumeAccess()) {
            return false;
        }

        if ($this->jobManager->employerHasActiveJob($employer)) {
            return true;
        }

        if ($employer->getSearchExpirationDate()) {
            $now = new \DateTime('now');
            if ($employer->getSearchExpirationDate() >= $now) {
                return true;
            }
        }

        return false;
    }
}