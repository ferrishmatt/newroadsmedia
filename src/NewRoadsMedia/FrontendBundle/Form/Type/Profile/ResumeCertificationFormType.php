<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResumeCertificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teaching', 'choice', array(
                'empty_value' => '',
                'choices' => array(
                    'Yes, I\'m certified.' => 'Yes, I\'m certified.',
                    'No, I\'m not certified.' => 'No, I\'m not certified.',
                    'My certification is pending.' => 'My certification is pending.',
                    'I\'m planning to get certified.' => 'I\'m planning to get certified.',
                    'Not applicable' => 'Not applicable',
                ),
                'required' => false,
                'label' => 'Are you certified?',
            ))
            ->add('locations', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => '',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->groupBy('l.locationDescription')->orderBy('l.displayOrder', 'ASC');
                },
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'label' => 'What states are you certified in?',
            ))
        ;
        if (!$options['compact']) {
            $builder
                ->add('organization', 'text', array(
                    'label' => 'School Attended for Certification',
                    'required' => false,
                ))
                ->add('securityClearances', 'textarea', array(
                    'label' => false,
                    'required' => false,
                ))
                ->add('certifications', 'textarea', array(
                    'label' => 'Special Notes',
                    'required' => false,
                ))
            ;
        }
    }

    public function getName()
    {
        return 'profile_edit_resume_certification';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeCertification',
            'compact' => false,
        ));
    }
}