<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CalendarAdmin extends Admin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('company')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array(
                'required' => true,
            ))
            ->add('title')
            ->add('company')
            ->add('address')
            ->add('city')
            ->add('country')
            ->add('zip')
            ->add('zipPlus')
            ->add('phoneArea')
            ->add('phone')
            ->add('email')
            ->add('eventBody')
            ->add('postDate')
            ->add('expirationDate')
            ->add('beginDate')
            ->add('event')
            ->add('adDuration')
            ->add('location')
            ->add('state')
            ->add('eventUrl')
            ->add('frontPage')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('company')
            ->add('event')
            ->add('event_body')
            ->add('entryDate')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }
}