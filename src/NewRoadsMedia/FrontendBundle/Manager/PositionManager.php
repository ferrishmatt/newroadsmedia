<?php

namespace NewRoadsMedia\FrontendBundle\Manager;

use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use NewRoadsMedia\FrontendBundle\Entity\Position;

/**
 * @method \NewRoadsMedia\FrontendBundle\Entity\Position create()
 * @method \NewRoadsMedia\FrontendBundle\Entity\Position find()
 * @method \NewRoadsMedia\FrontendBundle\Repository\PositionRepository getRepository()
 */
class PositionManager extends ObjectManager
{
    public function getPositionsWithGroupsAsArray()
    {
        /** @var Position[] $positionResults */
        $positionResults = $this->getRepository()->getPositionsWithGroupsAsArray();
        $positions = array();
        foreach ($positionResults as $position) {
            if ($position->getPositionGroup()) {
                $key = strtoupper($position->getPositionGroup()->getPositionGroup());
                $positions[$key][] = $position;
            } else {
                $positions[] = $position;
            }
        }

        return $positions;
    }
}