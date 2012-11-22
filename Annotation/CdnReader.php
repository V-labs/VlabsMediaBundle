<?php

/*
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Adapter\AdapterInterface;

/**
* Read values for the Cdn annotation
*
* @author Valentin Ferriere <valentin.ferriere@gmail.com>
*/
class CdnReader
{
    private $annotationClass = 'Vlabs\MediaBundle\Annotation\Vlabs\Cdn';

    private $baseUrl;
    private $adapter;
    private $config = array();

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
    
    /**
     * Read values from Vlabs\Cdn annotation
     * Return default CDN if not set
     *
     * @param string              $class
     * @param \ReflectionProperty $property
     */
    public function handle(BaseFileInterface $file)
    {
        $reader = new AnnotationReader();
        $property = new \ReflectionProperty($this->adapter->getClass($file), 'path');

        $cdn = $reader->getPropertyAnnotation($property, $this->annotationClass);
        
        if($cdn != null && array_key_exists($cdn->getBaseUrl(), $this->config)) {
            $this->baseUrl = $this->config[$cdn->getBaseUrl()];
        } else {
            $this->baseUrl = isset($this->config['default']) ? $this->config['default'] : null;
        }
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
    
    public function setConfig($config = array())
    {
        $this->config = $config;
    }
}
