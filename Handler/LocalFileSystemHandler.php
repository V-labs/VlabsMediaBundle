<?php

/**
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Handler;

use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class LocalFileSystemHandler extends AbstractHandler
{
    /**
    * {@inheritDoc}
     *
     * Use the move function provided by Symfony File Object
    */
    public function move(BaseFileInterface $baseFile)
    {
        $name = $this->getNamer()->rename($baseFile);
        $file = new File($this->getUri($baseFile));

        $baseFile->setPath(sprintf('%s/%s',
                $this->getUploadDir(),
                $name
        ));

        $baseFile->setName($name);
        
        $file->move($this->getUploadDir(), $name);
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
