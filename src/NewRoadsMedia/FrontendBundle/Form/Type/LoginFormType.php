<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', 'email', array(
                'label' => 'Your email address',
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your email address.')),
                    new Email(array('message' => 'Please enter a valid email address.')),
                ),
            ))
            ->add('_password', 'password', array(
                'label' => 'Password',
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your password.')),
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'login';
    }
}