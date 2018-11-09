<?php

namespace NewRoadsMedia\JournalismJobsBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Form\Type\PostNewJobFormType as BaseFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PostNewJobFormType extends BaseFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('position', 'position', array(
            'empty_value' => '',
            'required' => true,
            'error_bubbling' => true,
        ));
        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $job = $event->getData();
            if ($job instanceof Job && !$job->getPosition() && !$job->hasIndustry(16)) {
                $event->getForm()->get('position')->addError(new FormError('Position is required!'));
            }
        });
    }
}