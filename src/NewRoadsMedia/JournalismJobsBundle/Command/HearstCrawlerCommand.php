<?php

namespace NewRoadsMedia\JournalismJobsBundle\Command;

use Doctrine\DBAL\DBALException;
use NewRoadsMedia\FrontendBundle\Command\BaseCrawler;
use NewRoadsMedia\FrontendBundle\Entity\Employer;
use NewRoadsMedia\FrontendBundle\Entity\Industry;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Entity\JobType;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\DomCrawler\Crawler;

class HearstCrawlerCommand extends BaseCrawler
{
    protected function configure()
    {
        $this
            ->setName('jj:hearst')
            ->setHelp('Imports jobs from Hearst')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jobManager = $this->getContainer()->get('journalismjobs.manager.job');
        $locationManager = $this->getContainer()->get('journalismjobs.manager.location');
        $industryManager = $this->getContainer()->get('journalismjobs.manager.industry');
        $jobTypeManager = $this->getContainer()->get('journalismjobs.manager.job_type');
        $jobManager->getManager()->getConnection()->getConfiguration()->setSQLLogger(null);
        $command = $this;

        /** @var Industry $tvIndustry */
        $tvIndustry = $industryManager->getReference(2);

        /** @var Employer $employer */
        $employer = $this->getContainer()->get('journalismjobs.manager.employer')->findOneByEmail('careers@hearst.com');
        //https://hearst.referrals.selectminds.com/television/jobs/search/53611
        //$baseUrl = 'http://careers.hearsttelevision.com';
        $baseUrl = 'https://hearst.referrals.selectminds.com/television/jobs/search/';
        $page = 1;
        do {
            $url = ($page == 1) ? $baseUrl . '724872' : $baseUrl . '724872/' . $page;
            if ($input->getOption('verbose')) {
                $output->writeln('Crawling parent ' . $url . '...');
            }
            $response = $command->crawl($url);
            $crawler = new Crawler($response);
            $pageCount = (int) $crawler->filter('#jPaginateNumPages')->text();
            $nodes = $crawler->filter('.job_list_row');
            $nodes->each(function (Crawler $node, $index) use ($jobManager, $locationManager, $jobTypeManager, $tvIndustry, $employer, $baseUrl, $command, $input, $output) {
                
                $anchor = $node->first()->filter('a');
                $key = 'hearst-' . $anchor->text();
                $link = $anchor->attr('href');
                $title = $anchor->text();

                $company = trim($node->filter('.jlr_company')->text());
                $locationNode = trim($node->filter('.jlr_cat_loc .location')->text());
                $city = trim(explode(',', $locationNode)[0]);
                $state = trim(explode(',', $locationNode)[1]);

                $positionType = trim($node->filter('.jlr_cat_loc .category')->text());
                
                if ($command->shouldExclude($title)) {
                    return;
                }

                $job = $jobManager->findOneByJobKey($key);
                $doesJobExist = true;
                if (!$job) {
                    $doesJobExist = false;
                    $job = $jobManager->create();
                }
    
                if ($input->getOption('verbose')) {
                    $output->writeln('Crawling (' . $index . ') ' . $link . '...');
                }
                
                $jobCrawler = new Crawler($command->crawl($link));
                $adDescription = $jobCrawler->filter('.job_description')->html();
                $adDescription = '
<style type="text/css">
.field-name{display:inline-block;min-width:100px;}
.field-value{display:inline-block;}
</style>' . $adDescription;

                    /** @var JobType $jobType */
                    $jobType = $jobTypeManager->findOneByJobType($positionType);
                    $location = $locationManager->findOneByLocationDescription($state);
                    //$createDate = $command->createDate($date);
                    $now = new \DateTime('now');


                    $job->setAdDescription($adDescription);
                    $job->setJobKey($key);
		            if (!$doesJobExist) {
                        $job->setCreateDate($now);
		            }
                    $command->setExpiration($job);
                    $job->setTitleOfPositionOpen($title);
                    $job->setIncomplete(null);
                    $job->setApplyType('url');
                    $job->setApplyUrl($link);
                    $job->setEmployer($employer);
                    $job->setCompany($company);
                    $job->addIndustry($tvIndustry);
                    $job->setLocation($location);
                    $job->setJobType($jobType);
                    $job->setCity($city);
                    $job->setJobCity($city);
                    $job->setCityStateCountry($city . ', ' . $state);

                    $jobManager->save($job);
                    $jobManager->getManager()->clear('NewRoadsMedia\FrontendBundle\Entity\Job');

                
            });
            $page++;
            
            /*$nodes    
                ->each(function (Crawler $node, $index) use ($jobManager, $locationManager, $jobTypeManager, $tvIndustry, $employer, $baseUrl, $command, $input, $output) {
                    echo $index;
                    
                    $cells = $node->filter('.job_list_row .jlr_Odd');
                    
                    $anchor = $cells->first()->filter('a');
                    $key = 'hearst-' . $anchor->text();
                    $link = $baseUrl . $anchor->attr('href');
                    $title = $cells->eq(1)->filter('a')->text();

                    if ($command->shouldExclude($title)) {
                        return;
                    }

                    $job = $jobManager->findOneByJobKey($key);
		    $doesJobExist = true;
                    if (!$job) {
			$doesJobExist = false;
                        $job = $jobManager->create();
                    }

                    if ($input->getOption('verbose')) {
                        $output->writeln('Crawling (' . $index . ') ' . $link . '...');
                    }
                    $jobCrawler = new Crawler($command->crawl($link));
                    $adDescription = $jobCrawler->filter('.jobdescriptiontbl')->html();
                    $adDescription = '
<style type="text/css">
.field-name{display:inline-block;min-width:100px;}
.field-value{display:inline-block;}
</style>' . $adDescription;

                    $company = trim($cells->eq(2)->text());
                    $city = trim($cells->eq(3)->text());
                    $state = trim($cells->eq(4)->text());
                    $positionType = explode('/', trim($cells->eq(5)->text()));
                    $positionType = array_shift($positionType);
                    $date = str_replace('-', '/', trim($cells->eq(6)->text()));

                    /** @var JobType $jobType */
                    /*$jobType = $jobTypeManager->findOneByJobType($positionType);
                    $location = $locationManager->findOneByLocationAbbreviation($state);
                    $createDate = $command->createDate($date);
                    $now = new \DateTime('now');

                    // if expiration is older than 4 weeks then set null so the $command->setExpiration() will run on it
		            $fourWeeksInSeconds = 4*7*24*60*60;
                    if ($now->getTimestamp() - $createDate->getTimestamp() > $fourWeeksInSeconds) {
                        $job->setExpirationDate(null);
                    }

                    $job->setAdDescription($adDescription);
                    $job->setJobKey($key);
		            if (!$doesJobExist) {
                        $job->setCreateDate($createDate);
		            }
                    /*$command->setExpiration($job);
                    $job->setTitleOfPositionOpen($title);
                    $job->setIncomplete(null);
                    $job->setApplyType('url');
                    $job->setApplyUrl($link);
                    $job->setEmployer($employer);
                    $job->setCompany($company);
                    $job->addIndustry($tvIndustry);
                    $job->setLocation($location);
                    $job->setJobType($jobType);
                    $job->setCity($city);
                    $job->setJobCity($city);
                    $job->setCityStateCountry($city . ', ' . $state);

                    $jobManager->save($job);
                    $jobManager->getManager()->clear('NewRoadsMedia\FrontendBundle\Entity\Job');
                });*/
        } while($page < $pageCount);
    }
}
