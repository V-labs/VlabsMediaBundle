<?php

/*
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Annotation\Vlabs;

use Doctrine\Common\Annotations\AnnotationException;

/**
 * @Annotation
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class Cdn
{
    private $baseUrl;

    /**
     * Store options from the Vlabs\Cdn annotation
     *
     * @param  array                                            $options
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct($options)
    {
        if (isset($options['base_url'])) {
            $this->baseUrl = $options['base_url'];
        }
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
