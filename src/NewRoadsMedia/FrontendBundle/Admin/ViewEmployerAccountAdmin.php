<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ViewEmployerAccountAdmin extends Admin
{
    protected $baseRouteName = 'admin_newroadsmedia_frontend_view_employer_account';

    protected $baseRoutePattern = '/newroadsmedia/frontend/view-employer-account';

    protected $classnameLabel = 'View Employer Accounts';

//    protected $maxPerPage = 0;

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email', null, array(
                'label' => 'Find Employer By Email',
            ))
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('email')
            ->add('password')
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
    }
}