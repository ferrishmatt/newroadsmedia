<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminDailyBillingController extends CRUDController
{
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $page = $this->getRequest()->get('page', 1);
        $max = 50;

        $qb = $this->get('journalismjobs.manager.invoice_track')->getRepository()->createQueryBuilder('invoiceTrack')
            ->select('invoiceTrack invoice_track', 'DATE(invoiceTrack.billDate) bill_date')
            ->join('invoiceTrack.jobs', 'job')
            ->join('invoiceTrack.employer', 'employer')
            ->andWhere('job.expirationDate > CURRENT_TIMESTAMP()')
            ->andWhere('job.incomplete = :n OR job.incomplete IS NULL')
            ->andWhere('job.billing = :email')
            ->setParameter('n', 'N')
            ->setParameter('email', 'email')
        ;
        $qbCount = clone $qb;
        $qbCount->select('COUNT(DISTINCT invoiceTrack.id) row_count');
        $qb
            ->groupBy('invoiceTrack.id')
            ->orderBy('bill_date', 'DESC')
            ->addOrderBy('invoiceTrack.id', 'ASC')
        ;
        $query = $qb->getQuery();
        $query->setMaxResults($max);
        $query->setFirstResult(($page - 1) * $max);
        $results = $query->getResult();
        $invoiceTracks = array();
        foreach ($results as $result) {
            $invoiceTracks[] = $result['invoice_track'];
        }
        $countResult = $qbCount->getQuery()->getOneOrNullResult();
        $count = isset($countResult['row_count']) ? $countResult['row_count'] : 0;
        $pages = ceil($count / $max);

        return $this->render($this->admin->getTemplate('list'), array(
            'action'     => 'list',
            'invoiceTracks' => $invoiceTracks,
            'count' => $count,
            'pages' => $pages,
            'page' => $page,
        ));
    }
}