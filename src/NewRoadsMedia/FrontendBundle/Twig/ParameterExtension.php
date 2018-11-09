<?php

namespace NewRoadsMedia\FrontendBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ParameterExtension extends \Twig_Extension
{
    /** @var ContainerInterface */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('parameter', array($this, 'getParameter')),
        );
    }

    public function getParameter($parameter, $defaultIfNone = null)
    {
        if ($this->container->hasParameter($parameter)) {
            return $this->container->getParameter($parameter);
        }

        $configuration = $this->container->get('journalismjobs.manager.configuration');

        return $configuration->get($parameter, $defaultIfNone);
    }

    public function getName()
    {
        return 'newroadsmedia_parameter_extension';
    }
}