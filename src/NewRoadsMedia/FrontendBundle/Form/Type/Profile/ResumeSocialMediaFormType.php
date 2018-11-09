<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Regex;

class ResumeSocialMediaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('googlePlus', 'url', array(
                'label' => 'Google+',
                'required' => false,
                'constraints' => new Regex(array(
                    'pattern' => '/^https?:\/\/plus\.google\.com\//',
                    'message' => 'Url must begin with http://plus.google.com',
                )),
                'attr' => array('placeholder' => 'Enter the full web address for your account'),
            ))
            ->add('twitter', 'url', array(
                'required' => false,
                'constraints' => new Regex(array(
                    'pattern' => '/^https?:\/\/(www\.)?twitter\.com\//',
                    'message' => 'Url must begin with http://www.twitter.com',
                )),
                'attr' => array('placeholder' => 'Enter the full web address for your account'),
            ))
            ->add('facebook', 'url', array(
                'required' => false,
                'constraints' => new Regex(array(
                    'pattern' => '/^https?:\/\/www\.facebook\.com\//',
                    'message' => 'Url must begin with https://www.facebook.com',
                )),
                'attr' => array('placeholder' => 'Enter the full web address for your account'),
            ))
            ->add('linkedIn', 'url', array(
                'label'    => 'LinkedIn',
                'required' => false,
                'constraints' => new Regex(array(
                    'pattern' => '/^https?:\/\/www\.linkedin\.com\//',
                    'message' => 'Url must begin with http://www.linkedin.com',
                )),
                'attr' => array('placeholder' => 'Enter the full web address for your account'),
            ))
        ;
    }

    public function getName()
    {
        return 'profile_edit_resume_social_media';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeSocialMedia',
        ));
    }
}