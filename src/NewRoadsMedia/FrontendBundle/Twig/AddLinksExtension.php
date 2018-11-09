<?php

namespace NewRoadsMedia\FrontendBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AddLinksExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('add_links', array($this, 'addLinksFilter')),
        );
    }

    public function addLinksFilter($html)
    {
        $html = str_replace("\xc2\xa0", ' ', $html);
        $html =
            preg_replace(
                '~(\s|^|\>|&nbsp|&nbsp;)(http?://.+?)(\s|$|&nbsp|\<|\.\s|\.&nbsp)~im',
                '$1<a href="$2" target="_blank">$2</a>$3',
                $html
            );
        $html =
            preg_replace(
                '~(\s|^|\>|&nbsp|&nbsp;)(https?://.+?)(\s|$|&nbsp|\<|\.\s|\.&nbsp)~im',
                '$1<a href="$2" target="_blank">$2</a>$3',
                $html
            );
        $html =
            preg_replace(
                '~(\s|^|\>|&nbsp|&nbsp;)(www\..+?)(\s|$|&nbsp|\<|\.\s|\.&nbsp)~im',
                '$1<a href="http://$2" target="_blank">$2</a>$3',
                $html
            );
        $html =
            preg_replace(
                '~<\s*a\s+href\s*=\s*"\s*mailto\s*:\s*[^"]+"\s*>(.*?)</a>~im',
                '$1',
                $html
            );
	$html =
            preg_replace(
                '~(\s|^|\>|&nbsp|&nbsp;)(\w+?\.com)(\s|$|&nbsp|\<|\.\s|\.&nbsp)~im',
                '$1<a href="http://$2" target="_blank">$2</a>$3',
                $html
            );
        $pattern = '/((?<!mailto:|=|[a-zA-Z0-9._%+-])[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.‌​-]+\.[a-zA-Z]{2,64}(?![a-zA-Z]|<\/[aA]>))/';
        return preg_replace($pattern, '<a href="mailto:$1">$1</a>', $html);
    }

    public function getName()
    {
        return 'newroadsmedia_add_links_extension';
    }
}
