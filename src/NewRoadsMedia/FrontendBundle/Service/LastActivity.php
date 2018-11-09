<?php

namespace NewRoadsMedia\FrontendBundle\Service;

use NewRoadsMedia\FrontendBundle\Entity\Job;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class LastActivity
{
    /** @var SessionInterface */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setJobView(Job $job)
    {
        $this->session->set($this->getKey($job), true);
    }

    public function isNew(Job $job)
    {
        if (!$job->wasPostedToday()) {
            return false;
        }

        return !$this->session->get($this->getKey($job));
    }

    protected function getKey(Job $job)
    {
        return 'jv-' . $job->getId();
    }
}