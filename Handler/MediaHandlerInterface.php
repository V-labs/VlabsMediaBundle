<?php

/**
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Handler;

use Symfony\Component\HttpFoundation\File\File;
use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Tools\NamerInterface;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
interface MediaHandlerInterface
{
    /**
     * Create a BaseFileInterface instance from Symfony File
     *
     * @param  \Symfony\Component\HttpFoundation\File\File $file
     * @return \Vlabs\MediaBundle\Entity\BaseFileInterface
     */
    public function create(File $file);

    /**
     * Move the file to the requested path
     *
     * @param \Vlabs\MediaBundle\Entity\BaseFileInterface $baseFile
     */
    public function move(BaseFileInterface $baseFile);

    /**
     * Delete the file at the specified path
     *
     * @param type $path
     */
    public function remove($path);

    /**
     * Get media Uri
     *
     * @param  \Vlabs\MediaBundle\Entity\BaseFileInterface $baseFile
     * @return string                                      the media path
     */
    public function getUri(BaseFileInterface $baseFile);

    /**
     * Set the path where the media is uploaded
     *
     * @param string $uploadDir
     */
    public function setUploadDir($uploadDir);

    /**
     * Get the wanted upload dir for file
     *
     * @return string path to upload
     */
    public function getUploadDir();

    /**
     * Set the path of the media class
     *
     * @param string $mediaClass
     */
    public function setMediaClass($mediaClass);

    /**
     * Get the media class name
     *
     * @return string class namespace
     */
    public function getMediaClass();

    /**
     * Set the namer object
     *
     * @param \Vlabs\MediaBundle\Tools\NamerInterface $namer
     */
    public function setNamer(NamerInterface $namer);

    /**
     * Get the namer object
     *
     * @return \Vlabs\MediaBundle\Tools\NamerInterface
     */
    public function getNamer();
}
