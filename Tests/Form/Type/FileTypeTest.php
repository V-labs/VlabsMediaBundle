<?php

namespace Vlabs\MediaBundle\Tests\Form\Type;

use Symfony\Component\Form\Tests\Extension\Core\Type\FileTypeTest as BaseFileTypeTest;
use Vlabs\MediaBundle\Tests\Form\Extension\TypeExtensionTest;

class FileTypeTest extends BaseFileTypeTest
{
    public function testPassPathAsDataToView()
    {
        $form = $this->factory->create('vlabs_file');
        $form->setParent($this->getFormMock());
        $form->setData($this->createBaseFileMock('fichiers/test-file.jpg'));
        $view = $form->createView();

        $this->assertEquals('fichiers/test-file.jpg', $form->getViewData());
        $this->assertEquals('fichiers/test-file.jpg', $view->vars['data']);
    }

    public function testPassNullAsDataToView()
    {
        $form = $this->factory->create('vlabs_file');
        $form->setData(null);

        $view = $form->createView();

        $this->assertNull($form->getViewData());
        $this->assertNull($view->vars['data']);
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

    protected function getExtensions()
    {
        return array(new TypeExtensionTest($this->getHandlerManagerMock(), $this->getFormFactoryMock()));
    }

    protected function getHandlerManagerMock()
    {
        $handlerManager = $this->getMockBuilder('Vlabs\MediaBundle\Handler\HandlerManager')
               ->disableOriginalConstructor()
               ->getMock();

        return $handlerManager;
    }

    protected function getFormFactoryMock()
    {
        $mock = $this
            ->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $mock;
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
}
