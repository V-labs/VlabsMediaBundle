<?php

namespace Vlabs\MediaBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;

class MediaReader
{
    private $annotationClass = 'Vlabs\MediaBundle\Annotation\Vlabs\Media';

    private $identifier;
    private $uploadDir;
    private $metadatas = array();

    /**
     * Read values from Vlabs\Media annotation
     * Store all readed values into metadata property
     *
     * @param string              $class
     * @param \ReflectionProperty $property
     */
    public function handle($class, $property)
    {
        $reader = new AnnotationReader();
        $property = new \ReflectionProperty($class, $property);

        $media = $reader->getPropertyAnnotation($property, $this->annotationClass);

        $this->identifier = $media->getIdentifier();
        $this->uploadDir = $media->getUploadDir();

        $this->metadatas[$class][$property->getName()]['identifier'] = $this->identifier;
        $this->metadatas[$class][$property->getName()]['uploadDir'] = $this->uploadDir;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    public function getMetaDatas()
    {
        return $this->metadatas;
    }
}
