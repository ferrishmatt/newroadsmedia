<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Form\JobNotificationForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

class JobNotificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('industries', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Industry',
                'property' => 'industry',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'error_bubbling' => true,
            ))
            ->add('position', 'position', array(
                'empty_value' => 'Select All',
                'required' => false,
                'error_bubbling' => true,
            ))
            ->add('focus', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Focus',
                'error_bubbling' => true,
                'label' => 'STEM Focus',
                'property' => 'focus',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('f')->orderBy('f.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ))
            ->add('nonFocused', 'checkbox', array(
                'label' => 'Non-STEM Jobs',
                'required' => false,
            ))
            ->add('jobType', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:JobType',
                'empty_value' => 'Select All',
                'property' => 'jobType',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.displayOrder', 'ASC');
                },
                'error_bubbling' => true,
                'label' => 'Job Status',
            ))
            ->add('location', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => 'Select All',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->orderBy('l.displayOrder', 'ASC');
                },
                'error_bubbling' => true,
            ))
            // ->add('expiration', 'choice', array(
            //     'choices' => array(
            //         '+16 Weeks' => '16 Weeks',
            //         '+14 Weeks' => '14 Weeks',
            //         '+12 Weeks' => '12 Weeks',
            //         '+10 Weeks' => '10 Weeks',
            //         '+8 Weeks' => '8 Weeks',
            //         '+6 Weeks' => '6 Weeks',
            //         '+4 Weeks' => '4 Weeks',
            //         '+3 Weeks' => '3 Weeks',
            //         '+2 Weeks' => '2 Weeks',
            //         '+1 Week' => '1 Week',
            //     ),
            //     'error_bubbling' => true,
            // ))
            ->add('subscriptionStatus', 'choice', array(
                'choices' => array(
                    'subscribe' => 'Subscribe',
                    'unsubscribe' => 'Unsubscribe',
                ),
                'expanded' => true,
                'multiple' => false,
                'data' => 'subscribe',
                'error_bubbling' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Form\JobNotificationForm',
            'validation_groups' => function(FormInterface $form) {
                /** @var JobNotificationForm $jobNotificationForm */
                $jobNotificationForm = $form->getData();
                if ($jobNotificationForm->getSubscriptionStatus() == 'subscribe') {
                    return array('job-notification-subscribe');
                }

                return array();
            },
        ));
    }

    public function getName()
    {
        return 'job_notification';
    }
}