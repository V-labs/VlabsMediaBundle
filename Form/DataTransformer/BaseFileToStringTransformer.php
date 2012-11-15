<?php

/*
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Vlabs\MediaBundle\Entity\BaseFileInterface;

/**
 * Return the path in the form view data.
 * Needed to get a scalar return.
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class BaseFileToStringTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        return ($data instanceof BaseFileInterface) ? (string) $data->getPath() : null;
    }

    /**
     * {@inheritdoc}
     *
     * Handled by BaseFileListener on bind event
     */
    public function reverseTransform($data)
    {
        return $data;
    }
}
