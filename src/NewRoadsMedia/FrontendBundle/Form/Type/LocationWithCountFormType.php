<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use NewRoadsMedia\FrontendBundle\Form\DataTransformer\IdToEntityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationWithCountFormType extends AbstractType
{
    /** @var array */
    protected $choices = array();

    /** @var  IdToEntityTransformer */
    protected $transformer;

    public function __construct(ObjectManager $locationManager)
    {
        $results = $locationManager->getLocationsWithJobCount();
        foreach ($results as $row) {
            $this->choices[$row['id']] = sprintf("%s", $row['locationDescription']);
        }

        $this->transformer = new IdToEntityTransformer($locationManager->getRepository());
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->choices,
        ));
    }

    public function getName()
    {
        return 'location_with_count';
    }

    public function getParent()
    {
        return 'choice';
    }
}