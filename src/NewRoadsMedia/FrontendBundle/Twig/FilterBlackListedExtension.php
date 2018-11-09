<?php

namespace NewRoadsMedia\FrontendBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FilterBlackListedExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('filter_black_listed', array($this, 'htmlFilter')),
        );
    }

    public function htmlFilter($html)
    {
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('/display\h*:\h*none\h*;?/', '', $html);
        return $html; // maybe even apply the raw filter also afterwards.
    }

    public function getName()
    {
        return 'newroadsmedia_filter_black_listed_extension';
    }
}