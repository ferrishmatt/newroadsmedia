<?php

namespace NewRoadsMedia\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\Focus;

class ResumeRepository extends EntityRepository
{
    protected function createSearchResumesQueryBuilder($alias, $options)
    {
        $qb = $this->createQueryBuilder($alias)
            ->select($alias)
            ->where(sprintf('%s.viewAll = :viewAll', $alias))
            ->andWhere(sprintf('%s.okd = TRUE', $alias))
            ->andWhere(sprintf('%s.isPublic = TRUE', $alias))
            ->setParameter('viewAll', 'All')
        ;
        if (!empty($options['keywords'])) {
            $qb
                ->andWhere(sprintf('%s.resumeWorkExperience LIKE :keywords OR %s.resumeJobObjective LIKE :keywords'
                    , $alias
                    , $alias
                ))
                ->setParameter('keywords', '%' . $options['keywords']. '%')
            ;
        }
        if (!empty($options['industry'])) {
            $qb
                ->join(sprintf('%s.industries', $alias), 'industry')
                ->andWhere('industry IN (:industry)')
                ->setParameter('industry', $options['industry'])
            ;
        }
        if (!empty($options['language'])) {
            $qb->andWhere(sprintf('%s.language = :language', $alias))->setParameter('language', $options['language']);
        }
        foreach (array('position', 'specialty', 'market', 'experience', 'education', 'desiredState', 'jobType', 'salary') as $key) {
            if (!empty($options[$key])) {
                $qb
                    ->join(sprintf('%s.%s', $alias, $key), $key)
                    ->setParameter($key, $options[$key])
                ;
                $qb->andWhere(sprintf('%s = :%s', $key, $key));
            }
        }

        if (!empty($options['focus'])) {
            $focusIds = array();
            foreach ($options['focus'] as $focus) {
                /** @var Focus $focus */
                $focusIds[] = $focus->getId();
            }
            if (!empty($focusIds)) {
                $qb
                    ->join(sprintf('%s.focus', $alias), 'focus')
                    ->andWhere('focus IN (:focusIds)')
                    ->setParameter('focusIds', $focusIds)
                ;
            }
        }

        return $qb;
    }

    public function searchResumes($options)
    {
        $qb = $this->createSearchResumesQueryBuilder('resume', $options);
        $qb
            ->groupBy('resume.id')
            ->orderBy('resume.dateEntered', 'DESC')
        ;
        $query = $qb->getQuery();
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

    public function searchResumesCount($options)
    {
        $qb = $this->createSearchResumesQueryBuilder('resume', $options);
        $qb->select('COUNT(DISTINCT resume.id) row_count');
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $result = $query->getOneOrNullResult();

        return isset($result['row_count']) ? $result['row_count'] : 0;
    }
}