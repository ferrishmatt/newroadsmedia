<?php

namespace NewRoadsMedia\JournalismJobsBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Form\Type\AllowDeleteCollectionFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\Profile\ResumeSocialMediaFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\ResumeFormType as BaseFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\ResumeWorkSampleFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeFormType extends BaseFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder

            // Journalism Experience
            ->add('title', 'text', array(
                'label' => 'Current or Recent Job Title',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a job title.')),
                ),
            ))
            ->add('company', 'text', array(
                'label' => 'Current Employer (optional)',
                'required' => false,
            ))
            ->add('position', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Position',
                'empty_value' => '',
                'property' => 'position',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.media = :all')
                        ->orderBy('p.displayOrder', 'ASC')
                        ->setParameter('all', 'All')
                    ;
                },
                'required' => true,
            ))
            ->add('experience', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Experience',
                'empty_value' => '',
                'property' => 'experience',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('specialty', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Specialty',
                'empty_value' => '',
                'property' => 'specialty',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
                'label' => 'Specialty',
            ))
            ->add('jobType', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:JobType',
                'empty_value' => '',
                'property' => 'jobType',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('salary', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Salary',
                'empty_value' => '',
                'property' => 'salary',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('city', 'text', array(
                'required' => true,
                'label' => 'Current City',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('currentState', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => 'Select State',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->groupBy('l.locationDescription')->orderBy('l.displayOrder', 'ASC');
                },
                'required' => true,
                'label' => 'Current State',
                'multiple' => false,
                'expanded' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('desiredCity', 'text', array(
                'required' => true,
                'label' => 'Desired City',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('desiredState', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => 'Select State',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->groupBy('l.locationDescription')->orderBy('l.displayOrder', 'ASC');
                },
                'required' => true,
                'label' => 'Desired State',
                'multiple' => false,
                'expanded' => false,
            ))

            // Work Samples
            ->add('workSamples', new AllowDeleteCollectionFormType(), array(
                'type' => new ResumeWorkSampleFormType(),
                'prototype_name' => '__work_sample__',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Work Samples',
                'required' => false,
            ))

            // Social Media Accounts
            ->add('socialMedia', new ResumeSocialMediaFormType(), array(
                'label' => 'Social Media Accounts',
                'required' => false,
            ))
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $socialMedia = $form->get('socialMedia');
        $view->vars['complete'] = array(
            $form->get('name')->getData() && $form->get('email')->getData(),
            count($form->get('industries')->getData()) > 0 && $form->get('title')->getData() && $form->get('position')->getData() && $form->get('city')->getData() && $form->get('currentState')->getData(),
            count($form->get('workSamples')->getData()) > 0 && $this->getErrorCount($form->get('workSamples')) == 0,
            $socialMedia->get('googlePlus')->getData() || $socialMedia->get('twitter')->getData() || $socialMedia->get('facebook')->getData() || $socialMedia->get('linkedIn')->getData(),
            $form->get('resumeJobObjective')->getData(),
        );
    }
}