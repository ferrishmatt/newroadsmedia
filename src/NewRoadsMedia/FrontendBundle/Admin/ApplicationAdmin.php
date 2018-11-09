<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ApplicationAdmin extends Admin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('applicationDate')
            ->add('rating')
            ->add('displayOrder')
            ->add('departmentName')
            ->add('socialSecurityNumber')
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('title')
            ->add('email')
            ->add('address')
            ->add('city')
            ->add('stateOrProvince')
            ->add('region')
            ->add('postalCode')
            ->add('country')
            ->add('homePhone')
            ->add('workPhone')
            ->add('birthDate')
            ->add('salary')
            ->add('internship')
            ->add('introLetter')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('applicationDate')
            ->add('resume', null, array(
                'admin_code' => 'sonata.admin.resume',
            ))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }
}