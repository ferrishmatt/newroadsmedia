<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class InvoiceAdmin extends Admin
{
    protected $classnameLabel = 'Paid Invoices';

    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'whenPaid',
    );

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, array(
                'label' => 'Invoice #',
            ))
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('address')
            ->add('city')
            ->add('state')
            ->add('zip')
            ->add('company')
            ->add('phoneArea')
            ->add('phone')
            ->add('specialNotes')
            ->add('amount')
            ->add('amountPaid')
            ->add('invoice')
            ->add('whenPaid', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('creationDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('billingMethod')
//            ->add('isDeleted')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('invoice', null, array(
                'label' => 'Invoice #',
            ))
            ->add('company')
            ->add('name')
            ->add('address')
            ->add('amountPaid')
            ->add('specialNotes')
            ->add('whenPaid')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }
}