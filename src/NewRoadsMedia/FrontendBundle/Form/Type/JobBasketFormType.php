<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Entity\Resume;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Count;

class JobBasketFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (empty($options['resume']) || !$options['resume'] instanceof Resume) {
            throw new RuntimeException('"resume" needs to be passed to JobBasketFormType and must be of type "NewRoadsMedia\FrontendBundle\Entity\Resume".');
        }

        /** @var Resume $resume */
        $resume = $options['resume'];
        $choices = array();
        $count = 0;
        foreach ($resume->getJobBaskets() as $jobBasket) {
            $choices[$jobBasket->getId()] = 1;
            if ($options['max'] > 0 && ++$count >= $options['max']) {
                break;
            }
        }
        $builder
            ->add('deleteJobBaskets', 'choice', array(
                'choices' => $choices,
                'expanded' => true,
                'multiple' => true,
                'error_bubbling' => true,
                'constraints' => array(
                    new Count(array(
                        'min' => 1,
                        'minMessage' => 'You must select at least 1 job to delete from basket.',
                        'groups' => array('job-basket-delete'),
                    )),
                ),
            ))
//            ->add('applyJobBaskets', 'choice', array(
//                'choices' => $choices,
//                'expanded' => true,
//                'multiple' => true,
//                'error_bubbling' => true,
//                'constraints' => array(
//                    new Count(array(
//                        'min' => 1,
//                        'minMessage' => 'You must select at least 1 job to apply.',
//                        'groups' => array('job-basket-apply'),
//                    )),
//                ),
//            ))
            ->add('delete', 'submit', array(
                'label' => 'Delete From Job Basket',
                'validation_groups' => array('job-basket-delete'),
            ))
//            ->add('apply', 'submit', array(
//                'label' => 'Apply for Job',
//                'validation_groups' => array('job-basket-apply'),
//            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'resume' => null,
            'max' => null,
        ));
    }

    public function getName()
    {
        return 'job_basket';
    }
}