<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeEmployerSpecialtyFormType extends AbstractType
{
    protected $group = 'edit-profile';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $yearChoices = array();
        for ($i = 1; $i <= 20; $i++) {
            $year = $i . ' Year' . ($i == 1 ? '' : 's');
            $yearChoices[$year] = $year;
        }
        $yearChoices['+20 Years'] = '+20 Years';

        $builder
            ->add('specialty', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Specialty',
                'empty_value' => '- Select Subject -',
                'property' => 'specialty',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
                'label' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
            ->add('years', 'choice', array(
                'label' => false,
                'required' => false,
                'choices' => $yearChoices,
                'empty_value' => '- Select Number of Years -',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.', 'groups' => array($this->group))),
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'profile_edit_resume_employer_specialty';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\ResumeEmployerSpecialty',
        ));
    }
}