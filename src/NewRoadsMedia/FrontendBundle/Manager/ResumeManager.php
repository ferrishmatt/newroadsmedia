<?php

namespace NewRoadsMedia\FrontendBundle\Manager;

use Doctrine\ORM\EntityManager;
use Gregwar\ImageBundle\Services\ImageHandling;
use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @method \NewRoadsMedia\FrontendBundle\Entity\Resume create()
 * @method \NewRoadsMedia\FrontendBundle\Entity\Resume find()
 */
class ResumeManager extends ObjectManager
{
    /** @var ImageHandling */
    protected $imageHandling;
    protected $directory;
    protected $webDir;
    protected $imageDir;

    public function __construct(EntityManager $entityManager, $class, $imageHandling, $directory, $webDir, $imageDir)
    {
        parent::__construct($entityManager, $class);
        $this->directory = $directory;
        $this->webDir = $webDir;
        $this->imageDir = $imageDir;
        $this->imageHandling = $imageHandling;
    }

    public function deleteResumeFile(Resume $resume)
    {
        $directory = $this->getResumeDirectory();
        $currentResumeFile = $resume->getResumeFile();
        if ($currentResumeFile) {
            $currentPath = $directory . DIRECTORY_SEPARATOR . $currentResumeFile;
            if (file_exists($currentPath)) {
                unlink($directory . DIRECTORY_SEPARATOR . $currentResumeFile);
            }
        }
        $resume->setResumeFile(null);
    }

    public function deleteProfilePicture(Resume $resume)
    {
        $currentProfilePicture = $resume->getProfilePicture();
        if ($currentProfilePicture) {
            $baseDirectory = $this->webDir;
            $currentImagePath = $baseDirectory . $currentProfilePicture;
            if (file_exists($currentImagePath)) {
                unlink($currentImagePath);
            }
        }
        $resume->setProfilePicture(null);
    }

    public function uploadProfilePicture(Resume $resume, File $image)
    {
        $this->deleteProfilePicture($resume);
        $baseDirectory = $this->webDir;
        $path = $this->imageDir . '/';
        $directory = $baseDirectory . $path;
        $file =  uniqid() . '.' . $image->guessExtension();
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $image->move($directory, $file);

        $this->imageHandling->open($directory . $file)
            ->cropResize(134)
            ->cropResize(null, 150)
            ->save($directory . $file);

        chmod($directory . $file, 0666);

        $resume->setProfilePicture($path . $file);
    }

    public function getResumeDirectory()
    {
        $directory = $this->directory;
        if (!file_exists($directory)) {
            mkdir($directory, 0766, true);
        }

        return $directory;
    }

    public function getResumePath(Resume $resume)
    {
        $resumeFile = $resume->getResumeFile();
        if (!$resumeFile) {
            return null;
        }

        $directory = $this->getResumeDirectory();
        $path = $directory . DIRECTORY_SEPARATOR . $resumeFile;
        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }

    public function deleteResume(Resume $resume)
    {
        $this->deleteResumeFile($resume);
        $this->deleteProfilePicture($resume);
        $this->remove($resume);
    }
}