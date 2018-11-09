<?php

namespace NewRoadsMedia\FrontendBundle\Twig;

use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Service\LastActivity;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NewJobExtension extends \Twig_Extension
{
    /** @var LastActivity */
    protected $lastActivity;

    public function __construct($lastActivity)
    {
        $this->lastActivity = $lastActivity;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_new_job', array($this, 'isNewJob')),
        );
    }

    public function isNewJob(Job $job)
    {
        return $this->lastActivity->isNew($job);
    }

    public function getName()
    {
        return 'newroadsmedia_new_job_extension';
    }
}