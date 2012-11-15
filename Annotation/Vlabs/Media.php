<?php

namespace Vlabs\MediaBundle\Annotation\Vlabs;

use Doctrine\Common\Annotations\AnnotationException;

/**
 * @Annotation
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
