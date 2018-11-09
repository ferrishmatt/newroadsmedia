<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use NewRoadsMedia\FrontendBundle\Validator\Constraints\WordCount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostResumeFormType extends AbstractType
{
    /** @var bool */
    protected $showCertification = false;

    public function __construct($showCertification = false)
    {
        $this->showCertification = $showCertification;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                    new NotBlank(array('message' => 'Please select how long you want your resume profile to be listed.')),
                ),
                'error_bubbling' => true,
            ))
            ->add('focus', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Focus',
                'error_bubbling' => true,
                'label' => 'STEM Focus',
                'property' => 'focus',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('f')->orderBy('f.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ))
            ->add('industries', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Industry',
                'error_bubbling' => true,
                'property' => 'industry',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => true,
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
            ->add('specialty', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Specialty',
                'empty_value' => '',
                'property' => 'specialty',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
                'label' => $this->showCertification ? 'Subject Focus' : 'Specialty',
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
//            ->add('education', 'entity', array(
//                'class' => 'NewRoadsMediaFrontendBundle:Education',
//                'empty_value' => '',
//                'property' => 'education',
//                'query_builder' => function(EntityRepository $er) {
//                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
//                },
//                'required' => false,
//            ))
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
            ->add('currentState', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => '',
                'label' => 'Select your current location.',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->orderBy('l.displayOrder', 'ASC');
                },
                'required' => true,
            ))
            ->add('desiredState', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => '',
                'label' => 'Select your desired location.',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->orderBy('l.displayOrder', 'ASC');
                },
                'required' => true,
            ))
//            ->add('language', 'choice', array(
//                'choices' => array(
//                    'Cantonese' => 'Cantonese',
//                    'French' => 'French',
//                    'German' => 'German',
//                    'Italian' => 'Italian',
//                    'Mandarin' => 'Mandarin',
//                    'Persian' => 'Persian',
//                    'Russian' => 'Russian',
//                    'Spanish' => 'Spanish',
//                ),
//                'required' => false,
//                'empty_data'  => null,
//                'empty_value' => "Select One",
//            ))
            ->add('name', 'text')
            ->add('phone', 'text')
            ->add('phone_area', 'text')
            ->add('website', 'text', array(
                'required' => false,
                'label' => 'Personal Website',
            ))
            ->add('resume_job_objective', 'textarea', array(
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
            ->add('resume_work_experience', 'textarea', array(
                'required' => false,
                'label' => 'Work History',
            ))
            ->add('submit', 'submit', array(
                'label' => 'Submit Profile',
            ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $resume = $event->getData();
            $form = $event->getForm();
            if ($resume instanceof Resume) {
                if ($resume->getOkd()) {
                    $form->add('submit', 'submit', array(
                        'label' => 'Update Profile',
                    ));
                } else {
                    $form ->add('captcha', 'captcha', array(
                        'mapped' => false,
                        'label' => 'Enter Code to Prove You\'re Human:',
                        'error_bubbling' => true,
                    ));
                }
            }
        });

        if ($this->showCertification) {
            $builder->add('certification', new PostResumeCertificationFormType());
        } else {
//            $builder->add('market', 'entity', array(
//                'class' => 'NewRoadsMediaFrontendBundle:Market',
//                'empty_value' => '',
//                'property' => 'market',
//                'query_builder' => function(EntityRepository $er) {
//                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
//                },
//                'required' => false,
//            ));
        }
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
        return 'post_resume';
    }
}