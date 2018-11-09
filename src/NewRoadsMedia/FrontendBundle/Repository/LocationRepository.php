<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LocationRepository extends EntityRepository
{
    public function getLocationsWithJobCount()
    {
        $qb = $this->createQueryBuilder('l')
            ->select('l.id, l.locationDescription, COUNT(DISTINCT job.id) job_count')
            ->join('l.jobs', 'job')
            ->where('job.incomplete = :n OR job.incomplete IS NULL')
            ->andWhere('job.expirationDate >= CURRENT_TIMESTAMP()')
            ->groupBy('l.locationDescription')
            ->having('job_count > 0')
            ->orderBy('l.displayOrder', 'ASC')
            ->setParameter('n', 'N')
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }
}