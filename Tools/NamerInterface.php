<?php

namespace Vlabs\MediaBundle\Tools;

use Symfony\Component\HttpFoundation\File\File;

interface NamerInterface
{
    /**
     * Rename a file
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     */
    public function rename(File $file);
}
