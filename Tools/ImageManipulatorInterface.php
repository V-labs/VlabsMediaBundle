<?php

/**
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Tools;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
interface ImageManipulatorInterface
{
    /**
     * Resize the file if needed
     * Set the cached path for the resized image
     *
     * @param string $path
     * @param string $name
     * @param array  $formats
     */
    public function handleImage($path, $name, $formats);

    /**
     * Return the cached path for the resized image
     *
     * @return string path
     */
    public function getCachePath();

    /**
     * Get the paths of all files stored in cache
     *
     * @param  string $name
     * @return array  paths
     */
    public function getAllCachedPaths($name);
}
