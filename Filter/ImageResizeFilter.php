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

use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use Imagine\Gd\Imagine;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 * @author Jérémy CROMBEZ <contact@easytek.fr>
 */
class ImageResizeFilter extends AbstractFilter
{
    /**
     * {@inheritDoc}
     *
     * Resize an image according to width and/or height.
     * upscale & keepRatio can also be used.
     */
    protected function doFilter(BaseFileInterface $file, $baseUri, $options)
    {
        if (is_file($baseUri)) {
            $imagine = new Imagine();
            $image =  $imagine->open($baseUri);

            $height = isset($options['height']) ? $options['height'] : null;
            $width = isset($options['width']) ? $options['width'] : null;
            $upscale = isset($options['upscale']) ? $options['upscale'] : null;
            $keepRatio = isset($options['keepRatio']) ? $options['keepRatio'] : null;

            if (!isset($upscale)) {
                $upscale = false;
            }

            if (!isset($keepRatio)) {
                $keepRatio = true;
            }

            if ($height !== null && $width !== null) { // Scale by Width & Height
                $originalWidth = $image->getSize()->getWidth();
                $originalHeight = $image->getSize()->getHeight();

                if (!$upscale && ($width > $originalWidth || $height >  $originalHeight)) {
                    $max = min($originalWidth, $originalHeight);
                    $width = $max;
                    $height = $max;
                }

                if ($keepRatio) {
                    if ($width >= $height) {
                        $box = $image->getSize()->widen($width);
                    }
                    else {
                        $box = $image->getSize()->heighten($width);
                    }
                }
                else {
                    $image
                        ->resize(new Box($width, $height))
                        ->save($this->newPath);

                    return;
                }

            }
            elseif ($height === null && $width !== null) { // Scale by Width
                $box = $image->getSize()->widen($width);
            }
            elseif ($width === null && $height !== null) { // Scale by Height
                $box = $image->getSize()->heighten($height);
            }
            else {
                throw new \InvalidArgumentException('The "resize" image filter needs at least a "height" or a "width" option to be specified.');
            }

            $image
                ->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND)
                ->save($this->newPath);
        }
    }
}