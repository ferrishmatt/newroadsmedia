<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeWorkSampleFormType extends AbstractType
{
    /** @var bool */
    protected $showCertification = false;

    public function __construct($showCertification = false)
    {
        $this->showCertification = $showCertification;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', 'text', array(
                'label' => 'Enter Title or Description for Work Sample (e.g. "The Onion Club," Food Magazine, Oct. 2015):',
                'required' => false,
                'attr' => array(
                    'class' => 'work-sample work-sample-description'
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a description.', 'groups' => array('edit-profile'))),
                ),
            ))
            ->add('website', 'url', array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'e.g. http://www.example.com/my_work_samples',
                    'class' => 'work-sample work-sample-website'
                ),
                'label' => 'Enter Web Address for Work Sample [ e.g. http://www.example.com/food_magazine_writing ]',
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a web address.', 'groups' => array('edit-profile'))),
                ),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeWorkSample',
        ));
    }

    public function getName()
    {
        return 'resume_work_sample';
    }
}