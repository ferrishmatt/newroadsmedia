<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PositionRepository extends EntityRepository
{
    public function getPositionsWithGroupsAsArray()
    {
        $qb = $this->createQueryBuilder('position')
            ->select('position', 'positionGroup')
            ->leftJoin('position.positionGroup', 'positionGroup')
            ->where('position.media = :all')
            ->orderBy('positionGroup.displayOrder', 'ASC')
            ->addOrderBy('position.displayOrder', 'ASC')
            ->setParameter('all', 'All')
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 3600);
        $result = $query->getResult();

        return $result;
    }
}