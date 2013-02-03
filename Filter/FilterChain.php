<?php

/**
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Filter;

use Vlabs\MediaBundle\Filter\FilterInterface;

/**
 * Filter manager. Store & retrieve all tagged services
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class FilterChain
{
    private $filters;

    public function __construct()
    {
        $this->filters = array();
    }

    public function addFilter(FilterInterface $filter, $alias)
    {
        $this->filters[$alias] = $filter;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getFilter($alias)
    {
        if (array_key_exists($alias, $this->filters)) {
            return $this->filters[$alias];
        }
        else {
            throw new \InvalidArgumentException(sprintf('The "%s" filter does not exist', $alias));
        }
    }
}