<?php

namespace NewRoadsMedia\FrontendBundle\Manager;

use Doctrine\ORM\EntityManager;
use Gregwar\ImageBundle\Services\ImageHandling;
use NewRoadsMedia\FrontendBundle\Entity\Employer;
use NewRoadsMedia\FrontendBundle\Entity\Focus;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use NewRoadsMedia\FrontendBundle\Entity\Position;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @method \NewRoadsMedia\FrontendBundle\Entity\Job create()
 * @method \NewRoadsMedia\FrontendBundle\Entity\Job find($id)
 * @method \NewRoadsMedia\FrontendBundle\Entity\Job getReference($id)
 * @method \NewRoadsMedia\FrontendBundle\Repository\JobRepository getRepository()
 */
class JobManager extends ObjectManager
{
    /** @var float */
    protected $adCost;

    /** @var float */
    protected $featuredAdCost;

    /** @var float */
    protected $diversityAdCost;

    /** @var float */
    protected $frontPageAdCost;

    /** @var ObjectManager */
    protected $focusManager;

    /** @var ObjectManager */
    protected $positionManager;

    /** @var ImageHandling */
    protected $imageHandling;

    /** @var ConfigurationManager */
    protected $configuration;

    /** @var string */
    protected $imageDir;

    /** @var string */
    protected $webDir;

    public function __construct(EntityManager $entityManager, $class, $focusManager, $positionManager, $imageHandling, $configuration, $adCost, $featuredAdCost, $diversityAdCost, $frontPageAdCost, $imageDir, $webDir)
    {
        parent::__construct($entityManager, $class);
        $this->focusManager = $focusManager;
        $this->positionManager = $positionManager;
        $this->imageHandling = $imageHandling;
        $this->configuration = $configuration;
        $this->adCost = $adCost;
        $this->featuredAdCost = $featuredAdCost;
        $this->diversityAdCost = $diversityAdCost;
        $this->frontPageAdCost = $frontPageAdCost;
        $this->imageDir = $imageDir;
        $this->webDir = $webDir;
    }

    public function getJobChargeAmount(Job $job, $considerPackagePlan = true)
    {
        if ($job->getEmployer() && $job->getEmployer()->getFreeOption()) {
            return 0;
        }

        $jobIndustryCount = $job->getIndustries()->count();
        $amount = $this->adCost * $jobIndustryCount;
        if ($amount < 0) {
            $amount = 0;
        }
        if ($job->isFeaturedJob()) {
            $amount += $this->featuredAdCost;
        }
        if ($job->isDiversityJob()) {
            $amount += $this->diversityAdCost;
        }
        if ($job->getIsFrontPage()) {
            $amount += $this->frontPageAdCost;
        }
        if ($considerPackagePlan && $job->getEmployer() && $job->getEmployer()->hasPackagePlan() && $job->getEmployer()->getAdsRemaining() > 0) {
            $deduct = min($job->getEmployer()->getAdsRemaining(), $jobIndustryCount);
            $amount -= ($deduct * $this->adCost);
        }
        if ($amount < 0) {
            $amount = 0;
        }

        return $amount;
    }

    /**
     * @param Employer $employer
     * @param Job[] $jobs
     * @param bool $considerPackagePlan
     * @return float
     */
    public function getJobsChargeAmount(Employer $employer, $jobs, $considerPackagePlan = true)
    {
        if ($employer->getFreeOption()) {
            return 0.00;
        }

        $amount = 0.00;
        $industryCount = 0;
        foreach ($jobs as $job) {
            $amount += $this->getJobChargeAmount($job, false);
            $industryCount += $job->getIndustries()->count();
        }
        if ($considerPackagePlan && $employer->hasPackagePlan() && $employer->getAdsRemaining() > 0) {
            $deduct = min($employer->getAdsRemaining(), $industryCount);
            $amount -= ($deduct * $this->adCost);
        }
        if ($amount < 0) {
            $amount = 0;
        }

        return $amount;
    }

    public function getJobOrderText(Job $job)
    {
        $industries = array();
        foreach ($job->getIndustries() as $industry) {
            $industries[] = $industry->getIndustry();
        }

        $orderText = sprintf('<p><span style ="font-size:1.3em;"><strong>Job Title:</strong> %s <br />%s'
            , $job->getTitleOfPositionOpen()
            , PHP_EOL . PHP_EOL . PHP_EOL
        );
        $orderText .= sprintf('<strong>%d %s selected:</strong> %s - $%s<br/>%s'
            , $job->getIndustries()->count()
            , $job->getIndustries()->count() == 1 ? 'industry' : 'industries'
            , implode(', ', $industries)
            , number_format($this->adCost * $job->getIndustries()->count(), 2)
            , PHP_EOL
        );
        if ($job->getIsFrontPage()) {
            $orderText .= sprintf('Frontpage Featured Section -- $%s<br/> ', number_format($this->frontPageAdCost, 2));
        }
        if ($job->isFeaturedJob()) {
            $orderText .= sprintf('Featured Jobs Section -- $%s<br/> ', number_format($this->featuredAdCost, 2));
        }
        if ($job->isDiversityJob()) {
            $orderText .= sprintf('Diversity Jobs Section -- $%s<br/> ', number_format($this->diversityAdCost, 2));
        }

        $orderText .= PHP_EOL . '</span></p>';

        return $orderText;
    }

    public function getAvailableFrontPageAdCount()
    {
        $max = $this->configuration->get('max_frontpage_ads', 7);
        if ($max <= 0) {
            return 0;
        }

        $adCount = $this->getRepository()->getRunningFrontPageAdCount();

        return $max - $adCount;
    }

    public function incrementEmailApplicationCount(Job $job)
    {
        $affectedRows = $this->getManager()
            ->createQuery('UPDATE ' . $this->getClass() . ' j SET j.emailApplicantCount = j.emailApplicantCount + 1 WHERE j.id = :jobId')
            ->setParameter('jobId', $job->getId())
            ->execute()
        ;

        return $affectedRows;
    }

    /**
     * @param Job $job
     */
    public function save($job)
    {
        if (!$job->getId()) {
            $title = strtolower($job->getTitleOfPositionOpen());

            // find focuses
            /** @var Focus[] $focuses */
            $focuses = $this->focusManager->findAll();
            if ($focuses) {
                foreach ($focuses as $focus) {
                    $focusName = strtolower($focus->getFocus());
                    if (strpos($title, $focusName) !== false) {
                        $job->addFocus($focus);
                    } else if ($focus->getKeywords()) {
                        $keywords = $focus->getKeywordsArray();
                        foreach ($keywords as $keyword) {
                            $keyword = strtolower(trim($keyword));
                            if (strpos($keyword, $focusName) !== false) {
                                $job->addFocus($focus);
                                break;
                            }
                        }
                    }
                }
            }

            // find positions
            /** @var Position $position */
            $position = $this->positionManager->find(511);
            if ($position) {
                $keywords = array('Special Education', 'Learning Specialist', 'Special Needs');
                foreach ($keywords as $keyword) {
                    if (strpos($title, strtolower($keyword)) !== false) {
                        $job->setPosition($position);
                        break;
                    }
                }

                if (!$job->getPosition() && strpos($title, strtolower('substitute')) !== false) {
                    $position = $this->positionManager->find(552);
                    $job->setPosition($position);
                }
            }
        }

        parent::save($job);
    }

    public function deleteCompanyImage(Job $job)
    {
        $currentImage = $job->getCompanyImage();
        if ($currentImage) {
            $baseDirectory = $this->webDir;
            $currentImagePath = $baseDirectory . $currentImage;
            if (file_exists($currentImagePath)) {
                unlink($currentImagePath);
            }
        }
        $job->setCompanyImage(null);
    }

    public function uploadCompanyImage(Job $job, File $image)
    {
        $this->deleteCompanyImage($job);
        $baseDirectory = $this->webDir;
        $path = $this->imageDir . '/';
        $directory = $baseDirectory . $path;
        $file =  uniqid() . '.' . $image->guessExtension();
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $image->move($directory, $file);
        $this->resizeCompanyImage($directory . $file);
        chmod($directory . $file, 0666);
        $job->setCompanyImage($path . $file);
    }

    public function resizeCompanyImage($path)
    {
        $imageHandler = $this->imageHandling->open($path);
        if ($imageHandler->width() > 250 || $imageHandler->height() > 145) {
            if ($imageHandler->width() > 250) {
                $imageHandler->cropResize(250);
            }
            if ($imageHandler->height() > 145) {
                $imageHandler->cropResize(null, 145);
            }
            $imageHandler->save($path);
        }
    }
}