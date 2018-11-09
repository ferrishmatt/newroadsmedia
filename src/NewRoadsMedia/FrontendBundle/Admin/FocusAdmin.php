<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use NewRoadsMedia\FrontendBundle\Entity\Focus;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class FocusAdmin extends Admin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('focus')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('focus')
            ->add('displayOrder')
            ->add('priority')
            ->add('keywords', null, array(
                'help' => 'Please enter keywords separated by commas.  When these keywords are found in a job title they will automatically add this focus on job ad creation.',
            ))
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('focus')
            ->add('displayOrder')
            ->add('keywords')
            ->add('priority')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }

    protected function applyFocusToExistingJobs(Focus $focus)
    {
        $keywords = $focus->getKeywordsArray();
        if (!$keywords) {
            return;
        }

        $filters = array();
        $parameters = array();
        foreach ($keywords as $index => $keyword) {
            $keyword = strtolower($keyword);
            $filters[] = 'Job.TitleOfPositionOpen LIKE :keyword' . $index;
            $parameters['keyword' . $index] = '%' . $keyword . '%';
        }

        $sql = '
            INSERT INTO FocusTOJob (JobId, FocusID)
            SELECT Job.JobID, :focusId FocusID
            FROM Job
            LEFT JOIN FocusTOJob ON Job.JobID = FocusTOJob.JobID
            LEFT JOIN Focus ON FocusTOJob.FocusID = Focus.FocusID AND Focus.FocusID = :focusId
            WHERE Focus.FocusID IS NULL
            AND Job.ExpirationDate > NOW()';
        $sql .= ' AND (' . implode(' OR ', $filters) . ')';

        $statement = $this->getConfigurationPool()->getContainer()->get('journalismjobs.manager.focus')->getManager()->getConnection()->prepare($sql);
        $statement->bindValue('focusId', $focus->getId(), \PDO::PARAM_INT);
        foreach ($parameters as $key => $value) {
            $statement->bindValue($key, $value, \PDO::PARAM_STR);
        }

        $statement->execute();
    }

    public function postUpdate($object)
    {
        if ($object instanceof Focus) {
            $this->applyFocusToExistingJobs($object);
        }
    }

    public function postPersist($object)
    {
        if ($object instanceof Focus) {
            $this->applyFocusToExistingJobs($object);
        }
    }
}