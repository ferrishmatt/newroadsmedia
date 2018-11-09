<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Validator\Constraints\WordCount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\EqualTo;

class RegisteredApplicationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('action', 'hidden', array(
                'data' => 'apply',
                'constraints' => array(
                    new EqualTo(array(
                        'value' => 'apply',
                        'message' => 'Please try again.',
                    )),
                ),
            ))
            ->add('coverLetter', 'textarea', array(
                'label' => 'Your Cover Letter (450 words max).',
                'constraints' => array(
                    new WordCount(array(
                        'limit' => 500,
                        'message' => 'There is a %limit% word limit on your cover letter.'
                    )),
                ),
            ))
        ;
        if ($options['login']) {
            $builder->add('login', new LoginFormType());
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'login' => false,
        ));
    }

    public function getName()
    {
        return 'registered_application';
    }
}
