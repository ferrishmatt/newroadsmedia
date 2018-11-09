<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

class InvoiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $yearChoices = array();
        for ($i = 0; $i < 9; $i++) {
            $yearChoices[date('y') + $i] = date('Y') + $i;
        }

        $builder
            ->add('invoice', 'text', array(
                'label' => 'Invoice Number',
                'required' => true,
            ))
            ->add('amount', 'money', array(
                'label' => 'Amount to Pay',
                'required' => true,
                'precision' => 2,
                'currency' => 'USD',
                'error_bubbling' => true,
                'invalid_message' => 'The value entered into the "Amount To Pay" field is not valid. Please check that there are only digits and decimals present.',
                'constraints' => array(
                    new Range(array(
                        'min' => 1,
                        'minMessage' => 'Please enter an amount of at least $1.00.',
                    ))
                ),
            ))
            ->add('name', 'text', array(
                'label' => 'Billing Name',
                'constraints' => array(
                    new NotBlank(array('message' => 'Name is required!')),
                ),
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('email', 'email', array(
                'label' => 'Email Address',
                'constraints' => array(
                    new NotBlank(array('message' => 'Email is required!')),
                    new Email(array('message' => 'Valid email is required')),
                ),
                'error_bubbling' => true,
            ))
            ->add('company', 'text', array(
                'label' => 'Company',
                'constraints' => array(
                    new NotBlank(array('message' => 'Company is required!')),
                ),
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('address', 'text', array(
                'label' => 'Street',
                'constraints' => array(
                    new NotBlank(array('message' => 'Address is required!')),
                ),
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('city', 'text', array(
                'label' => 'City',
                'constraints' => array(
                    new NotBlank(array('message' => 'City is required!')),
                ),
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('state', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'State is required!')),
                ),
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('zip', 'text', array(
                'label' => 'Zip Code',
                'constraints' => array(
                    new NotBlank(array('message' => 'Zip is required!')),
                ),
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('specialInstructions', 'textarea', array(
                'required' => false,
                'label' => 'Special Instructions',
            ))
            ->add('creditCard', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:CreditCard',
                'empty_value' => 'Select One',
                'property' => 'cardName',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.displayOrder', 'ASC');
                },
                'required' => false,
                'label' => 'Credit Card',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Credit Card is required!',
                        'groups' => array('creditcard'),
                    )),
                ),
                'error_bubbling' => true,
            ))
            ->add('creditNumber', 'text', array(
                'required' => false,
                'label' => 'Credit Card Number',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Credit Card Number is required!',
                        'groups' => array('creditcard'),
                    )),
                ),
                'error_bubbling' => true,
            ))
            ->add('cardCode', 'text', array(
                'required' => false,
                'label' => 'Security Code (CVV)',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Security Code (CVV) is required!',
                        'groups' => array('creditcard'),
                    )),
                    new Regex(array(
                        'pattern' => '/^[0-9]{3,4}$/',
                        'message' => 'Invalid Security Code (CVV)',
                        'groups' => array('creditcard'),
                    )),
                ),
                'error_bubbling' => true,
            ))
            ->add('creditMonth', 'choice', array(
                'choices' => array(
                    '01' => 'January',
                    '02' => 'February',
                    '03' => 'March',
                    '04' => 'April',
                    '05' => 'May',
                    '06' => 'June',
                    '07' => 'July',
                    '08' => 'August',
                    '09' => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December',
                ),
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Expiration Date Month is required!',
                        'groups' => array('creditcard'),
                    )),
                ),
                'error_bubbling' => true,
                'label' => 'Month'
            ))
            ->add('creditYear', 'choice', array(
                'choices' => $yearChoices,
                'expanded' => false,
                'multiple' => false,
                'empty_value' => '',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Expiration Date Year is required!',
                        'groups' => array('creditcard'),
                    )),
                ),
                'error_bubbling' => true,
                'label' => 'Year'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
//            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\Invoice',
            'validation_groups' => array('Default', 'creditcard'),
        ));
    }

    public function getName()
    {
        return 'invoice';
    }
}