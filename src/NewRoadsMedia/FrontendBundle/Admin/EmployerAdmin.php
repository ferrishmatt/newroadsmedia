<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class EmployerAdmin extends Admin
{
    public function configure()
    {
        $this->setTemplate('grantResumeAccess', 'NewRoadsMediaFrontendBundle:Admin:Employer/grantResumeAccess.html.twig');
        $this->setTemplate('invoiceOption', 'NewRoadsMediaFrontendBundle:Admin:invoiceOption.html.twig');
        $this->setTemplate('packagePlan', 'NewRoadsMediaFrontendBundle:Admin:packagePlan.html.twig');
        $this->setTemplate('resumeAccess', 'NewRoadsMediaFrontendBundle:Admin:resumeAccess.html.twig');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('email')
            ->add('invoiceOption', 'doctrine_orm_callback', array(
                    'callback' => function($queryBuilder, $alias, $field, $value) {
                        if (!$value || empty($value['value'])) {
                            return false;
                        }

                        switch ($value['value']) {
                            case 1:
                                $queryBuilder
                                    ->andWhere(sprintf('%s.invoiceOption = :allow', $alias))
                                    ->setParameter('allow', 'allow')
                                ;
                                return true;
                            case 2:
                                $queryBuilder
                                    ->andWhere(sprintf('%s.invoiceOption IS NULL', $alias))
                                ;
                                return true;
                        }

                        return false;
                    },
                ), 'choice', array(
                    'choices' => array(
                        null => 'All',
                        1 => 'Yes',
                        2 => 'No',
                    ),
                )
            )
        ;
        if ($this->isAllowFreeOption()) {
            $datagridMapper->add('freeOption');
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('title')
            ->add('address')
            ->add('city')
            ->add('state')
            ->add('zip')
            ->add('zipPlus')
            ->add('company')
            ->add('phoneArea')
            ->add('phone')
            ->add('faxNumber')
            ->add('email')
            ->add('password')
            ->add('membershipExpirationDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('searchExpirationDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('specialBillingInstructions')
            ->add('billingCompany')
            ->add('packagePlan', 'string_to_boolean', array(
                'true_value' => 'Y',
                'false_value' => null,
            ))
            ->add('adsRemaining')
            ->add('resumeAccess', 'checkbox', array(
                'required' => false,
            ))
            ->add('createdDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('billingEmail')
            ->add('invoiceOption', 'string_to_boolean', array(
                'true_value' => 'allow',
                'false_value' => null,
            ))
        ;
        if ($this->isAllowFreeOption()) {
            $formMapper->add('freeOption', 'checkbox', array(
                'required' => false,
                'label' => 'Free Postings',
            ));
        }
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('email')
            ->add('password')
            ->add('membershipExpirationDate', null, array(
                'label' => 'Expires',
            ))
            ->add('name')
            ->add('title')
            ->add('company')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                    'grant' => array(
                        'template' => 'NewRoadsMediaFrontendBundle:Admin:Employer/actionGrant.html.twig'
                    ),
                ),
            ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('invoiceOption', 'invoice-option');
        $collection->add('packagePlan', 'package-plan');
        $collection->add('resumeAccess', 'resume-access');
        $collection->add('grantResumeAccess', $this->getRouterIdParameter() . '/grant-resume-access');
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        $admin = $this->isChild() ? $this->getParent() : $this;
        $menu->addChild('Enable Resume Access', array(
            'uri' => $admin->generateUrl('resumeAccess'),
        ));
    }

    public function getBatchActions()
    {
        return array();
    }

    protected function isAllowFreeOption()
    {
        return $this->getConfigurationPool()->getContainer()->getParameter('allowFree') === true;
    }
}