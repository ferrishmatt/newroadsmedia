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

class SinclairBroadcastGroupCrawlerCommand extends BaseCrawler
{
    protected function configure()
    {
        $this
            ->setName('jj:sinclair-broadcast-group')
            ->setHelp('Imports jobs Sinclair Broadcast Groups')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jobManager = $this->getContainer()->get('journalismjobs.manager.job');
        $locationManager = $this->getContainer()->get('journalismjobs.manager.location');
        $industryManager = $this->getContainer()->get('journalismjobs.manager.industry');
        /** @var JobType[] $jobTypes */
        $jobTypes = $jobTypeManager = $this->getContainer()->get('journalismjobs.manager.job_type')->findAll();
        $jobManager->getManager()->getConnection()->getConfiguration()->setSQLLogger(null);
        $command = $this;

        /** @var Industry $tvIndustry */
        $tvIndustry = $industryManager->getReference(2);

        /** @var Employer $employer */
        $employer = $this->getContainer()->get('journalismjobs.manager.employer')->findOneByEmail('employment@sbgtv.com');

        $baseUrl = 'https://sbgtv-openhire.silkroad.com/epostings/';
        $url = $baseUrl . 'index.cfm?fuseaction=app.jobsearch';
        if ($input->getOption('verbose')) {
            $output->writeln('Crawling parent ' . $url . '...');
        }
        $response = $command->crawl($url);
        $crawler = new Crawler($response);
        $crawler
            ->filter('table.cssSearchResults tr')
            ->each(function (Crawler $node) use ($jobManager, $locationManager, $jobTypes, $tvIndustry, $employer, $baseUrl, $command, $input, $output) {
                if ($node->attr('class') == 'cssSearchResultsColHead') {
                    return;
                }

                $columns = $node->filter('td');
                $anchor = $columns->eq(1)->filter('a');
                $link = $baseUrl . $anchor->attr('href');
                $title = trim($anchor->text());
                $key = preg_match('/jobid=(\d+?)\&.*JobOwner=(\d+?)\&/', $link, $matches) ? 'sinclair-broadcast-group-' . $matches[1] . '-' . $matches[2] : null;

                /** @var Job $existingJob */
                $job = $jobManager->findOneByJobKey($key);
		$doesJobExist = true;
                if (!$job) {
		    $doesJobExist = false;
                    $job = $jobManager->create();
                }

                $date = $columns->last()->text();

                $createDate = $command->createDate($date);
		$createDate->setTime(3, 0);
                $expirationDate = clone $createDate;
                $now = new \DateTime('now');
                do {
                    $expirationDate->modify('+5 weeks 03:00:00');
                } while ($expirationDate < $now);
                $job->setCreateDate($createDate);
                $job->setExpirationDate($expirationDate);

                $cityStateCountry = $columns->eq(2)->text();
                $job->setTitleOfPositionOpen($title);
                $job->addIndustry($tvIndustry);
                $job->setCityStateCountry($cityStateCountry);
                $job->setIncomplete(null);
                $job->setApplyType('url');
                $job->setApplyUrl($link);
                $job->setEmployer($employer);
                list($city, $state, $country) = explode(', ', $cityStateCountry);
                if (strtolower(trim($country)) == 'united states') {
                    $location = $locationManager->findOneByLocationDescription($state);
                    if ($location) {
                        $job->setLocation($location);
                    } else if ($input->getOption('verbose')) {
                        $output->writeln(sprintf('No location found for "%s".', $state));
                    }
                }
                if ($city) {
                    $job->setCity($city);
                    $job->setJobCity($city);
                }
                $job->setJobKey($key);

                if ($input->getOption('verbose')) {
                    $output->writeln('Crawling ' . $link . '...');
                }

                $jobCrawler = new Crawler($command->crawl($link));
                // ad description
                $adDescription = '';
                try {
                    $adDescription = trim($jobCrawler->filter('#jobDesciptionDiv')->html());
                } catch (\InvalidArgumentException $e) {
                }
                $job->setAdDescription($adDescription);

                // company
                /*try {
                    //$company = trim($jobCrawler->filter('#jobCompanyLocationDiv')->text());
                    //if (strpos(strtoupper($company), 'RADIO') === false && preg_match_all('/\b([A-Z]{4})\b/', $company, $matches)) {
                    //    foreach ($matches[1] as $match) {
                    //        $company = str_replace($match, $match . '-TV', $company);
                    //    }
                    //}
		            $company = "Sinclair Broadcast Group";
                    $job->setCompany($company);
                } catch (\InvalidArgumentException $e) {
                    $job->setCompany($employer->getCompany());
                }*/
                // company
                try {
                    $company = trim($jobCrawler->filter('#jobCompanyLocationDiv')->text());
                    if (strpos(strtoupper($company), 'RADIO') === false && preg_match_all('/\b([A-Z]{4})\b/', $company, $matches)) {
                        foreach ($matches[1] as $match) {
                            $company = str_replace($match, $match . '-TV', $company);
                        }
                    }

                    $company = substr_replace($company, '', strpos($company, '/'), strlen($company));
                    $company = str_replace('HR.', '', $company);
                    
                    $job->setCompany($company);
                } catch (\InvalidArgumentException $e) {
                    $job->setCompany($employer->getCompany());
                }

                // job type
                try {
                    $positionType = trim($jobCrawler->filter('#translatedJobPostingTypeDiv')->text());
                    $positions = explode('/', $positionType);
                    foreach ($positions as $position) {
                        $positionString = strtolower($position);
                        foreach ($jobTypes as $jobType) {
                            $jobTypeString = strtolower($jobType->getJobType());
                            $pos = strpos($jobTypeString, $positionString);
                            if ($pos !== false) {
                                $job->setJobType($jobType);
                                break 2;
                            }
                        }
                    }
                } catch (\InvalidArgumentException $e) {
                    // do nothing
                }

                if ($command->shouldExclude($job->getTitleOfPositionOpen())) {
                    return;
                }

                $jobManager->save($job);
                $jobManager->getManager()->clear('NewRoadsMedia\FrontendBundle\Entity\Job');
            })
        ;

//        $expiredExpirationDate = new \DateTime('yesterday 03:00:00');
//        $qb = $jobManager->getRepository()->createQueryBuilder('job')
//            ->update()
//            ->set('job.expirationDate', ':expiredExpirationDate')
//            ->where('job.employer = :employer')
//            ->andWhere('job.expirationDate < :expirationDate')
//            ->andWhere('job.jobKey IS NOT NULL')
//            ->setParameters(array(
//                'employer' => $employer,
//                'expirationDate' => $expirationDate->format('Y-m-d'),
//                'expiredExpirationDate' => $expiredExpirationDate->format('Y-m-d H:i:s'),
//            ))
//        ;
//        $qb->getQuery()->execute();
    }
}
