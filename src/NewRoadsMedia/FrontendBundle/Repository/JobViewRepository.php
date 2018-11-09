<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\Job;

class JobViewRepository extends EntityRepository
{
    public function getMinJobViewDate(Job $job)
    {
        $qb = $this->createQueryBuilder('jobView')
            ->select('MIN(jobView.createDate) createDate')
            ->where('jobView.job = :job')
            ->setParameter('job', $job)
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['createDate']) ? $result['createDate'] : null;
    }

    public function incrementJobViewCount(Job $job, $userGuid)
    {
        $now = new \DateTime();
        $sql = 'INSERT IGNORE INTO JobViews (dt ,UserGUID, JobID) VALUES (:date, :userGuid, :jobId)';
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue('date', $now->format('Y-m-d H:i:s'));
        $statement->bindValue('userGuid', $userGuid);
        $statement->bindValue('jobId', $job->getId());
        $statement->execute();
    }

    public function clearJobViewCount(Job $job)
    {
        $sql = 'DELETE FROM JobViews WHERE JobID = :jobId';
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindValue('jobId', $job->getId());
        $statement->execute();
    }
}