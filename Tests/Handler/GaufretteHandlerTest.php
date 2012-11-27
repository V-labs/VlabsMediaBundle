<?php

namespace Vlabs\MediaBundle\Tests\Handler;

use Vlabs\MediaBundle\Tools\Namer;
use Vlabs\MediaBundle\Tests\DummyFile;
use Vlabs\MediaBundle\Handler\GaufretteHandler;

class GaufretteHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $handler;
    protected $basePath;
    protected $targetDir;

    public function setUp()
    {
        $this->basePath = __DIR__.'/../Fixtures/test.gif';
        $this->targetDir = __DIR__.'/../Fixtures/directory';
        @copy($this->basePath, $this->targetDir.'/test.gif');
    }

    public function tearDown()
    {
        @copy($this->targetDir.'/test.gif', $this->basePath);
        @unlink($this->targetDir.'/test.gif');
        $this->handler = null;
        $this->basePath = null;
        $this->targetDir = null;
    }

    public function testMove()
    {
        $this->handler = new GaufretteHandler($this->getCdnReaderMock());
        $this->handler->setNamer(new Namer());
        $this->handler->setUploadDir($this->targetDir);
        $this->handler->setFileSystem($this->getFileSystemMock());

        $baseFile = $this->getBaseFile();
        $this->handler->move($baseFile);
    }

    public function testRemove()
    {
        $this->handler = new GaufretteHandler($this->getCdnReaderMock());
        $this->handler->setFileSystem($this->getFileSystemMock());

        $this->handler->remove($this->targetDir.'/test.gif');
    }
    
    public function testGetUri()
    {
        $this->handler = new GaufretteHandler($this->getCdnReaderMock());
        $this->handler->setFileSystem($this->getFileSystemMock());

        $baseFile = $this->getBaseFile();
        $uri = $this->handler->getUri($baseFile);
        
        $this->assertEquals(sprintf('%s', $this->basePath), $uri);
    }

    protected function getBaseFile()
    {
        $baseFile = new DummyFile();
        $baseFile->setPath($this->basePath);

        return $baseFile;
    }
    
    protected function getCdnReaderMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Annotation\CdnReader')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue(null))
        ;

        return $mock;
    }
    
    protected function getFileSystemMock()
    {
        $mock = $this->getMockBuilder('Gaufrette\Filesystem')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('write')
            ->will($this->returnValue(5325))
        ;
        
        $mock
            ->expects($this->any())
            ->method('delete')
            ->will($this->returnValue(true))
        ;

        return $mock;
    }
}
