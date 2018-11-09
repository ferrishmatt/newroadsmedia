<?php

namespace NewRoadsMedia\FrontendBundle\Service;

use \CloudConvert\Api;

class CloudConvert
{
    protected $apiKey;

    protected $directory;

    public function __construct($apiKey, $directory)
    {
        $this->apiKey = $apiKey;
        $this->directory = $directory;
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    public function convertToHtml($path, $filename)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $outputFile = $this->directory . DIRECTORY_SEPARATOR . $filename . '.html';
        if (!file_exists($outputFile)) {
            touch($outputFile);
            $api = new Api($this->apiKey);
            $parameters = array(
                'inputformat' => $ext,
                'outputformat' => 'html',
                'input' => 'upload',
                'file' => fopen($path, 'r'),
            );
            $api
                ->convert($parameters)
                ->wait()
                ->download($outputFile)
            ;
        }

        return file_get_contents($outputFile);
    }
}