<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResumeSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('industry', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Industry',
                'empty_value' => 'Select All',
                'label' => 'Category',
                'property' => 'industry',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('focus', 'entity', array(
                'label' => 'STEM Focus',
                'class' => 'NewRoadsMediaFrontendBundle:Focus',
                'property' => 'focus',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('f')->orderBy('f.displayOrder', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('nonFocused', 'checkbox', array(
                'label' => 'Non-STEM Jobs',
                'required' => false,
            ))
            ->add('position', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Position',
                'empty_value' => 'Select All',
                'property' => 'position',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.media = :all')
                        ->orderBy('p.position', 'ASC')
                        ->setParameter('all', 'all')
                    ;
                },
                'required' => false,
            ))
            ->add('specialty', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Specialty',
                'empty_value' => 'Select All',
                'property' => 'specialty',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('market', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Market',
                'empty_value' => 'Select All',
                'property' => 'market',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('experience', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Experience',
                'empty_value' => 'Select All',
                'property' => 'experience',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('education', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Education',
                'empty_value' => 'Select All',
                'property' => 'education',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('desiredState', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => 'Select All',
                'label' => 'Select Location',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->orderBy('l.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('jobType', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:JobType',
                'empty_value' => 'Select All',
                'label' => 'Job Status',
                'property' => 'jobType',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('jt')->orderBy('jt.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('keywords', 'text', array(
                'label' => 'Keyword Search',
                'required' => false,
            ))
            ->add('salary', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Salary',
                'empty_value' => 'Select All',
                'label' => 'Salary',
                'property' => 'salary',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')->orderBy('s.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('language', 'choice', array(
                'empty_value' => 'Select All',
                'label' => 'Foreign Language',
                'choices' => array(
                    'Cantonese' => 'Cantonese',
                    'French' => 'French',
                    'German' => 'German',
                    'Italian' => 'Italian',
                    'Mandarin' => 'Mandarin',
                    'Persian' => 'Persian',
                    'Russian' => 'Russian',
                    'Spanish' => 'Spanish',
                ),
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'resume_search';
    }
}
