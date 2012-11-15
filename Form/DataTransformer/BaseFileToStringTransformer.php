<?php

namespace Vlabs\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Vlabs\MediaBundle\Entity\BaseFileInterface;

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
