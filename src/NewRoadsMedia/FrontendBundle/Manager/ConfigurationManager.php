<?php

namespace NewRoadsMedia\FrontendBundle\Manager;

use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;

/**
 * @method \NewRoadsMedia\FrontendBundle\Entity\Configuration create()
 * @method \NewRoadsMedia\FrontendBundle\Entity\Configuration findOneByName()
 */
class ConfigurationManager extends ObjectManager
{
    public function get($name, $valueIfNew = null)
    {
        $configuration = $this->findOneByName($name);
        if ($configuration) {
            return $configuration->getValue();
        }

        if ($valueIfNew) {
            $configuration = $this->create();
            $configuration->setName($name);
            $configuration->setValue($valueIfNew);
            $this->save($configuration);

            return $valueIfNew;
        }

        return null;
    }
}