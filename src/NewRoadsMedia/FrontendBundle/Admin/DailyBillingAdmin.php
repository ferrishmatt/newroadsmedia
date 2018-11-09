<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class DailyBillingAdmin extends Admin
{
    protected $baseRouteName = 'admin_newroadsmedia_frontend_daily_billing';

    protected $baseRoutePattern = '/newroadsmedia/frontend/daily-billing';

    protected $classnameLabel = 'Daily Billing';

    public function configure()
    {
        $this->setTemplate('list', 'NewRoadsMediaFrontendBundle:Admin:DailyBilling/list.html.twig');
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
    }
}