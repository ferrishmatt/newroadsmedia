<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use NewRoadsMedia\FrontendBundle\Entity\ResumeExperience;
use NewRoadsMedia\FrontendBundle\Form\Type\AllowDeleteCollectionFormType;
use NewRoadsMedia\FrontendBundle\Validator\Constraints\WordCount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeCandidateSnapshotFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => true,
                'label' => 'Your Name',
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your name.')),
                ),
            ))
            ->add('image', 'file', array(
                'mapped' => false,
                'required' => false,
                'label' => 'Attach Profile Photo (optional)',
                'constraints' => array(
                    new File(array(
                        'maxSize' => '1000k',
                        'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.',
                        'mimeTypes' => array(
                            'image/jpg',
                            'image/jpeg',
                            'image/gif',
                            'image/png',
                        ),
                        'mimeTypesMessage' => 'Images can only be the following types: .GIF, .JPG, .PNG',
                    )),
                ),
            ))
//            ->add('deleteImage', 'checkbox', array(
//                'mapped' => false,
//                'required' => false,
//                'label' => 'Delete?',
//            ))
            ->add('title', 'text', array(
                'label' => 'Current or Recent Job Title',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a job title.')),
                ),
            ))
//            ->add('company', 'text', array(
//                'label' => 'Company',
//                'required' => true,
//                'constraints' => array(
//                    new NotBlank(array('message' => 'This field cannot be blank.')),
//                ),
//            ))
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
                'label' => 'Your Current Location',
                'multiple' => false,
                'expanded' => false,
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
                'label' => 'Your Current Location',
                'multiple' => false,
                'expanded' => false,
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
                'required' => false,
                'label' => 'Desired Location',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('contactEmail', 'email', array(
                'label' => 'Email Contact',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                    new Email(array('message' => 'Please enter a valid email.')),
                ),
            ))
            ->add('phone', 'text', array(
                'required' => true,
                'label' => 'Phone',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('phoneArea', 'text', array(
                'label' => false,
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('website', 'url', array(
                'required' => false,
                'label' => 'Professional Website',
            ))
            ->add('isAvailableImmediately', 'checkbox', array(
                'label' => 'Immediately',
                'required' => false,
            ))
            ->add('dateAvailable', 'datetime', array(
                'label' => 'When are you available to start work?',
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'required' => false,
                'invalid_message' => 'Please enter a valid date.',
                'attr' => array('placeholder' => 'Enter start date'),
            ))
            ->add('resumeJobObjective', 'textarea', array(
                'required' => true,
                'label' => 'Career Objective',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                    new WordCount(array('limit' => 200)),
                ),
            ))
            ->add('currentJobStatusChoice', 'choice', array(
                'mapped' => false,
                'label' => 'What is Your Job Status?',
                'empty_value' => '',
                'choices' => array(
                    'Employed' => 'Employed',
                    'Looking for Work' => 'Looking for Work',
                    'On Sabbatical' => 'On Sabbatical',
                    'Other' => 'Other',
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('currentJobStatus', 'text', array(
                'label' => false,
                'required' => false,
                'constraints' => array(
                    new WordCount(array('limit' => 10)),
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
            ->add('specialty', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Specialty',
                'empty_value' => '',
                'property' => 'specialty',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('focus', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Focus',
                'property' => 'focus',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('f')->orderBy('f.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ))

            ->add('certification', new ResumeCertificationFormType(), array(
                'label' => false,
                'compact' => true,
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

            ->add('resumeExperiences', new AllowDeleteCollectionFormType(), array(
                'type' => new ResumeExperienceFormType(),
                'prototype_name' => '__experience__',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
            ))

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $resume = $event->getData();
                if ($resume instanceof Resume) {
                    if ($resume->getResumeExperiences()->count() < 1) {
                        $resume->addResumeExperience(new ResumeExperience());
                    }
                }
            })

            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $choice = isset($data['currentJobStatusChoice']) ? $data['currentJobStatusChoice'] : null;
                if ($choice != 'Other') {
                    $data['currentJobStatus'] = $choice;
                    $event->setData($data);
                }
            })
        ;
    }

    public function getName()
    {
        return 'profile_candidate_snapshot';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\Resume',
            'validation_groups' => array('Default', 'edit-profile'),
        ));
    }
}