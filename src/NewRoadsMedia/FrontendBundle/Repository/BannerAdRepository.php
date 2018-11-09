<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BannerAdRepository extends EntityRepository
{
    public function getBannersById(array $bannerIds)
    {
        $qb = $this->createQueryBuilder('banner')
            ->where('banner.id IN (:bannerIds)')
            ->andWhere('(banner.isRawCode = TRUE AND banner.rawCode IS NOT NULL) OR banner.image IS NOT NULL')
            ->setParameters(array(
                'bannerIds' => $bannerIds,
            ))
        ;
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }
}