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
class ImageCropFilter extends AbstractFilter
{
    /**
     * {@inheritDoc}
     *
     * Crop an image according to width & height
     */
    protected function doFilter(BaseFileInterface $file, $baseUri, $options)
    {
        if (is_file($baseUri)) {
            $imagine = new Imagine();
            $image =  $imagine->open($baseUri);

            $height = isset($options['height']) ? $options['height'] : null;
            $width = isset($options['width']) ? $options['width'] : null;

            if ($height !== null && $width !== null) { // Crop-resize by Width & Height
                $box = new Box($width, $height);
            }
            else {
                throw new \InvalidArgumentException('The "crop" image filter needs at least a "height" and a "width" option to be specified.');
            }

            $image
                ->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND)
                ->save($this->newPath);
        }
    }
}