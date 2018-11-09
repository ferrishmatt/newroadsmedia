<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Manager\EmployerManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateEmployerAccountFormType extends AbstractType
{
    protected $employerManager;

    public function __construct(EmployerManager $employerManager)
    {
        $this->employerManager = $employerManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $employerManager = $this->employerManager;
        $builder
            ->add('name', 'text', array(
                'label' => 'Name',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Name is required')),
                ),
                'error_bubbling' => $options['error_bubbling'],
            ))
            ->add('company', 'text', array(
                'label' => 'Company',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Company is required')),
                ),
                'error_bubbling' => $options['error_bubbling'],
            ))
            ->add('email', 'email', array(
                'label' => 'Email',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Email address is required')),
                    new Email(array('message' => 'Valid Email is required')),
                ),
                'error_bubbling' => $options['error_bubbling'],
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Verify Password'),
                'constraints' => array(
                    new NotBlank(array('message' => 'Password is required')),
                    new Length(array(
                        'min' => 6,
                        'minMessage' => 'Password must be at least {{ limit }} characters',
                        'max' => 15,
                        'maxMessage' => 'Password must be no more than {{ limit }} characters',
                    ))
                ),
                'error_bubbling' => $options['error_bubbling'],
            ))
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($employerManager) {
                $form = $event->getForm();
                $email = $form->get('email')->getData();
                if ($email && !$form->getErrors() && $employerManager->findOneByEmail($email)) {
                    $form->get('email')->addError(new FormError('An account for the email address you entered already exists.'));
                }
            })
        ;
    }

    public function getName()
    {
        return 'create_employer';
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\Employer',
            'error_bubbling' => false,
        ));
    }
}