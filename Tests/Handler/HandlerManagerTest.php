<?php

namespace Vlabs\MediaBundle\Tests\Handler;

use Vlabs\MediaBundle\Annotation\MediaReader;
use Vlabs\MediaBundle\Handler\HandlerManager;

class HandlerManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $adapter;
    protected $mediaReader;

    public function setUp()
    {
        $this->mediaReader = new MediaReader();
        $this->adapter = $this->getAdapterMock();
    }

    protected function getConfigDatas($handlerId)
    {
        $config = array('dummy_identifier' => array(
            'handler'=> $handlerId,
            'class' => 'Vlabs\MediaBundle\Tests\DummyFile'
            )
        );

        return $config;
    }

    public function getFactoryMock($identifier)
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Handler\HandlerFactory')
               ->disableOriginalConstructor()
               ->getMock();

        switch ($identifier) {
            case 'vlabs_media.handler.local_file_system':
                $toReturn = $this->getMockBuilder('Vlabs\MediaBundle\Handler\LocalFileSystemHandler')
                                ->disableOriginalConstructor()
                                ->getMock();
                break;
            case 'vlabs_media.handler.gaufrette':
                $toReturn = $this->getMockBuilder('Vlabs\MediaBundle\Handler\GaufretteHandler')
                                ->disableOriginalConstructor()
                                ->getMock();
                break;
        }

        $mock
            ->expects($this->any())
            ->method('getHandler')
            ->will($this->returnValue($toReturn))
        ;

        return $mock;
    }

    public function handlersBag()
    {
        return array(
            array('vlabs_media.handler.local_file_system', 'Vlabs\MediaBundle\Handler\LocalFileSystemHandler'),
            array('vlabs_media.handler.gaufrette', 'Vlabs\MediaBundle\Handler\GaufretteHandler')
        );
    }

    protected function getAdapterMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Adapter\AdapterInterface')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getClass')
            ->will($this->returnValue('Vlabs\MediaBundle\Tests\DummyFile'))
        ;

        return $mock;
    }

    protected function getDummyFileMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Tests\DummyFile')
               ->disableOriginalConstructor()
               ->getMock();

        return $mock;
    }

    public function tearDown()
    {
        $this->mediaReader = null;
        $this->adapter = null;
    }

    /**
     * @dataProvider handlersBag
     */
    public function testHandlerIsCreatedWithClassAndProperty($handlerId, $handlerClass)
    {
        $config = $this->getConfigDatas($handlerId);
        $factory = $this->getFactoryMock($handlerId);

        $handlerManager = new HandlerManager($this->mediaReader, $factory, $this->adapter);
        $handlerManager->setConfig($config);

        $handler = $handlerManager->getHandler('Vlabs\MediaBundle\Tests\DummyEntity', 'file');

        $this->assertInstanceOf($handlerClass, $handler);
    }

    /**
     * @dataProvider handlersBag
     * @expectedException \Doctrine\Common\Annotations\AnnotationException
     */
    public function testHandlerCreationFailsWithoutIdentifier($handlerId, $handlerClass)
    {
        $config = $this->getConfigDatas($handlerId);
        $factory = $this->getFactoryMock($handlerId);

        $handlerManager = new HandlerManager($this->mediaReader, $factory, $this->adapter);
        $handlerManager->setConfig($config);

        $handlerManager->getHandler('Vlabs\MediaBundle\Tests\BadDummyEntity', 'file');
    }

    /**
     * @dataProvider handlersBag
     */
    public function testHandlerIsReturnedWithClassAndList($handlerId, $handlerClass)
    {
        $config = $this->getConfigDatas($handlerId);
        $factory = $this->getFactoryMock($handlerId);

        $handlerManager = new HandlerManager($this->mediaReader, $factory, $this->adapter);
        $handlerManager->setConfig($config);

        //init mediaReader metadatas
        $handlerManager->getHandler('Vlabs\MediaBundle\Tests\DummyEntity', 'file');

        $classList = array(
            'Vlabs\MediaBundle\Tests\DummyEntity',
            'Vlabs\MediaBundle\Tests\DummyFile',
            'Vlabs\MediaBundle\Foo\Bar'
        );

        $handler = $handlerManager->getHandlerForClass('Vlabs\MediaBundle\Tests\DummyFile', $classList);

        $this->assertInstanceOf($handlerClass, $handler);
    }

    /**
     * @dataProvider handlersBag
     */
    public function testHandlerIsReturnedForDelete($handlerId, $handlerClass)
    {
        $config = $this->getConfigDatas($handlerId);
        $factory = $this->getFactoryMock($handlerId);

        $handlerManager = new HandlerManager($this->mediaReader, $factory, $this->adapter);
        $handlerManager->setConfig($config);

        $handler = $handlerManager->getHandlerForDelete('Vlabs\MediaBundle\Tests\DummyFile');

        $this->assertInstanceOf($handlerClass, $handler);
    }

    /**
     * @dataProvider handlersBag
     */
    public function testHandlerIsReturnedForObject($handlerId, $handlerClass)
    {
        $config = $this->getConfigDatas($handlerId);
        $factory = $this->getFactoryMock($handlerId);

        $handlerManager = new HandlerManager($this->mediaReader, $factory, $this->adapter);
        $handlerManager->setConfig($config);

        $handler = $handlerManager->getHandlerForObject($this->getDummyFileMock());

        $this->assertInstanceOf($handlerClass, $handler);
        $this->assertInstanceOf('Vlabs\MediaBundle\Adapter\AdapterInterface', $handlerManager->getAdapter());
    }
}
