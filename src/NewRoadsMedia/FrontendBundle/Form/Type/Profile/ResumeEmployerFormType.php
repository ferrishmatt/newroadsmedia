<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Form\Type\AllowDeleteCollectionFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\BlankCollectionFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeEmployerFormType extends AbstractType
{
    protected $group = 'edit-profile';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('address', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('phone', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('title', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
                'label' => 'Your Job Title',
            ))
            ->add('designation', 'choice', array(
                'required' => false,
                'empty_value' => '',
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
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('dateFrom', 'date', array(
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'required' => false,
                'invalid_message' => 'Please enter a valid date.',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('dateTo', 'date', array(
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'required' => false,
                'invalid_message' => 'Please enter a valid date.',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('specialties', new AllowDeleteCollectionFormType(), array(
                'type' => new ResumeEmployerSpecialtyFormType(),
                'prototype_name' => '__specialty__',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
            ))
            ->add('type', 'choice', array(
                'required' => false,
                'empty_value' => '',
                'choices' => array(
                    'Preschool' => 'Preschool',
                    'Elementary School' => 'Elementary School',
                    'Middle School' => 'Middle School',
                    'High School' => 'High School',
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('jobDetails', 'textarea', array(
                'label' => 'Job Details',
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'profile_edit_resume_employer';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeEmployer',
        ));
    }
}