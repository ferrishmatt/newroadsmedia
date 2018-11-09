<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use NewRoadsMedia\FrontendBundle\Entity\Employer;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;

class JobAdmin extends Admin
{
    public function configure()
    {
        $this->setTemplate('crawler_report', 'NewRoadsMediaFrontendBundle:Admin:Job/crawler.html.twig');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('employer.email')
            ->add('employer.name')
            ->add('company')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('employer', 'sonata_type_model_autocomplete', array(
                    'property' => array('id', 'email'),
                    'to_string_callback' => function(Employer $employer, $property) {
                        return $employer->getId(). ' - ' . $employer->getEmail();
                    },
                    'callback' => function (AdminInterface $admin, $properties, $value) {
                        $datagrid = $admin->getDatagrid();
                        /** @var \Doctrine\Orm\QueryBuilder $queryBuilder */
                        $queryBuilder = $datagrid->getQuery();
                        foreach ($properties as $property) {
                            $queryBuilder
                                ->orWhere($queryBuilder->getRootAlias() . '.' . $property . ' LIKE :' . $property)
                                ->setParameter($property, '%' . $value . '%')
                            ;
                        }
                    },
                    'attr' => array(
                        'style' => 'width: 400px;',
                    ),
                )
                , array(
                    'admin_code' => 'sonata.admin.employer',
                )
            )
            ->add('company')
            ->add('website')
            ->add('contact')
            ->add('address2')
            ->add('accountZip')
            ->add('accountZipPlus')
            ->add('accountPhoneArea')
            ->add('accountPhone')
            ->add('contactEmail')
            ->add('contactTitle')
            ->add('applyType')
            ->add('apply')
            ->add('email')
            ->add('titleOfPositionOpen')
            ->add('adBody')
            ->add('adDescription')
            ->add('adQualifications')
            ->add('createDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('expirationDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('education')
            ->add('experience')
            ->add('industries')
            ->add('jobType')
            ->add('city')
            ->add('jobCity')
            ->add('location')
            ->add('majorMarket')
            ->add('market')
            ->add('media')
            ->add('position')
            ->add('region')
            ->add('specialty')
            ->add('salary')
            ->add('billPaid')
            ->add('billingFax')
            ->add('billing')
            ->add('billingEmail')
            ->add('billingInstructions')
            ->add('diversity')
            ->add('billingCompany')
            ->add('delayed')
            ->add('incomplete', 'string_to_boolean', array(
                'true_value' => 'Y',
                'false_value' => 'N',
            ))
            ->add('featureExpirationDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('changeLog')
            ->add('addedFeaturesDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('applyEmail', null, array(
                'required' => false,
            ))
            ->add('frontPageExpirationDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ))
            ->add('companyImage', 'file_upload', array(
                'directory' => '/media/company',
                'required' => false,
                'label' => 'Company Logo',
                'is_random_name' => true,
            ))
            ->add('deleteImage', 'checkbox', array(
                'mapped' => false,
                'required' => false,
                'label' => 'Delete Company Logo?',
            ))
        ;

        $jobManager = $this->getConfigurationPool()->getContainer()->get('journalismjobs.manager.job');
        $formMapper->getFormBuilder()
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($jobManager) {
                $data = $event->getData();
                if (isset($data['companyImage'])) {
                    $companyImage = $data['companyImage'];
                    if ($companyImage instanceof UploadedFile) {
                        $jobManager->resizeCompanyImage($companyImage->getRealPath());
                    }
                }
            })
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($jobManager) {
                $form = $event->getForm();
                $job = $event->getData();
                if ($job->getCompanyImage() && $form->has('deleteImage') && $form->get('deleteImage')->getData()) {
                    $jobManager->deleteCompanyImage($job);
                }
            })
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('employer', null, array(
                'associated_property' => 'email',
                'label' => 'Employer Email',
                'admin_code' => 'sonata.admin.employer',
            ))
            ->add('employer.name')
            ->add('company')
            ->add('titleOfPositionOpen')
            ->add('featureExpirationDate')
            ->add('applyType')
            ->add('diversity')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'edit', 'create', 'delete'));
        $collection->add('crawler_report', 'crawler-report');
    }
}