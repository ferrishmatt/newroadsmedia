<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Manager\PositionManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PositionFormType extends AbstractType
{
    /**
     * @var PositionManager
     */
    protected $positionManager;

    public function __construct(PositionManager $positionManager)
    {
        $this->positionManager = $positionManager;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'NewRoadsMediaFrontendBundle:Position',
            'property' => 'position',
            'choices' => $this->positionManager->getPositionsWithGroupsAsArray(),
        ));
    }

    public function getName()
    {
        return 'position';
    }

    public function getParent()
    {
        return 'entity';
    }
}