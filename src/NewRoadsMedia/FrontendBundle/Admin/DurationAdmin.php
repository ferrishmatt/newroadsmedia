<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DurationAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_by' => 'displayOrder',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('duration', 'text')
            ->add('displayOrder')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('duration')
            ->add('displayOrder')
        ;
    }
}