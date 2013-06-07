<?php

namespace Vlabs\MediaBundle\Tests\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Tests\FormIntegrationTestCase;
use Vlabs\MediaBundle\EventListener\BaseFileListener;
use Vlabs\MediaBundle\Tests\Form\Extension\TypeExtensionTest;
use Vlabs\MediaBundle\Tests\DummyFile;

class BaseFileListenerTest extends FormIntegrationTestCase
{
    protected $handlerManager;
    protected $form;
    protected $builder;

    protected function setUp()
    {
        parent::setUp();

        $this->handlerManager = $this->getHandlerManagerMock();

        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->builder = new FormBuilder(null, null, $dispatcher, $this->factory);

        $this->form = $this->factory->create('vlabs_file', null, array('add_del' => true, 'del_label' => 'Delete'));
        $this->form->setParent($this->getFormMock());
    }

    protected function getFormMock()
    {
        $mock = $this->getMock('Symfony\Component\Form\Test\FormInterface');

        $mock
            ->expects($this->any())
            ->method('getConfig')
            ->will($this->returnValue($this->getFormConfigMock()))
        ;

        return $mock;
    }

    protected function getFormConfigMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\Form\FormConfigInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getDataClass')
            ->will($this->returnValue('Vlabs\MediaBundle\Tests\DummyEntity'))
        ;

        return $mock;
    }

    protected function getHandlerManagerMock()
    {
        $handlerManager = $this->getMockBuilder('Vlabs\MediaBundle\Handler\HandlerManager')
               ->disableOriginalConstructor()
               ->getMock();

        $handlerManager
            ->expects($this->any())
            ->method('getHandler')
            ->will($this->returnValue($this->getHandlerMock()))
        ;

        $handlerManager
            ->expects($this->any())
            ->method('isDeletable')
            ->will($this->returnValue(true))
        ;

        return $handlerManager;
    }

    protected function getHandlerMock()
    {
        $handler = $this->getMockBuilder('Vlabs\MediaBundle\Handler\LocalFileSystemHandler')
               ->disableOriginalConstructor()
               ->getMock();

        $handler
            ->expects($this->any())
            ->method('getMediaClass')
            ->will($this->returnValue('Vlabs\MediaBundle\Tests\DummyEntity'))
        ;

        $handler
            ->expects($this->any())
            ->method('create')
            ->will($this->returnValue(new DummyFile()))
        ;

        return $handler;
    }

    protected function createBaseFileMock($path)
    {
        $file = $this
            ->getMockBuilder('Vlabs\MediaBundle\Entity\BaseFileInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $file
            ->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue($path))
        ;

        return $file;
    }

    protected function getFormFactoryMock()
    {
        $file = $this
            ->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $file;
    }

    public function getFormWithData()
    {
        return $this->form->setData($this->createBaseFileMock('fichiers/test-file.jpg'));
    }

    protected function getExtensions()
    {
        return array(new TypeExtensionTest($this->getHandlerManagerMock(), $this->getFormFactoryMock()));
    }

    protected function tearDown()
    {
        $this->handlerManager = null;
        $this->form = null;
        $this->builder = null;
    }

    public function testGetSubscribedEvents()
    {
        $listener = new BaseFileListener($this->handlerManager, $this->getFormFactoryMock());
        $events = $listener->getSubscribedEvents();

        $this->assertTrue(array_key_exists(FormEvents::PRE_SET_DATA, $events));
        $this->assertTrue(array_key_exists(FormEvents::SUBMIT, $events));
        $this->assertEquals($events[FormEvents::PRE_SET_DATA], 'preSetData');
        $this->assertEquals($events[FormEvents::SUBMIT], 'submit');
    }

    public function testPreSetData()
    {
        $data = '';
        $form = $this->getFormWithData();

        $event = new FormEvent($form, $data);

        $listener = new BaseFileListener($this->handlerManager, $this->getFormFactoryMock());
        $listener->preSetData($event);
    }

    public function testSubmitHandleSameFile()
    {
        $data = '';
        $form = $this->getFormWithData();

        $event = new FormEvent($form, $data);

        $listener = new BaseFileListener($this->handlerManager, $this->getFormFactoryMock());
        $listener->submit($event);

        $this->assertInstanceOf('Vlabs\MediaBundle\Entity\BaseFileInterface', $event->getData());
    }

    public function testSubmitHandleNewFileWithNonEmptyField()
    {
        $data = $file = new UploadedFile(
            __DIR__.'/../Fixtures/test.gif',
            'original.gif',
            'image/gif',
            filesize(__DIR__.'/../Fixtures/test.gif'),
            null
        );
        $form = $this->getFormWithData();

        $event = new FormEvent($form, $data);

        $listener = new BaseFileListener($this->handlerManager, $this->getFormFactoryMock());
        $listener->submit($event);

        $this->assertInstanceOf('Vlabs\MediaBundle\Entity\BaseFileInterface', $event->getData());
        $this->assertNotEquals($event->getData(), $form->getNormData());
    }

    public function testNullDatasOnSubmit()
    {
        $data = '';
        $event = new FormEvent($this->form, $data);

        $listener = new BaseFileListener($this->handlerManager, $this->getFormFactoryMock());
        $listener->submit($event);

        $this->assertNull($event->getData());
    }
}
