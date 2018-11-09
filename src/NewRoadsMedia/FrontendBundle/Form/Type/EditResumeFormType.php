<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use NewRoadsMedia\FrontendBundle\Form\Type\Profile\ResumeSocialMediaFormType;
use NewRoadsMedia\FrontendBundle\Validator\Constraints\WordCount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditResumeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contactEmail', 'email', array(
                'label' => 'Your Email Address',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                    new Email(array('message' => 'Please enter a valid email.')),
                ),
            ))
            ->add('phone', 'text', array(
                'required' => false,
                'label' => 'Phone Number (optional)',
            ))
            ->add('phoneArea', 'text', array(
                'label' => false,
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('duration', 'choice', array(
                'label' => 'How Long Do You Want Your Resume Profile Listed?',
                'choices' => array(
                    '3 Months' => '3 Months',
                    '6 Months' => '6 Months',
                    '12 Months' => '12 Months',
                ),
                'expanded' => true,
                'multiple' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please select a duration.')),
                ),
            ))
            ->add('industries', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Industry',
                'property' => 'industry',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ))
            ->add('title', 'text', array(
                'label' => 'Current or Recent Job Title',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a job title.')),
                ),
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
                'label' => 'Current Location',
                'multiple' => false,
                'expanded' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('desiredCity', 'text', array(
                'required' => true,
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
            ->add('resumeJobObjective', 'textarea', array(
                'required' => false,
                'label' => 'Job Objective',
                'constraints' => array(
                    new WordCount(array('limit' => 250)),
                ),
            ))
            ->add('workSamples', new AllowDeleteCollectionFormType(), array(
                'type' => new ResumeWorkSampleFormType(),
                'prototype_name' => '__work_sample__',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Work Samples',
            ))
            ->add('socialMedia', new ResumeSocialMediaFormType())
            ->add('resume_work_experience', 'textarea', array(
                'required' => false,
                'label' => 'Work History',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\Resume',
            'validation_groups' => array('Default', 'post-resume'),
        ));
    }

    public function getName()
    {
        return 'edit_resume';
    }
}