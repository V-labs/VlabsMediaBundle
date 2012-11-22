<?php

namespace Vlabs\MediaBundle\Tests;

use Vlabs\MediaBundle\Annotation\Vlabs;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vlabs\MediaBundle\Tests\BadDummyEntity
 */
class BadDummyEntity
{
    /**
     * @var DummyFile
     *
     * @Vlabs\Media()
     * @Assert\Valid()
     */
    private $file;
    
    /**
     * @var DummyFile
     *
     * @Vlabs\Media(identifier="dummy_identifier")
     * @Assert\Valid()
     */
    private $video;

    /**
     * Set file
     *
     * @param  DummyFile   $file
     * @return DummyEntity
     */
    public function setFile(DummyFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return Vlabs\TestBundle\Entity\VlabsFile
     */
    public function getFile()
    {
        return $this->file;
    }
}
