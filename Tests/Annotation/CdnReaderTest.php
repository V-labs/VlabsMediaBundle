<?php

namespace Vlabs\MediaBundle\Tests\Annotation;

use Vlabs\MediaBundle\Annotation\CdnReader;
use Vlabs\MediaBundle\Tests\DummyFile;
use Vlabs\MediaBundle\Tests\DummyFileWithoutCdn;

class CdnReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testCdnReaderReturnDummyHost()
    {
        $entity = new DummyFile();
        $cdnReader = new CdnReader($this->getAdapterMock($entity));
        $config = array('dummy_host' => 'http://www.foo.com');
        $cdnReader->setConfig($config);
        
        $cdnReader->handle($entity);
        
        $this->assertEquals('http://www.foo.com', $cdnReader->getBaseUrl());
    }
    
    public function testCdnReaderReturnDefaultHost()
    {
        $entity = new DummyFileWithoutCdn();
        $cdnReader = new CdnReader($this->getAdapterMock($entity));
        $config = array('default' => 'http://www.foo.com');
        $cdnReader->setConfig($config);
        
        $cdnReader->handle($entity);
        
        $this->assertEquals('http://www.foo.com', $cdnReader->getBaseUrl());
    }
    
    public function getAdapterMock($object)
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Adapter\AdapterInterface')
               ->disableOriginalConstructor()
               ->getMock();

        $mock
            ->expects($this->any())
            ->method('getClass')
            ->will($this->returnValue(get_class($object)))
        ;

        return $mock;
    }
}
