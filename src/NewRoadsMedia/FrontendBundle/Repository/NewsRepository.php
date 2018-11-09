<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
{
    public function getLatestNewsArticles($entryType = 'medianews', $maxResults = 5)
    {
        $qb = $this->createQueryBuilder('news')
            ->select('news')
            ->andWhere('news.entryType = :entryType')
            ->setParameters(array(
                'entryType' => $entryType,
            ))
            ->orderBy('news.entryDate', 'DESC')
        ;
        $query = $qb->getQuery();
        $query->setMaxResults($maxResults);
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }

    public function getNewsArticles($entryType = 'medianews', $firstResult = 0, $maxResults = 5)
    {
        $qb = $this->createQueryBuilder('news')
            ->select('news')
            ->andWhere('news.entryType = :entryType')
            ->setParameters(array(
                'entryType' => $entryType,
            ))
            ->orderBy('news.entryDate', 'DESC')
        ;
        $query = $qb->getQuery();
        $query->setFirstResult($firstResult);
        $query->setMaxResults($maxResults);
        $query->useQueryCache(true);
        $result = $query->getResult();

        return $result;
    }
}