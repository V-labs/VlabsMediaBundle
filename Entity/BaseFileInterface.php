<?php

namespace Vlabs\MediaBundle\Entity;

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
