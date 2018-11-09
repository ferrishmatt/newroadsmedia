<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PaymentAdmin extends Admin
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
            ->add('bankName')
            ->add('accountNumber')
            ->add('routingNumber')
            ->add('creditCard')
            ->add('creditCardNumber')
            ->add('expirationDateMonth')
            ->add('expirationDateYear')
            ->add('nameOnCard')
            ->add('paymentMethod')
            ->add('approval')
            ->add('chargeAmount')
            ->add('transactionDate')
            ->add('comment')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('bankName')
            ->add('accountNumber')
            ->add('routingNumber')
            ->add('creditCard')
            ->add('creditCardNumber')
            ->add('expirationDateMonth')
            ->add('expirationDateYear')
            ->add('nameOnCard')
            ->add('paymentMethod')
            ->add('approval')
            ->add('chargeAmount')
            ->add('transactionDate')
            ->add('comment')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }
}