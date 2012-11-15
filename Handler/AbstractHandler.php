<?php

namespace Vlabs\MediaBundle\Handler;

use Symfony\Component\HttpFoundation\File\File;
use Vlabs\MediaBundle\Entity\BaseFile;
use Vlabs\MediaBundle\Tools\NamerInterface;

abstract class AbstractHandler implements MediaHandlerInterface
{
    protected $mediaClass;
    protected $uploadDir;
    protected $namer;

    /**
    * {@inheritDoc}
    */
    public function create(File $file)
    {
        $mc = $this->getMediaClass();
        $baseFile = new $mc();

        $baseFile->setPath($file->getPathname());
        $baseFile->setName($file->getClientOriginalName());

        if ($baseFile instanceof BaseFile) {
            $baseFile->setCreatedAt(new \DateTime());
            $baseFile->setSize($file->getSize());
            $baseFile->setContentType($file->getMimeType());
        }

        return $baseFile;
    }

    /**
    * {@inheritDoc}
    */
    public function setMediaClass($mediaClass)
    {
        $this->mediaClass = $mediaClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setUploadDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    /**
    * {@inheritDoc}
    */
    public function getMediaClass()
    {
        return $this->mediaClass;
    }

    /**
    * {@inheritDoc}
    */
    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    /**
    * {@inheritDoc}
    */
    public function setNamer(NamerInterface $namer)
    {
        $this->namer = $namer;
    }

    /**
    * {@inheritDoc}
    */
    public function getNamer()
    {
        return $this->namer;
    }
}
