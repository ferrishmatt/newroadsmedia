<?php

namespace NewRoadsMedia\FrontendBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class StripScriptExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('strip_script', array($this, 'stripScriptFilter')),
        );
    }

    public function stripScriptFilter($html)
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $script = $dom->getElementsByTagName('script');
        $remove = array();
        foreach ($script as $item) {
            $remove[] = $item;
        }
        foreach ($remove as $item) {
            $item->parentNode->removeChild($item);
        }

        return $dom->saveHTML();
    }

    public function getName()
    {
        return 'newroadsmedia_stripscript_extension';
    }
}