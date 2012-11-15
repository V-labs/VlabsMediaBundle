<?php

namespace Vlabs\MediaBundle\Tests\Form\DataTransformer;

use Vlabs\MediaBundle\Form\DataTransformer\BaseFileToStringTransformer;

class BaseFileToStringTransformerTest extends \PHPUnit_Framework_TestCase
{
    private $transformer;

    protected function setUp()
    {
        $this->transformer = new BaseFileToStringTransformer();
    }

    public function testTransformReturnPath()
    {
        $data = $this->createBaseFileMock('fichiers/test-file.jpg');

        $this->assertEquals('fichiers/test-file.jpg', $this->transformer->transform($data));
    }

    public function testTransformReturnNull()
    {
        $this->assertNull($this->transformer->transform(null));
        $this->assertNull($this->transformer->transform(new \stdClass()));
    }

    public function testReverseTransformReturnData()
    {
        $data = $this->createBaseFileMock('fichiers/test-file.jpg');

        $this->assertEquals($data, $this->transformer->reverseTransform($data));
    }

    public function createBaseFileMock($path)
    {
        $file = $this
            ->getMockBuilder('Vlabs\MediaBundle\Entity\BaseFile')
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
}
