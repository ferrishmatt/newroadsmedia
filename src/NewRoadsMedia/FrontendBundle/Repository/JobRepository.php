<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use NewRoadsMedia\FrontendBundle\Entity\DatePosted;
use NewRoadsMedia\FrontendBundle\Entity\Employer;
use NewRoadsMedia\FrontendBundle\Entity\Notification;

class JobRepository extends EntityRepository
{
    protected function createJobQueryBuilder($alias)
    {
        return $this->createQueryBuilder($alias)
            ->where(sprintf('%s.expirationDate >= CURRENT_TIMESTAMP()', $alias))
        ;
    }

    protected function andWhereKeywordsLike(QueryBuilder $qb, $alias, $keywords, $locationAlias = 'location')
    {
        $keywords = trim($keywords);
        $qb
            ->andWhere(sprintf('(
                %s.titleOfPositionOpen LIKE :keywords OR
                %s.adDescription LIKE :keywords OR
                %s.company LIKE :keywords OR
                %s.jobCity LIKE :keywords OR
                %s.city LIKE :keywords OR
                %s.locationAbbreviation LIKE :keywords OR
                %s.locationDescription LIKE :keywords)'
                , $alias
                , $alias
                , $alias
                , $alias
                , $alias
                , $locationAlias
                , $locationAlias
            ))
            ->setParameter('keywords', '%' . $keywords. '%')
        ;

        return $qb;
    }

    protected function createSearchJobsQueryBuilder($alias, $options)
    {
        $qb = $this->createJobQueryBuilder($alias)
            ->select(sprintf('partial %s.{id, titleOfPositionOpen, company, isExternal, website, jobCity, createDate, companyImage}', $alias)
                , 'partial location.{id, locationDescription}'
            )
            ->leftJoin(sprintf('%s.location', $alias), 'location')
        ;

        // if pr is set override
        if (isset($options['pr']) && $options['pr'] == 1) {
            $positionIds = array(85,26,44);
            $industryIds = array(6);

            $qb = $this->createJobQueryBuilder('job')
                ->join('job.industries', 'industry')
                ->andWhere('(industry.id IN (:industryIds) OR job.position IN (:positionIds))')
                ->setParameter('industryIds', $industryIds)
                ->setParameter('positionIds', $positionIds)
            ;

            return $qb;
        }

        if (!empty($options['keywords'])) {
            $this->andWhereKeywordsLike($qb, $alias, $options['keywords']);
        }
        if (!empty($options['industry'])) {
            $qb
                ->join(sprintf('%s.industries', $alias), 'industry')
                ->andWhere('industry IN (:industry)')
                ->setParameter('industry', $options['industry'])
            ;
        }
        if (!empty($options['nonFocused']) && $options['nonFocused']) {
            unset($options['focus']);
            $key = 'focus';
            $qb
                ->leftJoin(sprintf('%s.%s', $alias, $key), $key)
                ->andWhere(sprintf('%s IS NULL', $key))
            ;
        }
        foreach (array('position', 'location', 'jobType', 'salary', 'focus') as $key) {
            if (!empty($options[$key])) {
                $value = $options[$key];
                if ($value instanceof ArrayCollection) {
                    $value = $value->toArray();
                }

                if (is_array($value) && count($value) == 0) {
                    continue;
                }

                if ($key != 'location') {
                    $qb
                        ->join(sprintf('%s.%s', $alias, $key), $key)
                    ;
                }
                $qb->setParameter($key, $value);
                if (is_array($value)) {
                    $qb->andWhere(sprintf('%s IN (:%s)', $key, $key));
                } else {
                    $qb->andWhere(sprintf('%s = :%s', $key, $key));
                }
            }
        }
        if (array_key_exists('datePosted', $options) && $options['datePosted'] instanceof DatePosted) {
            /** @var DatePosted $datePosted */
            $datePosted = $options['datePosted'];
            $qb
                ->andWhere('DATE(CONVERT_TZ(job.createDate, :serverTimezone, :clientTimezone)) BETWEEN DATE(DATE_SUB(CONVERT_TZ(CURRENT_TIMESTAMP(), :serverTimezone, :clientTimezone), :rangeEnd, \'DAY\')) AND DATE(DATE_SUB(CONVERT_TZ(CURRENT_TIMESTAMP(), :serverTimezone, :clientTimezone), :rangeStart, \'DAY\'))')
                ->setParameter('rangeStart', $datePosted->getRangeStart())
                ->setParameter('rangeEnd', $datePosted->getRangeEnd())
                ->setParameter('serverTimezone', 'America/New_York')
                ->setParameter('clientTimezone', 'America/Los_Angeles')
            ;
        }
        if (!empty($options['diversity'])) {
            $qb
                ->andWhere(sprintf('%s.diversity IN (:diversity)', $alias))
                ->setParameter('diversity', array(
                    'only',
                    'both',
                    'Both',
                ))
            ;
        }

        if (!empty($options['certificationNeeded'])) {
            $qb
                ->andWhere(sprintf('%s.certificationNeeded = :certificationNeeded', $alias))
                ->setParameter('certificationNeeded', $options['certificationNeeded'])
            ;
        }

        if (!empty($options['excludedEmployerIDs'])){
            $qb
                ->andWhere(sprintf('%s.employerID NOT IN (:employerIDs)', $alias))
                ->setParameter('employerIDs', $options['excludedEmployerIDs'])
            ;
        }

        return $qb;
    }

    public function employerHasActiveJob(Employer $employer)
    {
        $qb = $this->createJobQueryBuilder('job')
            ->select('COUNT(DISTINCT job.id) job_count')
            ->where('job.employer = :employer')
            ->andWhere('job.expirationDate >= CURRENT_TIMESTAMP()')
            ->setParameter('employer', $employer)
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['job_count']) && $result['job_count'] > 0 ? true : false;
    }

    public function createEmployerJobQuery(Employer $employer, $alias)
    {
        return $this->createQueryBuilder($alias)
            ->where(sprintf('%s.employer = :employer', $alias))
            ->setParameters(array(
                'employer' => $employer,
            ))
        ;
    }

    public function findActive($jobId)
    {
        $qb = $this->createQueryBuilder('job')
            ->where('job.id = :id')
            ->andWhere('job.incomplete = :n OR job.incomplete IS NULL')
            ->setParameters(array(
                'id' => $jobId,
                'n' => 'N',
            ));
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return $result;
    }

    public function getEmployerJobCount(Employer $employer)
    {
        $qb = $this->createEmployerJobQuery($employer, 'job')
            ->select('COUNT(DISTINCT job.id) job_count')
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['job_count']) ? $result['job_count'] : 0;
    }

    public function getEmployerJobs(Employer $employer, $maxResults = null, $firstResult = null)
    {
        $qb = $this->createEmployerJobQuery($employer, 'job')
            ->addOrderBy('job.expirationDate', 'DESC')
        ;
        $query = $qb->getQuery();
        if ($maxResults) {
            $query->setMaxResults($maxResults);
        }
        if ($firstResult) {
            $query->setFirstResult($firstResult);
        }
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function getExpiringJobsQuery()
    {
        $startTime = new \DateTime('+3 days');
        $endTime = new \DateTime('+4 days');
        $qb = $this->createQueryBuilder('job')
            ->select('partial job.{id, titleOfPositionOpen, expirationDate}'
                , 'partial employer.{id, email, password}'
            )
            ->join('job.employer', 'employer')
            ->where('job.expirationDate BETWEEN :startTime AND :endTime')
            ->setParameters(array(
                'startTime' => $startTime,
                'endTime' => $endTime,
            ))
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);

        return $query;
    }

    public function getFeaturedJobs()
    {
        $qb = $this->createJobQueryBuilder('job')
            ->select(array(
                'partial job.{id, titleOfPositionOpen, company, jobCity}',
                'partial location.{id, locationDescription}'
            ))
            ->leftJoin('job.location', 'location')
            ->andWhere('job.featureExpirationDate > CURRENT_TIMESTAMP()')
            ->orderBy('job.featureExpirationDate', 'DESC')
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function getJobCount()
    {
        $qb = $this->createJobQueryBuilder('job')
            ->select('COUNT(DISTINCT job.id) row_count')
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 60);
        $result = $query->getSingleResult();

        return isset($result['row_count']) ? $result['row_count'] : 0;
    }

    public function getJobCountForFocus()
    {
        $qb = $this->createJobQueryBuilder('job')
            ->select('COUNT(DISTINCT job.id) row_count')
            ->join('job.focus', 'focus')
        ;

        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 60);
        $result = $query->getSingleResult();

        return isset($result['row_count']) ? $result['row_count'] : 0;
    }

    public function getJobCountForPublicRelations()
    {
        $positionIds = array(85,26,44);
        $industryIds = array(6);

        $qb = $this->createJobQueryBuilder('job')
            ->select('COUNT(DISTINCT job.id) row_count')
            ->join('job.industries', 'industry')
            ->andWhere('(industry.id IN (:industryIds) OR job.position IN (:positionIds))')
            ->setParameter('industryIds', $industryIds)
            ->setParameter('positionIds', $positionIds)
        ;

        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['row_count']) ? $result['row_count'] : 0;
    }

    public function getJobCountBy($industryIds = null, $positionIds = null, $jobTypeIds = null, $diversity = null)
    {
        if ($industryIds && !is_array($industryIds)) {
            $industryIds = array($industryIds);
        }
        if ($positionIds && !is_array($positionIds)) {
            $positionIds = array($positionIds);
        }
        if ($jobTypeIds && !is_array($jobTypeIds)) {
            $jobTypeIds = array($jobTypeIds);
        }

        $qb = $this->createJobQueryBuilder('job')
            ->select('COUNT(DISTINCT job.id) row_count')
        ;
        if ($industryIds) {
            $qb
                ->join('job.industries', 'industry')
                ->andWhere('industry IN (:industryIds)')
                ->setParameter('industryIds', $industryIds)
            ;
        }
        if ($positionIds) {
            $qb
                ->andWhere('job.position IN (:positionIds)')
                ->setParameter('positionIds', $positionIds)
            ;
        }
        if ($jobTypeIds) {
            $qb
                ->andWhere('job.jobType IN (:jobTypeIds)')
                ->setParameter('jobTypeIds', $jobTypeIds)
            ;
        }
        if ($diversity === true) {
            $qb
                ->andWhere('job.diversity IN (:diversity)')
                ->setParameter('diversity', array(
                    'only',
                    'both',
                    'Both',
                ))
            ;
        }
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['row_count']) ? $result['row_count'] : 0;
    }

    public function getJobCountByLocation($limit = null)
    {
        $qb = $this->createJobQueryBuilder('job')
            ->select('location.id'
                , 'location.locationDescription description'
                , 'COUNT(DISTINCT job.id) row_count'
            )
            ->join('job.location', 'location')
            ->andWhere('job.incomplete = :n OR job.incomplete IS NULL')
            ->andWhere('job.delayed = :n OR job.delayed IS NULL')
            ->groupBy('location.locationDescription')
            ->addGroupBy('location.id')
            ->orderBy('row_count', 'DESC')
            ->setParameters(array(
                'n' => 'N',
            ))
        ;
        $query = $qb->getQuery();
        if ($limit) {
            $query->setMaxResults($limit);
        }
        $query->useQueryCache(true);
        $result = $query->getResult();

        usort($result, function($a, $b) {
            return strnatcmp($a['description'], $b['description']);
        });

        return $result;
    }

    public function getJobCountByPopularity()
    {
        $positionIds = array(95, 827, 16, 26, 13, 37, 85, 28, 38, 802, 865);
        $qb = $this->createJobQueryBuilder('job')
            ->select('position.id'
                , 'position.position description'
                , 'COUNT(DISTINCT job.id) row_count'
            )
            ->join('job.position', 'position')
            ->andWhere('position in (:positionIds)')
            ->groupBy('position.id')
            ->setParameters(array(
                'positionIds' => $positionIds,
            ))
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getResult();

        usort($result, function($a, $b) use ($positionIds) {
            $aIndex = array_search($a['id'], $positionIds);
            $bIndex = array_search($b['id'], $positionIds);

            return $aIndex < $bIndex ? -1: 1;
        });

        return $result;
    }

    public function getJobsByNotification(Notification $notification)
    {
        $dateTime = new \DateTime('-1 days 18:30:00');
        $qb = $this->createJobQueryBuilder('job')
            ->leftJoin('job.focus', 'focus')
            ->andWhere('job.createDate >= :dateTime')
            ->andWhere('job.incomplete = :n OR job.incomplete IS NULL')
            ->groupBy('job.id')
            ->setParameters(array(
                'dateTime' => $dateTime,
                'n' => 'N',
            ))
        ;
        if ($notification->getIndustry()) {
            $qb
                ->join('job.industries', 'industry')
                ->andWhere('industry = :industry')
                ->setParameter('industry', $notification->getIndustry())
            ;
        }
        if ($notification->getPosition()) {
            $qb->andWhere('job.position = :position')->setParameter('position', $notification->getPosition());
        }
        if ($notification->getSpecialty()) {
            $qb->andWhere('job.specialty = :specialty')->setParameter('specialty', $notification->getSpecialty());
        }
        if ($notification->getMarket()) {
            $qb->andWhere('job.market = :market')->setParameter('market', $notification->getMarket());
        }
        if ($notification->getExperience()) {
            $qb->andWhere('job.experience = :experience')->setParameter('experience', $notification->getExperience());
        }
        if ($notification->getEducation()) {
            $qb->andWhere('job.education = :education')->setParameter('education', $notification->getEducation());
        }
        if ($notification->getLocation()) {
            $qb->andWhere('job.location = :location')->setParameter('location', $notification->getLocation());
        }
        if ($notification->getJobType()) {
            $qb->andWhere('job.jobType = :jobType')->setParameter('jobType', $notification->getJobType());
        }
        if ($notification->getSalary()) {
            $qb->andWhere('job.salary = :salary')->setParameter('salary', $notification->getSalary());
        }
        if ($notification->getFocus()->count() == 0) {
            if ($notification->getHasNonFocused()) {
                $qb->andWhere('focus IS NULL');
            }
        } else {
            $expr = $qb->expr()->orX();
            $expr->add('focus IN (:focuses)');
            if ($notification->getHasNonFocused()) {
                $expr->add('focus IS NULL');
            }
            $qb->andWhere($expr)->setParameter('focuses', $notification->getFocus()->toArray());
        }

        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function getLatestFocusedJobs($maxResults = 4)
    {
        $qb = $this->createJobQueryBuilder('job')
            ->select('partial job.{id, titleOfPositionOpen, jobCity, company, createDate}'
                , 'partial focus.{id, focus}'
                , 'partial location.{id, locationAbbreviation}'
            )
            ->join('job.focus', 'focus')
            ->leftJoin('job.location', 'location')
            ->where('job.incomplete = :n OR job.incomplete IS NULL')
            ->andWhere('job.expirationDate >= CURRENT_TIMESTAMP()')
            ->groupBy('job.id')
            ->orderBy('job.createDate', 'DESC')
            ->setParameter('n', 'N')
        ;
        $query = $qb->getQuery();
        $query->setMaxResults($maxResults);
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function getLatestNonFocusedJobs($maxResults = 4)
    {
        $qb = $this->createJobQueryBuilder('job')
            ->select('partial job.{id, titleOfPositionOpen, jobCity, company, createDate}'
                , 'partial location.{id, locationAbbreviation}'
            )
            ->leftJoin('job.focus', 'focus')
            ->leftJoin('job.location', 'location')
            ->where('job.incomplete = :n OR job.incomplete IS NULL')
            ->andWhere('job.expirationDate >= CURRENT_TIMESTAMP()')
            ->andWhere('focus IS NULL')
            ->groupBy('job.id')
            ->orderBy('job.createDate', 'DESC')
            ->setParameter('n', 'N')
        ;
        $query = $qb->getQuery();
        $query->setMaxResults($maxResults);
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function getLatestJobListings($maxResults = 8)
    {
        $qb = $this->createJobQueryBuilder('job')
            ->where('job.incomplete = :n OR job.incomplete IS NULL')
            ->andWhere('job.expirationDate >= CURRENT_TIMESTAMP()')
            ->orderBy('job.createDate', 'DESC')
            ->setParameter('n', 'N')
        ;
        $query = $qb->getQuery();
        $query->setMaxResults($maxResults);
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function searchJobs($options, $debug = false)
    {
        $qb = $this->createSearchJobsQueryBuilder('job', $options);

        $qb
            ->groupBy('job.id')
            ->addOrderBy('job.createDate', 'DESC')
        ;
        $query = $qb->getQuery();


        if ($debug){
            print '<!--';
            // \Doctrine\Common\Util\Debug::dump($qb->getEntityManager(), 4);
            \Doctrine\Common\Util\Debug::dump($query->getSql(), 4);
            \Doctrine\Common\Util\Debug::dump($query->getParameters(), 4);
            print '-->';
        }

        if (!empty($options['maxResults'])) {
            $query->setMaxResults($options['maxResults']);
        }
        if (!empty($options['firstResult'])) {
            $query->setFirstResult($options['firstResult']);
        }
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function searchJobsCount($options)
    {
        $qb = $this->createSearchJobsQueryBuilder('job', $options);
        $qb->select('COUNT(DISTINCT job.id) row_count');
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['row_count']) ? $result['row_count'] : 0;
    }

    public function getRunningFrontPageAdCount()
    {
        $qb = $this->createJobQueryBuilder('jobs')
            ->select('COUNT(DISTINCT jobs.id) job_count')
            ->andWhere('jobs.incomplete = :n OR jobs.incomplete IS NULL')
            ->andWhere('jobs.frontPageExpirationDate >= CURRENT_TIMESTAMP()')
            ->setParameter('n', 'N')
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['job_count']) ? $result['job_count'] : 0;
    }

    public function getFrontPageFeaturedJobListings($maxResults = null)
    {
        $qb = $this->createJobQueryBuilder('job')
            ->select('partial job.{id, isExternal, website, titleOfPositionOpen, jobCity, createDate, company, companyImage}'
                , 'partial location.{id, locationDescription, locationAbbreviation}'
            )
            ->leftJoin('job.location', 'location')
            ->andWhere('job.incomplete = :n OR job.incomplete IS NULL')
            ->andWhere('job.frontPageExpirationDate >= CURRENT_TIMESTAMP()')
            ->orderBy('job.frontPageExpirationDate', 'DESC')
            ->setParameter('n', 'N')
        ;
        if ($maxResults !== null) {
            $qb->setMaxResults($maxResults);
        }
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    protected function createCountsBuilder($options, $skip = null)
    {
        $qb = $this->createJobQueryBuilder('job')
            ->leftJoin('job.industries', 'industry')
            ->leftJoin('job.location', 'location')
            ->leftJoin('job.jobType', 'jobType')
            ->leftJoin('job.position', 'position')
        ;

        foreach (array('industry', 'location', 'jobType' , 'position') as $key) {
            if (!empty($options[$key]) && $skip != $key) {
                $property = $key . 'Entity';
                $qb->andWhere(sprintf('%s.id IN (:%s)', $key, $property))->setParameter($property, $options[$key]);
            }
        }
        if (!empty($options['datePosted']) && $skip != 'datePosted') {
            $qb
                ->leftJoin('NewRoadsMediaFrontendBundle:DatePosted' , 'datePosted', 'WITH'
                    , 'DATE(CONVERT_TZ(job.createDate, :serverTimezone, :clientTimezone)) BETWEEN DATE(DATE_SUB(CONVERT_TZ(CURRENT_TIMESTAMP(), :serverTimezone, :clientTimezone), datePosted.rangeEnd, \'DAY\')) AND DATE(DATE_SUB(CONVERT_TZ(CURRENT_TIMESTAMP(), :serverTimezone, :clientTimezone), datePosted.rangeStart, \'DAY\'))'
                )
                ->andWhere(':days BETWEEN datePosted.rangeStart AND datePosted.rangeEnd')->setParameter('days', $options['datePosted'])
                ->setParameter('serverTimezone', 'America/New_York')
                ->setParameter('clientTimezone', 'America/Los_Angeles')
            ;
        }
        if (!empty($options['keywords'])) {
            $this->andWhereKeywordsLike($qb, 'job', $options['keywords']);
        }
        if (!empty($options['diversity']) && $options['diversity']) {
            $qb
                ->andWhere('job.diversity IN (:diversity)')
                ->setParameter('diversity', array(
                    'only',
                    'both',
                    'Both',
                ))
            ;
        }

        return $qb;
    }

    public function getIndustryCounts($options)
    {
        $qb = $this->createCountsBuilder($options, 'industry')
            ->select('industry.id', 'industry.industry name', 'COUNT(DISTINCT job.id) job_count', 'industry.displayOrder')
            ->andWhere('industry.id IS NOT NULL')
            // ->andWhere('industry.id != 2')
            // ->andWhere('industry.id != 3')
            ->groupBy('industry.id')
            ->orderBy('industry.displayOrder', 'ASC')
        ;

        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getArrayResult();

        // $qb = $this->createCountsBuilder($options, 'industry')
        //     ->select('3 as id', 'industry.industry name', 'COUNT(DISTINCT job.id) job_count', 'industry.displayOrder')
        //     ->andWhere('industry.id in (2,3)')
        //     ->orderBy('industry.displayOrder', 'ASC')
        // ;

        // $query = $qb->getQuery();
        // $query->useQueryCache(true);
        // $result2 = $query->getArrayResult();

        // $results = array_merge($result, $result2);
        $results = $result;

        usort($results, function ($first, $second) {
            if ($first == $second) {
                return 0;
            }
            return $first['displayOrder'] < $second['displayOrder'] ? -1 : 1;
        });

        return $results;
    }

    public function getLocationCounts($options)
    {
        $qb = $this->createCountsBuilder($options, 'location')
            ->select('location.id', 'location.locationDescription name', 'COUNT(DISTINCT job.id) job_count')
            ->andWhere('location.id IS NOT NULL')
            ->groupBy('location.id')
            ->orderBy('job_count', 'DESC')
        ;

        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function getJobTypeCounts($options, $jobTypeId = null)
    {
        $qb = $this->createCountsBuilder($options, 'jobType')
            ->select('jobType.id', 'jobType.jobType name', 'COUNT(DISTINCT job.id) job_count')
            ->andWhere('jobType.id IS NOT NULL')
            ->groupBy('jobType.id')
            ->orderBy('jobType.displayOrder', 'ASC')
        ;
        if ($jobTypeId !== null) {
            $qb->andWhere('jobType.id = :jobTypeId')->setParameter('jobTypeId', $jobTypeId);
        }

        $query = $qb->getQuery();
        $query->useQueryCache(true);

        if ($jobTypeId !== null) {
            $result = $query->getOneOrNullResult();

            return isset($result['job_count']) ? $result['job_count'] : 0;
        }

        $result = $query->getResult();

        return $result;
    }

    public function getDiversityCount($options)
    {
        $options['diversity'] = 1;
        $qb = $this->createCountsBuilder($options, 'diversity')
            ->select('COUNT(DISTINCT job.id) job_count')
        ;

        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['job_count']) ? $result['job_count'] : 0;
    }

    public function getPositionCount($options, $positionId)
    {
        $qb = $this->createCountsBuilder($options, 'position')
            ->select('COUNT(DISTINCT job.id) job_count')
            ->andWhere('job.position = :positionId')
            ->setParameter('positionId', $positionId)
        ;

        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['job_count']) ? $result['job_count'] : 0;
    }

    public function getDatePostedCounts($options)
    {
        $qb = $this->createCountsBuilder($options, 'datePosted')
            ->select('datePosted.id', 'datePosted.datePosted name', 'COUNT(DISTINCT job.id) job_count')
            ->leftJoin('NewRoadsMediaFrontendBundle:DatePosted' , 'datePosted', 'WITH'
                , 'DATE(CONVERT_TZ(job.createDate, :serverTimezone, :clientTimezone)) BETWEEN DATE(DATE_SUB(CONVERT_TZ(CURRENT_TIMESTAMP(), :serverTimezone, :clientTimezone), datePosted.rangeEnd, \'DAY\')) AND DATE(DATE_SUB(CONVERT_TZ(CURRENT_TIMESTAMP(), :serverTimezone, :clientTimezone), datePosted.rangeStart, \'DAY\'))'
            )
            ->andWhere('datePosted.id IS NOT NULL')
            ->groupBy('datePosted.rangeEnd')
            ->orderBy('datePosted.rangeEnd', 'ASC')
            ->setParameter('serverTimezone', 'America/New_York')
            ->setParameter('clientTimezone', 'America/Los_Angeles')
        ;

        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function getCrawlerReport(\DateTime $startDate, \DateTime $endDate)
    {
        $sql = '
            SELECT IF(Job.JobKey LIKE "sinclair-broadcast-group-%"
                    , "sinclair-broadcast-group"
                    , LEFT(Job.JobKey, LENGTH(Job.JobKey) - LOCATE("-", REVERSE(Job.JobKey)))
                ) crawler
                , DATE_FORMAT(DATE(Job.CreateDate), "%m/%d/%Y") date
                , COUNT(*) job_count
            FROM Job
            WHERE JobKey IS NOT NULL
            AND Job.CreateDate BETWEEN :startDate AND :endDate
            GROUP BY date, crawler
            ORDER BY Job.CreateDate DESC, crawler ASC';
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue('startDate', $startDate->format('Y-m-d'), \PDO::PARAM_STR);
        $statement->bindValue('endDate', $endDate->format('Y-m-d'), \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function setCrawlsExpired($prefix, $jobKeys)
    {
        $jobKeys = array_filter(array_unique($this->flattenArray($jobKeys)));
        if (empty($prefix) || empty($jobKeys)){
            return false;
        }
        $now = new \DateTime();
        $sql = '
            UPDATE Job
            SET ExpirationDate = ?
            WHERE JobKey like ?
            and JobKey not in (?)
            and ExpirationDate > ?';
        $statement = $this->getEntityManager()->getConnection()
            ->executeQuery($sql,
                array($now->format('Y-m-d 00:00:00'), $prefix . '%', $jobKeys, $now->format('Y-m-d 00:00:00')),
                array(\PDO::PARAM_STR, \PDO::PARAM_STR, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY, \PDO::PARAM_STR));
        return true;
    }

    private function flattenArray(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
        return $return;
    }
}