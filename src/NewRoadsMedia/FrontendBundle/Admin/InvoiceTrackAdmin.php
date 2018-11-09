<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class InvoiceTrackAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'id',
    );

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('billingEmail')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('billingEmail')
            ->add('billDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('jobList')
            ->add('amount')
            ->add('paid')
            ->add('paidDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('billingType')
            ->add('orderText')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('billingEmail')
            ->add('billDate')
            ->add('jobList')
            ->add('employer', null, array(
                'admin_code' => 'sonata.admin.employer',
            ))
            ->add('amount')
            ->add('paid')
            ->add('paidDate')
            ->add('billingType')
            ->add('orderText', null, array(
                'template' => 'NewRoadsMediaFrontendBundle:Admin:InvoiceTrack/listFieldOrderText.html.twig',
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