<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JobSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keywords', 'text', array(
                'label' => 'Enter Keywords',
                'required' => false,
            ))
            ->add('location', 'location_with_count', array(
                'empty_value' => 'Select All',
                'label' => 'Select Location',
                'required' => false,
            ))
        ;
        if (!$options['simple']) {
            $builder
                ->add('industry', 'entity', array(
                    'class' => 'NewRoadsMediaFrontendBundle:Industry',
                    'empty_value' => 'Select All',
                    'property' => 'industry',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('i')->orderBy('i.displayOrder', 'ASC');
                    },
                    'required' => false,
                    // 'multiple' => true,
                    // 'expanded' => true,
                    // 'mapped' => false
                ))
                ->add('position', 'position', array(
                    'empty_value' => 'Select All',
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
                ->add('datePosted', 'entity', array(
                    'class' => 'NewRoadsMediaFrontendBundle:DatePosted',
                    'empty_value' => 'Select All',
                    'label' => 'Date Posted',
                    'property' => 'datePosted',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('d')->orderBy('d.rangeEnd', 'ASC');
                    },
                    'required' => false,
                ))
                ->add('diversity', 'hidden')
            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'simple' => false,
        ));
    }

    public function getName()
    {
        return 'job_posts_search';
    }
}
