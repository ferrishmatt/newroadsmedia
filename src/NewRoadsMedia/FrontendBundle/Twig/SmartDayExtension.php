<?php

namespace NewRoadsMedia\FrontendBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SmartDayExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('smart_day', array($this, 'smartDayFilter'), array('needs_environment' => true)),
        );
    }

    public function smartDayFilter(\Twig_Environment $env, $date, $format = null, $timezone = null)
    {
        if ($date instanceof \DateTime) {
            $timezone = new \DateTimeZone('America/Los_Angeles');
            $date = clone $date;
            $date->setTimezone($timezone);
            $justDate = $date->format('Y-m-d');

            $today = new \DateTime('now', $timezone);
            if ($today->format('Y-m-d') == $justDate) {
                return 'Today';
            }

            $yesterday = new \DateTime('yesterday', $timezone);
            if ($yesterday->format('Y-m-d') == $justDate) {
                return 'Yesterday';
            }
        }

        return twig_date_format_filter($env, $date, $format, $timezone);
    }

    public function getName()
    {
        return 'newroadsmedia_smartday_extension';
    }
}