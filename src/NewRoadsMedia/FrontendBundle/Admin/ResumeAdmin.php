<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;

class ResumeAdmin extends Admin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('email')
            ->add('date', 'doctrine_orm_callback', array(
                    'callback' => function($qb, $alias, $field, $value) {
                        /** @var QueryBuilder $qb */
                        if (!$value['value']) {
                            return false;
                        }

                        $date = new \DateTime($value['value']);
                        $qb
                            ->andWhere(sprintf('%s.dateEntered <= :date', $alias))
                            ->setParameter('date', $date->format('Y-m-d'))
                        ;

                        return true;
                    },
                    'label' => 'Date Entered Before',
                )
                , 'text'
                , array(
                    'attr' => array('class' => 'date-picker'),
                )
            )
            ->add('activity', 'doctrine_orm_callback', array(
                    'callback' => function($qb, $alias, $field, $value) {
                        /** @var QueryBuilder $qb */
                        if (!$value['value']) {
                            return false;
                        }

                        $date = new \DateTime('-6 months');
                        switch ($value['value']) {
                            case 1:
                                $qb
                                    ->andWhere(sprintf('%s.lastActivity >= :date', $alias))
                                    ->setParameter('date', $date->format('Y-m-d H:i:s'))
                                ;
                                break;
                            case 2:
                                $qb
                                    ->andWhere(sprintf('%s.lastActivity < :date', $alias))
                                    ->setParameter('date', $date->format('Y-m-d H:i:s'))
                                ;
                                break;
                        }

                        return true;
                    },
                    'label' => 'Active (within 6 months)',
                )
                , 'choice'
                , array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => array(
                        1 => 'Active Users',
                        2 => 'Inactive Users',
                    ),
                )
            )
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('email')
            ->add('password')
            ->add('viewAll')
            ->add('name')
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('city')
            ->add('state')
            ->add('zip')
            ->add('zipPlus')
            ->add('phoneArea')
            ->add('phone')
            ->add('faxArea')
            ->add('fax')
            ->add('screenName')
            ->add('industries')
            ->add('position')
            ->add('specialty')
            ->add('market')
            ->add('experience')
            ->add('education')
            ->add('jobType')
            ->add('salary')
            ->add('currentState')
            ->add('desiredState')
            ->add('desiredRegion')
            ->add('website')
            ->add('resumeBody')
            ->add('resumeJobObjective')
            ->add('resumeWorkExperienceOld')
            ->add('resumeWorkExperience')
            ->add('resumeEducation')
            ->add('resumeHonors')
            ->add('dateEntered')
            ->add('okd')
            ->add('expiration')
            ->add('viewCount')
            ->add('resumeContract')
            ->add('sendDemoTape')
            ->add('avi')
            ->add('language')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('email')
            ->add('password')
            ->add('expiration', null, array(
                'label' => 'Expires',
            ))
            ->add('name')
            ->add('dateEntered')
            ->add('lastActivity')
            ->add('viewCount')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }

    /**
     * @return \NewRoadsMedia\FrontendBundle\Manager\ResumeManager
     */
    protected function getResumeManager()
    {
        return $this->getConfigurationPool()->getContainer()->get('journalismjobs.manager.resume');
    }

    /**
     * @param Resume $object
     * @return mixed|void
     */
    public function preRemove($object)
    {
        $resumeManager = $this->getResumeManager();
        $resumeManager->deleteResumeFile($object);
        $resumeManager->deleteProfilePicture($object);
    }

    /**
     * {@inheritdoc}
     */
    public function preBatchAction($actionName, ProxyQueryInterface $query, array & $idx, $allElements)
    {
        if ($actionName != 'delete') {
            return;
        }

        $resumeManager = $this->getResumeManager();
        $resumes = $query->getQuery()->getResult();
        foreach ($resumes as $resume) {
            if (!empty($idx) && !in_array($resume->getId(), $idx)) {
                continue;
            }
            $resumeManager->deleteResumeFile($resume);
            $resumeManager->deleteProfilePicture($resume);
        }
    }
}