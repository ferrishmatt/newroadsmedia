<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;

class IndustryRepository extends EntityRepository
{
    protected $jobCounts;

    public function getJobCounts($industryId)
    {
        if ($this->jobCounts === null) {
            $qb = $this->createQueryBuilder('industry')
                ->select('industry.id', 'COUNT(DISTINCT job.id) job_count')
                ->join('industry.jobs', 'job', 'WITH', 'job.expirationDate >= CURRENT_TIMESTAMP()')
                ->groupBy('industry.id')
            ;
            $query = $qb->getQuery();
            $result = $query->getResult();

            $this->jobCounts = array();
            foreach ($result as $row) {
                $this->jobCounts[$row['id']] = $row['job_count'];
            }
        }

        if (!array_key_exists($industryId, $this->jobCounts)) {
            return 0;
        }

        return $this->jobCounts[$industryId];
    }
}