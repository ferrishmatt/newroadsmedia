<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use NewRoadsMedia\FrontendBundle\Form\DataTransformer\IdToEntityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IdToEntityFormType extends AbstractType
{
    /** @var EntityManager */
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (empty($options['class'])) {
            throw new RuntimeException('"class" needs to be specified for IdToEntityFormType.');
        }

        $repository = $this->em->getRepository($options['class']);
        $builder->addModelTransformer(new IdToEntityTransformer($repository));

        $invalidMessage = $options['invalid_message'] ?: 'Entity not found.';
        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($invalidMessage) {
            $form = $event->getForm();
            if ($form->getData() === null) {
                $form->addError(new FormError($invalidMessage));
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class' => null,
            'invalid_message' => null,
        ));
    }

    public function getName()
    {
        return 'id_to_entity';
    }

    public function getParent()
    {
        return 'text';
    }
}