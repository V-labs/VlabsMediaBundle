<?php

namespace Vlabs\MediaBundle\Tests;

use Vlabs\MediaBundle\Annotation\Vlabs;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vlabs\MediaBundle\Tests\DummyEntity
 */
class DummyEntity
{
    /**
     * @var DummyFile
     *
     * @Vlabs\Media(identifier="dummy_identifier", upload_dir="images")
     * @Assert\Valid()
     */
    private $file;

    /**
     * @var DummyFile
     *
     * @Vlabs\Media(identifier="dummy_identifier2", upload_dir="images")
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private $image;

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
