<?php

namespace NewRoadsMedia\FrontendBundle\Twig;

use NewRoadsMedia\FrontendBundle\Service\Device;
use Symfony\Bundle\TwigBundle\TwigEngine as BaseTwigEngine;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Config\FileLocatorInterface;

class TwigEngine extends BaseTwigEngine
{
    /** @var Device */
    protected $device;

    public function __construct(\Twig_Environment $environment, TemplateNameParserInterface $parser, FileLocatorInterface $locator, Device $device)
    {
        parent::__construct($environment, $parser, $locator);
        $this->device = $device;
    }

    public function render($name, array $parameters = array())
    {
        if ($this->device->isMobile()) {
            $mobileTemplate = preg_replace('/^\w+:\w+:/', '$0Mobile/', $name);
            if ($this->exists($mobileTemplate)) {
                $name = $mobileTemplate;
            }
        }

        return parent::render($name, $parameters);
    }
}