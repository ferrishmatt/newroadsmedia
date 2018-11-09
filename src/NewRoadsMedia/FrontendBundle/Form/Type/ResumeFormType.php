<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Validator\Constraints\WordCount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // Basic Profile Info
            ->add('name', 'text', array(
                'required' => true,
                'label' => 'Your Name',
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your name.')),
                ),
            ))
            ->add('email', 'email', array(
                'label' => 'Your Email Address',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                    new Email(array('message' => 'Please enter a valid email.')),
                ),
            ))
            ->add('contactEmail', 'email', array(
                'label' => 'Contact Email Address',
                'required' => false,
                'constraints' => array(
                    new Email(array('message' => 'Please enter a valid email.')),
                ),
            ))
            ->add('phone', 'text', array(
                'required' => false,
                'label' => 'Phone Number (optional)',
            ))
            ->add('phoneArea', 'text', array(
                'label' => false,
                'required' => false,
            ))
            ->add('website', 'url', array(
                'required' => false,
                'label' => 'Personal Website',
            ))
            ->add('image', 'file', array(
                'mapped' => false,
                'required' => false,
                'label' => 'Attach Profile Photo (optional)',
                'constraints' => array(
                    new File(array(
                        'maxSize' => '1000k',
                        'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.',
                        'mimeTypes' => array(
                            'image/jpg',
                            'image/jpeg',
                            'image/gif',
                            'image/png',
                        ),
                        'mimeTypesMessage' => 'Images can only be the following types: .GIF, .JPG, .PNG',
                    )),
                ),
            ))

            // Industries
            ->add('industries', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Industry',
                'property' => 'industry',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ))

            // Job Objective
            ->add('resumeJobObjective', 'textarea', array(
                'required' => false,
                'label' => 'Job Objective',
                'constraints' => array(
                    new WordCount(array('limit' => 250)),
                ),
            ))
        ;
    }

    protected function getErrorCount(FormInterface $form)
    {
        $count = count($form->getErrors());
        foreach ($form->all() as $child) {
            $count += $this->getErrorCount($child);
        }

        return $count;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\Resume',
            'validation_groups' => array('Default', 'edit-profile'),
        ));
    }

    public function getName()
    {
        return 'resume';
    }
}