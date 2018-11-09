<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\Job;

class ApplicationRepository extends EntityRepository
{
    public function deleteApplications(Job $job, array $applicationIds)
    {
        if (empty($applicationIds)) {
            return;
        }

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->delete('NewRoadsMediaFrontendBundle:Application', 'a')
            ->where('a.job = :job')
            ->andWhere('a.id IN (:applicationIds)')
            ->setParameters(array(
                'job' => $job,
                'applicationIds' => $applicationIds,
            ))
        ;
        $query = $qb->getQuery();
        $query->execute();
    }
}