<?php

namespace Vlabs\MediaBundle\Tests;

use Vlabs\MediaBundle\Entity\BaseFile as VlabsFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vlabs\MediaBundle\Tests\DummyFile
 */
class DummyFileWithoutCdn extends VlabsFile
{
    /**
     * @var string $path
     *
     * @Assert\Image()
     */
    private $path;

    /**
     * Set path
     *
     * @param  string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

}
