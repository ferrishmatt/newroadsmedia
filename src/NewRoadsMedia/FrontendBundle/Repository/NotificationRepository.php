<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\Resume;

class NotificationRepository extends EntityRepository
{
    public function getActiveNotificationsQuery()
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.expiration > CURRENT_TIMESTAMP()')
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);

        return $query;
    }

    public function getActiveNotificationsForResume(Resume $resume)
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.expiration > CURRENT_TIMESTAMP()')
            ->andWhere('n.resume = :resume')
            ->orderBy('n.expiration', 'ASC')
            ->setParameter('resume', $resume)
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function getActiveNotificationCountForResume(Resume $resume)
    {
        $qb = $this->createQueryBuilder('n')
            ->select('COUNT(DISTINCT n.id) notification_count')
            ->where('n.expiration > CURRENT_TIMESTAMP()')
            ->andWhere('n.resume = :resume')
            ->setParameter('resume', $resume)
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['notification_count']) ? $result['notification_count'] : 0;
    }
}