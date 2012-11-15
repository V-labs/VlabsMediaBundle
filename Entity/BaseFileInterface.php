<?php

/*
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Entity;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
interface BaseFileInterface
{
    /**
     * Set File path
     *
     * @param string $path
     */
    public function setPath($path);

    /**
     * Get File path
     */
    public function getPath();

    /**
     * Set File name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get File name
     */
    public function getName();

    /**
     * Set File content type
     *
     * @param string $name
     */
    public function setContentType($contentType);

    /**
     * Get File content type
     */
    public function getContentType();
}
