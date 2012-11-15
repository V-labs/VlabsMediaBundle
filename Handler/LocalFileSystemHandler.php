<?php

namespace Vlabs\MediaBundle\Handler;

use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Symfony\Component\HttpFoundation\File\File;

class LocalFileSystemHandler extends AbstractHandler
{
    /**
    * {@inheritDoc}
     *
     * Use the move function provided by Symfony File Object
    */
    public function move(BaseFileInterface $baseFile)
    {
        $file = new File($this->getUri($baseFile));
        $name = $this->getNamer()->rename($file);

        $file = $file->move($this->getUploadDir(), $name);

        $baseFile->setPath(sprintf('%s/%s',
                $file->getPath(),
                $name
        ));

        $baseFile->setName($name);
    }

    /**
    * {@inheritDoc}
     *
     * Unlink at given path on local filesystem
    */
    public function remove($path)
    {
        @unlink($path);
    }

    /**
    * {@inheritDoc}
    */
    public function getUri(BaseFileInterface $baseFile)
    {
        return $baseFile->getPath();
    }
}
