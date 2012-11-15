<?php

namespace Vlabs\MediaBundle\Tests\EventListener;

use Vlabs\MediaBundle\EventListener\UploaderListener;

class UploaderListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $adapter;
    protected $handlerManager;
    protected $imageManipulator;

    public function setUp()
    {
        $this->adapter = $this->getAdapterMock();
        $this->handlerManager = $this->getHandlerManagerMock();
        $this->imageManipulator = $this->getImageManipulatorMock();
    }

    protected function getAdapterMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Adapter\AdapterInterface')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getObject')
            ->will($this->returnValue($this->getBaseFileMock()))
        ;

        $mock
            ->expects($this->any())
            ->method('getLoadedMetaDatas')
            ->will($this->returnValue(array()))
        ;

        return $mock;
    }

    protected function getHandlerManagerMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Handler\HandlerManager')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getHandlerForClass')
            ->will($this->returnValue($this->getHandlerMock()))
        ;

        $mock
            ->expects($this->any())
            ->method('getHandlerForDelete')
            ->will($this->returnValue($this->getHandlerMock()))
        ;

        $mock
            ->expects($this->any())
            ->method('getHandlerForObject')
            ->will($this->returnValue($this->getHandlerMock()))
        ;

        $mock
            ->expects($this->any())
            ->method('getAdapter')
            ->will($this->returnValue($this->adapter))
        ;

        return $mock;
    }

    public function getHandlerMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Handler\MediaHandlerInterface')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getUri')
            ->will($this->returnValue(__DIR__.'/../Fixtures/test.gif'))
        ;

        return $mock;
    }

    public function getBaseFileMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Entity\BaseFileInterface')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue(__DIR__.'/../Fixtures/test.gif'))
        ;

        return $mock;
    }

    public function getImageManipulatorMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Tools\ImageManipulatorInterface')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getAllCachedPaths')
            ->will($this->returnValue(array()))
        ;

        return $mock;
    }

    public function tearDown()
    {
        $this->handlerManager = null;
        $this->imageManipulator = null;
        $this->adapter = null;
    }

    public function testGetSubscribedEvents()
    {
        $listener = new UploaderListener($this->handlerManager, $this->imageManipulator);
        $events = $listener->getSubscribedEvents();

        $this->assertTrue(in_array('prePersist', $events));
        $this->assertTrue(in_array('preRemove', $events));
        $this->assertTrue(in_array('postRemove', $events));
    }

    public function testPrePersist()
    {
        $args = $this->getMockBuilder('Doctrine\Common\EventArgs')
                ->disableOriginalConstructor()
                ->getMock();

        $listener = new UploaderListener($this->handlerManager, $this->imageManipulator);
        $listener->prePersist($args);
    }

    public function testPreRemove()
    {
        $args = $this->getMockBuilder('Doctrine\Common\EventArgs')
                ->disableOriginalConstructor()
                ->getMock();

        $listener = new UploaderListener($this->handlerManager, $this->imageManipulator);
        $listener->preRemove($args);
    }

    public function testPostRemove()
    {
        $args = $this->getMockBuilder('Doctrine\Common\EventArgs')
                ->disableOriginalConstructor()
                ->getMock();

        $listener = new UploaderListener($this->handlerManager, $this->imageManipulator);
        $listener->preRemove($args);
        $listener->postRemove($args);
    }
}
