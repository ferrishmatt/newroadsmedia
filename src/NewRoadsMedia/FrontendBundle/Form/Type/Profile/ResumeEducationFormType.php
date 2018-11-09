<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeEducationFormType extends AbstractType
{
    protected $group = 'edit-profile';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('major', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('gpa', 'text', array(
                'required' => false,
                'label' => 'GPA',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('degree', 'choice', array(
                'required' => false,
                'empty_value' => '',
                'choices' => array(
                    'Associate in Arts Degree (AA)' => 'Associate in Arts Degree (AA)',
                    'Associate in Science Degree (AS)' => 'Associate in Science Degree (AS)',
                    'Bachelor of Arts (BA)' => 'Bachelor of Arts (BA)',
                    'Bachelor of Science (BS)' => 'Bachelor of Science (BS)',
                    'Master of Arts (MA)' => 'Master of Arts (MA)',
                    'Master of Science (MS)' => 'Master of Science (MS)',
                    'Master of Education (M.ED)' => 'Master of Education (M.ED)',
                    'Education Specialist (Ed.S)' => 'Education Specialist (Ed.S)',
                    'Doctor of Education (Ed.D)' => 'Doctor of Education (Ed.D)',
                    'Doctor of Philosophy (Ph.D)' => 'Doctor of Philosophy (Ph.D)',
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'profile_edit_resume_education';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeEducation',
        ));
    }
}