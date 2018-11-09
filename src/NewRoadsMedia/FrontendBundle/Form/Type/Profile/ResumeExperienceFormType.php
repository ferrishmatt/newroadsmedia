<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeExperienceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $yearChoices = array();
        for ($i = 1; $i <= 20; $i++) {
            $year = $i . ' Year' . ($i == 1 ? '' : 's');
            $yearChoices[$year] = $year;
        }
        $yearChoices['+20 Years'] = '+20 Years';

        $builder
            ->add('designation', 'choice', array(
                'required' => false,
                'empty_value' => '- Select Class Type -',
                'choices' => array(
                    'Regular class' => 'Regular class',
                    'Honors class' => 'Honors class',
                    'Advanced Placement (AP)' => 'Advanced Placement (AP)',
                    'International Baccalaureate (IB)' => 'International Baccalaureate (IB)',
                    'Special Education' => 'Special Education',
                    'Other' => 'Other',
                ),
                'expanded' => false,
                'multiple' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
                'label' => false,
            ))
            ->add('type', 'choice', array(
                'required' => false,
                'empty_value' => '- Select School Type -',
                'choices' => array(
                    'Preschool' => 'Preschool',
                    'Elementary School' => 'Elementary School',
                    'Middle School' => 'Middle School',
                    'High School' => 'High School',
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
                'label' => false,
            ))
            ->add('specialty', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Specialty',
                'empty_value' => '- Select Subject Focus -',
                'property' => 'specialty',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
                'label' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('years', 'choice', array(
                'label' => false,
                'required' => false,
                'choices' => $yearChoices,
                'empty_value' => '- Select Number of Years -',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'profile_edit_resume_experience';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeExperience',
        ));
    }
}