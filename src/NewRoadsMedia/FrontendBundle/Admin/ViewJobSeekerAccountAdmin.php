<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ViewJobSeekerAccountAdmin extends Admin
{
    protected $baseRouteName = 'admin_newroadsmedia_frontend_view_job_seeker_account';

    protected $baseRoutePattern = '/newroadsmedia/frontend/view-job-seeker-account';

    protected $classnameLabel = 'View Job Seeker Accounts';

//    protected $maxPerPage = 0;

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email', null, array(
                'label' => 'Find Job Seeker By Email',
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