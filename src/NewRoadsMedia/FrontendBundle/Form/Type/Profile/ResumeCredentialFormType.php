<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeCredentialFormType extends AbstractType
{
    protected $group = 'edit-profile';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'text', array(
                'required' => false,
                'label' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('location', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => '- Select State -',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->groupBy('l.locationDescription')->orderBy('l.displayOrder', 'ASC');
                },
                'required' => false,
                'label' => false,
                'multiple' => false,
                'expanded' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('isLifetime', 'checkbox', array(
                'required' => false,
                'label' => 'Lifetime'
            ))
            ->add('dateExpiration', 'date', array(
                'label' => 'Enter Date',
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'invalid_message' => 'Please enter a valid date.',
                'required' => false,
            ))
        ;
    }

    public function getName()
    {
        return 'profile_edit_resume_reference';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeCredential',
        ));
    }
}