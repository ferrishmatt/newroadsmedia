<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use NewRoadsMedia\FrontendBundle\Form\DataTransformer\IdToEntityTransformer;
use NewRoadsMedia\FrontendBundle\Form\DataTransformer\StringToBooleanTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StringToBooleanFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new StringToBooleanTransformer($options['true_value'], $options['false_value']));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
            'true_value' => 1,
            'false_value' => 0,
        ));
    }

    public function getName()
    {
        return 'string_to_boolean';
    }

    public function getParent()
    {
        return 'checkbox';
    }
}