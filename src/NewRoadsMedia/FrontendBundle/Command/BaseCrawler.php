<?php

namespace NewRoadsMedia\FrontendBundle\Command;

use NewRoadsMedia\FrontendBundle\Entity\CrawlerExclusion;
use NewRoadsMedia\FrontendBundle\Entity\Focus;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class BaseCrawler extends ContainerAwareCommand
{
    /** @var string[] */
    protected $exclusions;

    public function crawl($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        return curl_exec($ch);
    }

    /**
     * @param $dateString
     * @return \DateTime
     */
    public function createDate($dateString) {
        $now = new \DateTime('now');
        $createDate = new \DateTime($dateString . ' 03:00:00');
        if ($createDate->format('Y-m-d') == $now->format('Y-m-d')) {
            $createDate = clone $now;
        }

        return $createDate;
    }

    public function postCrawl($url, $parameters)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));

        return curl_exec($ch);
    }

    public function setExpiration(Job $job)
    {
        $job->setDuration('4 weeks');
        $createDate = $job->getCreateDate();
        if ($createDate && (!$job->getExpirationDate() || $job->hasExpired())) {
            $expirationDate = clone $createDate;
            $now = new \DateTime('now');
            $count = 0;
            do {
                $expirationDate->modify('+' . $job->getDuration());
                $count++;
            } while ($expirationDate < $now);
            $job->setExpirationDate($expirationDate);
            if ($count > 1) {
                $createDate = clone $expirationDate;
                $createDate->modify('-' . $job->getDuration());
                $job->setCreateDate($createDate);
            }
        }
    }

    public function shouldExclude($string)
    {
        foreach ($this->getExclusions() as $exclusion) {
            if (strpos(strtolower($string), strtolower($exclusion)) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function getExclusions()
    {
        if ($this->exclusions === null) {
            $this->exclusions = array();
            /** @var CrawlerExclusion[] $crawlerExclusions */
            $crawlerExclusions = $this->getContainer()->get('journalismjobs.manager.crawler_exclusion')->findAll();
            foreach ($crawlerExclusions as $crawlerExclusion) {
                $this->exclusions[] = $crawlerExclusion->getExclusion();
            }
        }

        return $this->exclusions;
    }

    public function getPdfContent($pdfLink){
        $fileDirectory = $this->getContainer()->getParameter('kernel.root_dir') . '/files/';
        $key = substr(md5(uniqid(rand(), true)), 0, 8);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pdfLink);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec ($ch);
        $error = curl_error($ch);
        curl_close ($ch);

        $destination = $fileDirectory . $key;
        file_put_contents($destination . '.pdf', $data);
        exec('pdftohtml -i -noframes ' . $destination . '.pdf');
        $doc = new \DOMDocument;
        $doc->loadhtmlfile($destination . '.html');

        $body = $doc->getElementsByTagName('body');
        if ( $body && 0<$body->length ) {
            $body = $body->item(0);
            $adDescription = '';
            foreach ($body->childNodes as $node) {
                $adDescription .= $doc->saveHTML($node);
            }
        }

        unlink($destination . '.pdf');
        unlink($destination . '.html');
        return $adDescription;
    }
}