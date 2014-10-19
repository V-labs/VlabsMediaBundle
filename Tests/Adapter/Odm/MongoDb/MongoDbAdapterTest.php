<?php

namespace Vlabs\MediaBundle\Tests\Adapter\MongoDb\Odm;

use Vlabs\MediaBundle\Adapter\Odm\MongoDb\MongoDbAdapter;

class MongoDbAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected function getEventArgsMock()
    {
        $args = $this->getMockBuilder('Doctrine\ODM\MongoDB\Event\LifecycleEventArgs')
                ->disableOriginalConstructor()
                ->getMock();

        $args
            ->expects($this->any())
            ->method('getDocument')
            ->will($this->returnValue($this->getBaseFileMock()))
        ;

        $args
            ->expects($this->any())
            ->method('getDocumentManager')
            ->will($this->returnValue($this->getDocumentManagerMock()))
        ;

        return $args;
    }

    public function getBaseFileMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Entity\BaseFileInterface')
               ->disableOriginalConstructor()
               ->getMock();

        return $mock;
    }

    public function getDocumentManagerMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ODM\MongoDB\DocumentManager')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getMetadataFactory')
            ->will($this->returnValue($this->getClassMetadataFactoryMock()))
        ;

        return $mock;
    }

    public function getClassMetadataFactoryMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getLoadedMetadata')
            ->will($this->returnValue(array()))
        ;

        return $mock;
    }

    public function testGetObjet()
    {
        $adapter = new MongoDbAdapter();
        $adapter->getObject($this->getEventArgsMock());

        $this->assertInstanceOf('Vlabs\MediaBundle\Entity\BaseFileInterface', $adapter->getObject($this->getEventArgsMock()));
    }

    public function testGetLoadedMetaDatas()
    {
        $adapter = new MongoDbAdapter();
        $adapter->getLoadedMetaDatas($this->getEventArgsMock());
    }

    public function testGetClass()
    {
        $adapter = new MongoDbAdapter();
        $adapter->getClass($this->getBaseFileMock());
    }

    /**
     * getClass() must return object's parent class if ODM proxy object is passed
     */
    public function testGetClassFromOdmProxy()
    {
        $odmProxyMock = $this->getMockBuilder('Vlabs\MediaBundle\Tests\DummyFileOdmProxy')
            ->getMockForAbstractClass();

        $adapter = new MongoDbAdapter();

        $classFromMock = $adapter->getClass($odmProxyMock);

        // phpunit mock itself is a subclass of a requested class, need to get level up
        $class = get_parent_class($classFromMock);

        $this->assertEquals('Vlabs\MediaBundle\Tests\DummyFile', $class);
    }
}
