<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class JobListingEmailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipient_email', 'email', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter recipient\'s email address.')),
                    new Email(array('message' => 'Recipient\'s email address is not valid.')),
                ),
                'error_bubbling' => true,
                'required' => true,
                'label' => 'To:',
            ))
            ->add('your_email', 'email', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your email address.')),
                    new Email(array('message' => 'Your email address is not valid.')),
                ),
                'error_bubbling' => true,
                'required' => true,
                'label' => 'From:',
            ))
            ->add('comments', 'textarea', array(
                'error_bubbling' => true,
                'required' => false,
                'label' => 'Comments:',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    public function getName()
    {
        return 'job_listing_email';
    }
}