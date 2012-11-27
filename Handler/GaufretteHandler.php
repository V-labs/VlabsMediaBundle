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

use Gaufrette\Filesystem;
use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Annotation\CdnReader;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class GaufretteHandler extends AbstractHandler
{
    /*@var \Gaufrette\Filesystem */
    protected $fs;
    protected $cdn;
    
    public function __construct(CdnReader $cdn) 
    {
        $this->cdn = $cdn;
    }
    
    /**
     * {@inheritDoc}
     */
    public function move(BaseFileInterface $baseFile)
    {
        $name = $this->getNamer()->rename($baseFile);
        $content = file_get_contents($baseFile->getPath());
        
        $baseFile->setPath(sprintf('%s/%s',
                $this->getUploadDir(),
                $name
        ));

        $baseFile->setName($name);
        
        $this->fs->write($baseFile->getPath(), $content);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($path)
    {
        $this->fs->delete($path);
    }

    /**
     * {@inheritDoc}
     * 
     * Get the base url from object annotation
     */
    public function getUri(BaseFileInterface $baseFile)
    {
        $this->cdn->handle($baseFile);
        $host = $this->cdn->getBaseUrl();
        
        if(!empty($host)) {
            $uri = sprintf('%s/%s', $host, $baseFile->getPath());
        } else {
            $uri = sprintf('%s', $baseFile->getPath());
        }
        
        return $uri;
    }
    
    /**
     * Set the given configuration file system 
     * 
     * @param \Gaufrette\Filesystem $fs
     */
    public function setFileSystem(Filesystem $fs)
    {
        $this->fs = $fs;
    }
}