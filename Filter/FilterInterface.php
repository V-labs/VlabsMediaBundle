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

use Vlabs\MediaBundle\Entity\BaseFileInterface;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
interface FilterInterface
{
    /**
     * Create new path & handle directory creation
     * Call the real filter operation
     *
     * @param \Vlabs\MediaBundle\Entity\BaseFileInterface $file
     * @param $baseUri
     * @param array $options
     */
    public function handle(BaseFileInterface $file, $baseUri, array $options);

    /**
     * Parse cache filesystem according to a filename
     * Return an array of paths
     *
     * @param $name
     * @return array
     */
    public function getAllCachedPaths($name);

    /**
     * Get the cached path for a file
     *
     * @return string path
     */
    public function getCachePath();

    /**
     * Get the filter alias
     *
     * @return string alias
     */
    public function getAlias();

    /**
     * Set the filter alias
     */
    public function setAlias($alias);
}