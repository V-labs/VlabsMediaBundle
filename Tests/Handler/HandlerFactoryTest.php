<?php

namespace Vlabs\MediaBundle\Tests\Handler;

use Vlabs\MediaBundle\Handler\HandlerFactory;

class HandlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function getContainerMock($identifier)
    {
        $mock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')
               ->disableOriginalConstructor()
               ->getMock();
        
        switch($identifier)
        {
            case 'vlabs_media.handler.local_file_system';
                $mock
                    ->expects($this->any())
                    ->method('get')
                    ->will($this->returnValue($this->getLocalFileSystemHandlerMock()))
                ;
                break;
            
            case 'vlabs_gaufrette_dummy_fs' :
                $mock
                    ->expects($this->at(0))
                    ->method('get')
                    ->with('vlabs_media.handler.gaufrette')
                    ->will($this->returnValue($this->getGaufretteHandlerMock()))
                ;

                $mock
                    ->expects($this->at(1))
                    ->method('get')
                    ->with('knp_gaufrette.filesystem_map')
                    ->will($this->returnValue($this->getFileSystemMapMock()))
                ;
                break;
            
            default :
                $mock
                    ->expects($this->any())
                    ->method('get')
                    ->will($this->returnValue(new \stdClass()))
                ;
                break;
        }

        return $mock;
    }
    
    public function getFileSystemMapMock()
    {
        $mock = $this->getMockBuilder('Gaufrette\FilesystemMap')
           ->disableOriginalConstructor()
           ->getMock();
        
        $mock
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->getFileSystemMock()))
        ;
        
        return $mock;
    }
    
    public function getFileSystemMock()
    {
        $mock = $this->getMockBuilder('Gaufrette\Filesystem')
           ->disableOriginalConstructor()
           ->getMock();
        
        return $mock;
    }
    
    public function getGaufretteHandlerMock()
    {
        return $this->getMockBuilder('Vlabs\MediaBundle\Handler\GaufretteHandler')
                                ->disableOriginalConstructor()
                                ->getMock();
    }
    
    public function getLocalFileSystemHandlerMock()
    {
        return $this->getMockBuilder('Vlabs\MediaBundle\Handler\LocalFileSystemHandler')
                                ->disableOriginalConstructor()
                                ->getMock();
    }
    
    public function identifiersBag()
    {
        return array(
            array('vlabs_media.handler.local_file_system'),
            array('vlabs_gaufrette_dummy_fs'),
        );
    }
    
    /**
     * @dataProvider identifiersBag
     */
    public function testFactoryReturnHandler($identifier)
    {
        $factory = new HandlerFactory($this->getContainerMock($identifier));
        $handler = $factory->getHandler($identifier);
        
        $this->assertInstanceOf('Vlabs\MediaBundle\Handler\MediaHandlerInterface', $handler);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testFactoryReturnException()
    {
        $factory = new HandlerFactory($this->getContainerMock('foo'));
        $factory->getHandler('foo');
    }
}