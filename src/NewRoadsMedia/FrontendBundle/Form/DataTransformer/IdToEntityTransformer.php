<?php

namespace NewRoadsMedia\FrontendBundle\Form\DataTransformer;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;

class IdToEntityTransformer implements DataTransformerInterface
{
    /** @var EntityRepository */
    protected $entityRepository;

    public function __construct($entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public function transform($entity)
    {
        if ($entity !== null) {
            return $entity->getId();
        }

        return null;
    }

    public function reverseTransform($id)
    {
        if ($id !== null) {
            return $this->entityRepository->find($id);
        }

        return null;
    }
}