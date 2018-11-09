<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Entity\ResumeCertification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostResumeCertificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teachingChoices', 'choice', array(
                'mapped' => false,
                'label' => 'Teaching Certification',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices' => array(
                    1 => 'Yes, I\'m certified.',
                    2 => 'No, I\'m not certified.',
                    3 => 'I\'m planning to get certified.',
                ),
                'empty_value' => false,
            ))
            ->add('subjects', 'textarea', array(
                'label' => 'List Subjects You Are Certified In',
                'required' => false,
            ))
            ->add('securityClearances', 'textarea', array(
                'label' => 'Security Clearances',
            ))
            ->add('references', 'textarea', array(
                'label' => 'References',
            ))
        ;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {
            /** @var ResumeCertification $resumeCertification */
            $resumeCertification = $event->getData();
            $form = $event->getForm();
            $choices = $form->get('teachingChoices')->getConfig()->getOption('choices');
            if ($resumeCertification) {
                $teaching = $resumeCertification->getTeaching();
                if ($teaching) {
                    foreach ($choices as $index => $choice) {
                        if ($teaching == $choice) {
                            $form->get('teachingChoices')->setData($index);
                            break;
                        }
                    }

                }
            }
        });
        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $form = $event->getForm();
            $choice = $form->get('teachingChoices')->getData();
            $choices = $form->get('teachingChoices')->getConfig()->getOption('choices');
            if (isset($choices[$choice])) {
                /** @var ResumeCertification $resumeCertification */
                $resumeCertification = $event->getData();
                $resumeCertification->setTeaching($choices[$choice]);
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeCertification',
            'validation_groups' => array('Default', 'post-resume'),
        ));
    }

    public function getName()
    {
        return 'post_resume_certification';
    }
}