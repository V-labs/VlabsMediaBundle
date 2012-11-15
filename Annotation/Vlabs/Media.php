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
class Media
{
    private $identifier;
    private $uploadDir;

    /**
     * Store options from the Vlabs\Media annotation
     *
     * @param  array                                            $options
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct($options)
    {
        if (isset($options['identifier'])) {
            $this->identifier = $options['identifier'];
        } else {
            $e = new AnnotationException();
            throw $e->requiredError('identifier', 'Vlabs\Media', 'entity', 'configuration entity identifier');
        }

        if (isset($options['upload_dir'])) {
            $this->uploadDir = $options['upload_dir'];
        } else {
            $e = new AnnotationException();
            throw $e->requiredError('upload_dir', 'Vlabs\Media', 'entity', 'configuration media upload directory');
        }
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getUploadDir()
    {
        return $this->uploadDir;
    }
}
