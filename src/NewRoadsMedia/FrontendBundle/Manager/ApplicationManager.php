<?php

namespace NewRoadsMedia\FrontendBundle\Manager;

use Doctrine\ORM\EntityManager;
use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use NewRoadsMedia\FrontendBundle\Entity\Application;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Entity\Resume;

/**
 * @method \NewRoadsMedia\FrontendBundle\Entity\Application create()
 * @method \NewRoadsMedia\FrontendBundle\Entity\Application find()
 */
class ApplicationManager extends ObjectManager
{
    protected $directory;

    public function __construct(EntityManager $entityManager, $class, $directory)
    {
        parent::__construct($entityManager, $class);
        $this->directory = $directory;
        if (!file_exists($this->directory)) {
            mkdir($this->directory, 0777, true);
        }
    }

    public function apply(Job $job, $email, $coverLetter = null, $resumeText = null, Resume $resume = null, $resumePath = null)
    {
        $application = $this->create();
        $application->setEmail($email);
        $application->setApplicationDate(new \DateTime());
        $application->setJob($job);
        if ($coverLetter) {
            $application->setIntroLetter($coverLetter);
        }
        if ($resumeText) {
            $application->setResumeText($resumeText);
        }
        if ($resume) {
            $application->setResume($resume);
        }
        if ($resumePath && is_readable($resumePath)) {
            $parts = explode('.', $resumePath);
            $ext = end($parts);
            $file = 'resume.' . uniqid() . '.' . $ext;

            rename($resumePath, $this->directory . DIRECTORY_SEPARATOR . $file);
            $application->setResumeFile($file);
        }

        $this->save($application);
    }

    public function getResumeFilePath(Application $application)
    {
        return $this->directory . DIRECTORY_SEPARATOR . $application->getResumeFile();
    }
}