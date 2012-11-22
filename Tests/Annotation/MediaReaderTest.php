<?php

namespace Vlabs\MediaBundle\Tests\Annotation;

use Vlabs\MediaBundle\Annotation\MediaReader;
use Vlabs\MediaBundle\Tests\BadDummyEntity;

class MediaReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Doctrine\Common\Annotations\AnnotationException
     */
    public function testCdnReaderThowExceptionForIdentifier()
    {
        $entity = new BadDummyEntity();
        $mediaReader = new MediaReader();
        
        $mediaReader->handle(get_class($entity), 'file');
    }
    
    /**
     * @expectedException \Doctrine\Common\Annotations\AnnotationException
     */
    public function testCdnReaderThowExceptionForUploadDir()
    {
        $entity = new BadDummyEntity();
        $mediaReader = new MediaReader();
        
        $mediaReader->handle(get_class($entity), 'video');
    }
}
