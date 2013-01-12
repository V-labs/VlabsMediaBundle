<?php

namespace Vlabs\MediaBundle\Tests\Filter;

use Vlabs\MediaBundle\Entity\BaseFileInterface;

class AbstractFilterTest extends \PHPUnit_Framework_TestCase
{
    private $cacheDir;

    public function setUp()
    {
        $this->cacheDir = __DIR__.'/../Fixtures/cache/';
    }

    public function getDataBag()
    {
        return array(
            array(
                $this->getBaseFileMock(),
                'test',
                array ('width' => 300, 'height' => 300)
            ),
        );
    }

    protected function getBaseFileMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Entity\BaseFileInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo.jpg'))
        ;

        return $mock;
    }

    /**
     * @dataProvider getDataBag
     */
    public function testDirectoryIsCreatedWithGoodPath(BaseFileInterface $file, $alias, array $options)
    {
        $abstractFilter = $this->getMockForAbstractClass('Vlabs\MediaBundle\Filter\AbstractFilter', array($this->cacheDir));
        $abstractFilter->setAlias($alias);
        $abstractFilter->handle($file, 'fooValue', $options);

        $dir = sprintf('%s/%s/%s', $this->cacheDir, $alias, md5(serialize($options)));

        $this->assertTrue(is_dir($dir));
        @rmdir($dir);
        $this->assertFalse(is_dir($dir));

        $this->assertEquals(sprintf('%s/%s', $dir, $file->getName()), $abstractFilter->getCachePath());
    }

    public function testFinderFindFile()
    {
        $abstractFilter = $this->getMockForAbstractClass('Vlabs\MediaBundle\Filter\AbstractFilter', array($this->cacheDir));
        $paths = $abstractFilter->getAllCachedPaths('test.gif');

        $this->assertCount(1, $paths);
    }
}