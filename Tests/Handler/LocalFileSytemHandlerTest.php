<?php

namespace Vlabs\MediaBundle\Tests\Handler;
use Vlabs\MediaBundle\Tools\Namer;
use Vlabs\MediaBundle\Tests\DummyFile;
use Vlabs\MediaBundle\Handler\LocalFileSystemHandler;

class LocalFileSystemHandlerTest extends \PHPUnit_Framework_TestCase
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
        $this->handler = new LocalFileSystemHandler();
        $this->handler->setNamer(new Namer());
        $this->handler->setUploadDir($this->targetDir);

        $baseFile = $this->getBaseFile();
        $this->handler->move($baseFile);

        $this->assertTrue(file_exists($this->targetDir.'/'.$baseFile->getName()));
        $this->assertFalse(file_exists($this->basePath));

        @unlink($this->targetDir.'/'.$baseFile->getName());
    }

    public function testRemove()
    {
        $this->handler = new LocalFileSystemHandler();
        @copy($this->basePath, $this->targetDir.'/test.gif');

        $this->assertTrue(file_exists($this->targetDir.'/test.gif'));

        $this->handler->remove($this->targetDir.'/test.gif');

        $this->assertFalse(file_exists($this->targetDir.'/test.gif'));
    }

    protected function getBaseFile()
    {
        $baseFile = new DummyFile();
        $baseFile->setPath($this->basePath);

        return $baseFile;
    }
}
