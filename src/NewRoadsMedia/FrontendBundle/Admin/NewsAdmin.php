<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'entryDate'  // name of the ordered field
    );

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('link', 'text', array(
                'label' => 'Full Url',
                'required' => true,
            ))
            ->add('title', null, array(
                'label' => 'Article Title',
                'required' => true,
            ))
            ->add('entryDate', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'Article Date',
                'required' => true,
            ))
            ->add('linkBody', 'textarea', array(
                'label' =>'Article Summary',
                'required' => true,
            ))
            ->add('source')
            ->add('imageName', 'file_upload', array(
                'required' => false,
                'directory' => '/media/news',
            ))
            ->add('imageAlign', 'choice', array(
                'choices' => array(
                    'Left' => 'Left',
                    'Right' => 'Right',
                ),
            ))
            ->add('imageCredit', 'text', array(
                'label' => 'Image Credit',
                'required' => false,
            ))
            ->add('industry', 'text', array(
                'required' => false,
            ))
            ->add('entryType', 'choice', array(
                'empty_value' => '',
                'choices' => array(
                    'medianews' => 'Media News',
                    'spotlight' => 'Spotlight',
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter entry type.')),
                ),
            ))
//            ->add('whichSite', 'choice', array(
//                'choices' => array(
//                    'Both' => 'Both',
//                    'JournalismGossip' => 'JournalismGossip',
//                    'JournalismJobs' => 'JournalismJobs',
//                    'TVGossip' => 'TVGossip',
//                ),
//                'required' => true,
//            ))
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('link')
            ->add('title')
            ->add('entryDate')
            ->add('entryType')
//            ->add('whichSite')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }
}