<?php

namespace NewRoadsMedia\FrontendBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileToFilenameTransformer implements DataTransformerInterface
{
    protected $baseDirectory;

    protected $directory;

    protected $filename;

    protected $isRandomName = false;

    public function __construct($baseDirectory, $directory, $isRandomName = false)
    {
        $this->isRandomName = $isRandomName;
        if (!file_exists($baseDirectory)) {
            mkdir($baseDirectory, 0777, true);
        }
        $baseDirectory = realpath($baseDirectory);
        if (!$baseDirectory) {
            throw new \Exception(sprintf('Base directory "%s" not found and could not be created in FileToFilenameTransformer.'
                , $baseDirectory
            ));
        }
        $this->baseDirectory = $baseDirectory;

        if (!empty($directory)) {
            if ($directory{0} != '/') {
                $directory = '/' . $directory;
            }
            if (substr($directory, -1) != '/') {
                $directory .= '/';
            }
        }

        $path = $baseDirectory . $directory;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if (!realpath($path)) {
            throw new \Exception(sprintf('Directory "%s" not found and could not be created in FileToFilenameTransformer.'
                , $path
            ));
        }

        $this->directory = $directory;
    }

    public function transform($filename)
    {
        if ($filename !== null) {
            $this->filename = $filename;
            $path = $this->baseDirectory . $filename;
            if (is_readable($path) && is_file($path)) {
                return new File($path);
            }
        }

        return null;
    }

    public function reverseTransform($file)
    {
        if ($file instanceof UploadedFile) {
            $originalName = $this->isRandomName ? uniqid() . '.' . $file->getClientOriginalExtension() : $file->getClientOriginalName();
            $path = $this->baseDirectory . $this->directory;
            $file->move($path, $originalName);

            return $this->directory . $originalName;
        }

        return $this->filename;
    }

    public function getFilename(File $file)
    {
        return $this->directory . $file->getFilename();
    }

    public function getUploadedFilename(UploadedFile $file)
    {
        return $this->directory . $file->getClientOriginalName();
    }

    public function getUploadedPath(UploadedFile $file)
    {
        return $this->baseDirectory . $this->directory . $file->getClientOriginalName();
    }
}