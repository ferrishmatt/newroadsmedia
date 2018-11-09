<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeReferenceFormType extends AbstractType
{
    protected $group = 'edit-profile';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a name.', 'groups' => array($this->group))),
                ),
            ))
            ->add('title', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a title.', 'groups' => array($this->group))),
                ),
            ))
            ->add('company', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a company.', 'groups' => array($this->group))),
                ),
                'label' => 'Organization',
            ))
            ->add('relationship', 'text', array(
                'label' => 'Your Work Relationship',
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your work relationship.', 'groups' => array($this->group))),
                ),
            ))
            ->add('phone', 'text', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a phone number.', 'groups' => array($this->group))),
                ),
            ))
            ->add('email', 'email', array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter an email.', 'groups' => array($this->group))),
                    new Email(array('message' => 'Please enter a valid email.')),
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'profile_edit_resume_reference';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeReference',
        ));
    }
}