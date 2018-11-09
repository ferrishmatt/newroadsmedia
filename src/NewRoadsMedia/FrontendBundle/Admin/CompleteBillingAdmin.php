<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CompleteBillingAdmin extends Admin
{
    protected $baseRouteName = 'admin_newroadsmedia_frontend_complete_billing';

    protected $baseRoutePattern = '/newroadsmedia/frontend/complete-billing';

    protected $classnameLabel = 'Complete Billing';

    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'createDate',
    );

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('id', null, array(
                'label' => 'Job ID',
                'template' => 'NewRoadsMediaFrontendBundle:Admin:CompleteBilling/listFieldId.html.twig',
            ))
            ->add('createDate', 'datetime', array(
                'template' => 'NewRoadsMediaFrontendBundle:Admin:CompleteBilling/listFieldCreateDate.html.twig',
            ))
            ->add('employer', null, array(
                'label' => 'Name',
                'template' => 'NewRoadsMediaFrontendBundle:Admin:CompleteBilling/listFieldEmployer.html.twig',
                'admin_code' => 'sonata.admin.employer',
            ))
            ->add('employer.email')
            ->add('employer.password')
            ->add('company')
            ->add('titleOfPositionOpen', null, array(
                'label' => 'Title',
            ))
            ->add('billing')
            ->add('billingFax')
            ->add('billingEmail')
            ->add('billingInstructions')
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
    }

    public function createQuery($context = 'list')
    {
        /** @var QueryBuilder $query */
        $query = parent::createQuery($context);
        $alias = $query->getRootAlias();

        $query
            ->andWhere(sprintf('%s.expirationDate > CURRENT_TIMESTAMP()', $alias))
            ->andWhere(sprintf('%s.employer IS NOT NULL', $alias))
            ->andWhere(sprintf('(%s.incomplete = :n OR %s.incomplete IS NULL)', $alias, $alias))
            ->setParameter('n', 'N')
        ;

        return $query;
    }
}