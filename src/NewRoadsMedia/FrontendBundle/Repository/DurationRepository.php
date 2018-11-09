<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DurationRepository extends EntityRepository
{
    public function getCount()
    {
        $qb = $this->createQueryBuilder('d')->select('COUNT(DISTINCT d.id) row_count');
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(86400);
        $result = $query->getSingleResult();

        return isset($result['row_count']) ? $result['row_count'] : 0;
    }
}