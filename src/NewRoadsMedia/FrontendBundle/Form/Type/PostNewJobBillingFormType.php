<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PostNewJobBillingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $yearChoices = array();
        for ($i = 0; $i < 9; $i++) {
            $yearChoices[date('y') + $i] = date('Y') + $i;
        }

        if ($options['payment_methods']) {
            $paymentMethodChoices = array(
                'creditcard' => 'Pay Now With a Credit Card (Visa, Mastercard or American Express)',
            );
            if ($options['invoice']) {
                $paymentMethodChoices['email'] = 'Email an invoice to the name and company listed below';
            }
            $builder ->add('paymentMethod', 'choice', array(
                'choices' => $paymentMethodChoices,
                'expanded' => true,
                'multiple' => false,
                'data' => 'creditcard',
                'constraints' => array(
                    new NotBlank(array('message' => 'Payment method is required!')),
                ),
                'error_bubbling' => true,
                'mapped' => false,
            ));
        }

        $builder
            ->add('name', 'text', array(
                'label' => 'Billing Name',
                'constraints' => array(
                    new NotBlank(array('message' => 'Name is required!')),
                ),
                'error_bubbling' => true,
            ))
            ->add('billingCompany', 'text', array(
                'label' => 'Billing Company',
                'constraints' => array(
                    new NotBlank(array('message' => 'Billing Company is required!')),
                ),
                'error_bubbling' => true,
            ))
            ->add('billingEmail', 'email', array(
                'label' => 'Email Address for Receipt',
                'constraints' => array(
                    new NotBlank(array('message' => 'Billing email is required!')),
                    new Email(array('message' => 'Valid billing email is required')),
                ),
                'error_bubbling' => true,
            ))
            ->add('address', 'text', array(
                'label' => 'Street',
                'constraints' => array(
                    new NotBlank(array('message' => 'Address is required!')),
                ),
                'error_bubbling' => true,
            ))
            ->add('city', 'text', array(
                'label' => 'City',
                'constraints' => array(
                    new NotBlank(array('message' => 'City is required!')),
                ),
                'error_bubbling' => true,
            ))
            ->add('state', 'text', array(
                'label' => 'State',
                'constraints' => array(
                    new NotBlank(array('message' => 'State is required!')),
                ),
                'error_bubbling' => true,
            ))
            ->add('zip', 'text', array(
                'label' => 'Zip Code',
                'constraints' => array(
                    new NotBlank(array('message' => 'Zip is required!')),
                ),
                'error_bubbling' => true,
            ))
            ->add('zipPlus', 'text', array(
                'required' => false,
                'label' => false,
            ))
            ->add('specialBillingInstructions', 'textarea', array(
                'required' => false,
                'label' => 'Enter Any Special Billing Instructions',
            ))
            ->add('phone', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Phone Number is required!')),
                ),
                'error_bubbling' => true,
            ))
            ->add('phoneArea', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Area Code is required!')),
                ),
                'error_bubbling' => true,
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
                'mapped' => false,
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
                'mapped' => false,
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
                'mapped' => false,
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
                'required' => false,
                'mapped' => false,
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
                'required' => false,
                'mapped' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => function(FormInterface $form) {
                $groups = array('Default');
                if (!$form->has('paymentMethod') || $form->get('paymentMethod')->getData() == 'creditcard') {
                    $groups[] = 'creditcard';
                }

                return $groups;
            },
            'invoice' => false,
            'payment_methods' => true,
        ));
    }

    public function getName()
    {
        return 'post_new_job_billing';
    }
}
