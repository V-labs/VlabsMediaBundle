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

use Symfony\Component\Finder\Finder;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use Vlabs\MediaBundle\Tools\NamerInterface;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class ImageManipulator implements ImageManipulatorInterface
{
    protected $newPath;
    protected $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function handleImage($path, $name, $filters)
    {
    	$pathinfo = pathinfo($name);
    	
        $this->newPath = sprintf('%s/%s/%s',
            $this->cacheDir,
            md5(serialize($filters)),
            $name
        );

        if (!is_file($this->newPath)) {
            @mkdir(sprintf('%s', $this->cacheDir));
            @mkdir(sprintf('%s/%s', $this->cacheDir, md5(serialize($filters))));

            if (is_file($path)) {
                $imagine = new Imagine();
                $image =  $imagine->open($path);

                foreach ($filters as $filter => $filterOptions) {
                    $height = isset($filterOptions['height']) ? $filterOptions['height'] : null;
                    $width = isset($filterOptions['width']) ? $filterOptions['width'] : null;

                    if ($filter == 'resize') {
                        $upscale = isset($filterOptions['upscale']) ? $filterOptions['upscale'] : null;
                        $keepRatio = isset($filterOptions['keepRatio']) ? $filterOptions['keepRatio'] : null;

                        $image = $this->getResizedBox(
                            $image,
                            $height,
                            $width,
                            $upscale,
                            $keepRatio
                        );
                    } elseif ($filter == 'crop') {
                        $image = $this->getCroppedBox(
                            $image,
                            $height,
                            $width
                        );
                    } else
                        throw new \Exception(sprintf('The "%s" image filter does not exist', $filter));
                }

                $image->save($this->newPath);
            }
        }
    }

    /**
     * Create a resized Box based on an Image and a height and/or a width.
     * Upscale to true will authorize the box to be bigger than the original image dimensions.
     * keepRatio false will authorize the box to ignore the image ratio.
     * 
     * @param  ImageInterface     $image
     * @param  integer            $height
     * @param  integer            $width
     * @throws \Exception
     * @return \Imagine\Image\Box
     */
    protected function getResizedBox(ImageInterface $image, $height = null, $width = null, $upscale = false, $keepRatio = true)
    {
        if (!isset($upscale))
            $upscale = false;

        if (!isset($keepRatio))
            $keepRatio = true;

        if ($height !== null && $width !== null) { // Scale by Width & Height
            $originalWidth = $image->getSize()->getWidth();
            $originalHeight = $image->getSize()->getHeight();

            if (!$upscale && ($width > $originalWidth || $height >  $originalHeight)) {
                   $max = min($originalWidth, $originalHeight);
                   $width = $max;
                   $height = $max;
            }

            if ($keepRatio)
                if ($width >= $height)
                    $box = $image->getSize()->widen($width);
                else
                    $box = $image->getSize()->heighten($width);
            else
                return $image->resize(new Box($width, $height));

        } elseif ($height === null && $width !== null) { // Scale by Width
            $box = $image->getSize()->widen($width);
        } elseif ($width === null && $height !== null) { // Scale by Height
            $box = $image->getSize()->heighten($height);
        } else
            throw new \Exception('The "resize" image filter needs at least a "height" or a "width" option to be specified.');

        return $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);;
    }

    /**
     * Create a resized Box, using cropping, based on an Image and a new height and a new width.
     * 
     * @param  ImageInterface     $image
     * @param  integer            $height
     * @param  integer            $width
     * @throws \Exception
     * @return \Imagine\Image\Box
     */
    protected function getCroppedBox(ImageInterface $image, $height = null, $width = null)
    {
        if ($height !== null && $width !== null) { // Crop-resize by Width & Height
            $box = new Box($width, $height);
        } else {
        	throw new \Exception('The "crop" image filter needs at least a "height" and a "width" option to be specified.');
        }

        return $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);;
    }

    public function getAllCachedPaths($name)
    {
        $paths = array();

        $finder = new Finder();
        $finder->name($name);

        foreach ($finder->in($this->cacheDir) as $file) {
            /* @var $file \Symfony\Component\Finder\SplFileInfo */
            $paths[] = sprintf('%s/%s', $this->cacheDir, $file->getRelativePathname());
        }

        return $paths;
    }

    public function getCachePath()
    {
        return $this->newPath;
    }

    public function setNamer(NamerInterface $namer)
    {
        $this->namer = $namer;
    }

    public function getNamer()
    {
        return $this->namer;
    }
}
