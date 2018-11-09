<?php

namespace NewRoadsMedia\FrontendBundle\Twig;

class ContainsHtmlExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('containsHtml', array($this, 'doesContainHtml')),
        );
    }

    public function doesContainHtml($string)
    {
        return $string != strip_tags($string);
    }

    public function getName()
    {
        return 'newroadsmedia_contains_html_extension';
    }
}