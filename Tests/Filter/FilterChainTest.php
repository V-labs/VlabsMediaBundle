<?php

namespace Vlabs\MediaBundle\Tests\Handler;

use Vlabs\MediaBundle\Filter\FilterChain;
use Vlabs\MediaBundle\Filter\FilterInterface;

class FilterChainTest extends \PHPUnit_Framework_TestCase
{
    public function getFilterBag()
    {
        return array(
            array(
                $this->getFilterMock('crop'),
                'crop'
            ),
            array(
                $this->getFilterMock('resize'),
                'resize'
            )
        );
    }

    private function getFilterMock($alias)
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Filter\FilterInterface')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    /**
     * @dataProvider getFilterBag
     */
    public function testFilterChainReturnGoodFilter(FilterInterface $filter, $alias)
    {
        $filterChain = new FilterChain();
        $filterChain->addFilter($filter, $alias);

        $this->assertArrayHasKey($alias, $filterChain->getFilters());
        $this->assertInstanceOf('Vlabs\MediaBundle\Filter\FilterInterface', $filterChain->getFilter($alias));
    }

    /**
     * @dataProvider getFilterBag
     * @expectedException \InvalidArgumentException
     */
    public function testFilterChainThrowException(FilterInterface $filter, $alias)
    {
        $filterChain = new FilterChain();
        $filterChain->addFilter($filter, $alias);

        $filterChain->getFilter('foo');
    }
}