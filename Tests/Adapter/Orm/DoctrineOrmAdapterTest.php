<?php

namespace Vlabs\MediaBundle\Tests\Adapter\Orm;

use Vlabs\MediaBundle\Adapter\Orm\DoctrineOrmAdapter;

class DoctrineOrmAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected function getEventArgsMock()
    {
        $args = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
                ->disableOriginalConstructor()
                ->getMock();

        $args
            ->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($this->getBaseFileMock()))
        ;

        $args
            ->expects($this->any())
            ->method('getEntityManager')
            ->will($this->returnValue($this->getEntityManagerMock()))
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

    public function getEntityManagerMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
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
        $mock = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadataFactory')
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
        $adapter = new DoctrineOrmAdapter();
        $adapter->getObject($this->getEventArgsMock());

        $this->assertInstanceOf('Vlabs\MediaBundle\Entity\BaseFileInterface', $adapter->getObject($this->getEventArgsMock()));
    }

    public function testGetLoadedMetaDatas()
    {
        $adapter = new DoctrineOrmAdapter();
        $adapter->getLoadedMetaDatas($this->getEventArgsMock());
    }

    public function testGetClass()
    {
        $adapter = new DoctrineOrmAdapter();
        $adapter->getClass($this->getBaseFileMock());
    }
}
