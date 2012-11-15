<?php

namespace Vlabs\MediaBundle\Tests\Handler;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AbstractHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateBaseFile()
    {
        $file = $file = new UploadedFile(
            __DIR__.'/../Fixtures/test.gif',
            'original.gif',
            'image/gif',
            filesize(__DIR__.'/../Fixtures/test.gif'),
            null
        );

        $stub = $this->getMockForAbstractClass('Vlabs\MediaBundle\Handler\AbstractHandler');
        $stub->setMediaClass('Vlabs\MediaBundle\Tests\DummyFile');
        $stub->setUploadDir('files/foo');

        $baseFile = $stub->create($file);

        $this->assertInstanceOf('Vlabs\MediaBundle\Entity\BaseFileInterface', $baseFile);
        $this->assertNull($baseFile->getId());
        $this->assertEquals('original.gif', $baseFile->getName());
        $this->assertEquals(filesize(__DIR__.'/../Fixtures/test.gif'), $baseFile->getSize());
        $this->assertInstanceOf('\DateTime', $baseFile->getCreatedAt());
        $this->assertEquals('image/gif', $baseFile->getContentType());
        $this->assertEquals('Vlabs\MediaBundle\Tests\DummyFile', $stub->getMediaClass());
        $this->assertEquals('files/foo', $stub->getUploadDir());
    }
}
