<?php

/**
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Handler;

use Vlabs\MediaBundle\Annotation\MediaReader;
use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Adapter\AdapterInterface;

/**
 * Manage handlers creation and retrieval
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class HandlerManager
{
    private $mediaReader;
    private $factory;
    private $adapter;
    private $config = array();

    public function __construct(MediaReader $reader, HandlerFactory $factory, AdapterInterface $adapter)
    {
        $this->mediaReader = $reader;
        $this->factory = $factory;
        $this->adapter = $adapter;
    }

    /**
     * Get handler for a given property in a class
     *
     * @param  string                                           $class
     * @param  string                                           $property
     * @return \Vlabs\MediaBundle\Handler\MediaHandlerInterface
     */
    public function getHandler($class, $property)
    {
        $this->mediaReader->handle($class, $property);
        $identifier = $this->mediaReader->getIdentifier();

        $handler = $this->factory->getHandler($this->extractService($identifier));
        $handler->setMediaClass($this->extractMediaClass($identifier));
        $handler->setUploadDir($this->mediaReader->getUploadDir());

        return $handler;
    }

    /**
     * Search an retrieve handler for a class in the loaded Doctrine metadatas
     *
     * @param string $fileClass
     * @param array doctrine metadatas
     * @return \Vlabs\MediaBundle\Handler\MediaHandlerInterface
     */
    public function getHandlerForClass($fileClass, $classList)
    {
        $metadatas = $this->mediaReader->getMetaDatas();

        if(empty($metadatas)) {
            return;
        }

        $class = '';
        $property = '';

        foreach ($classList as $classInvolved) {
            if (array_key_exists($classInvolved, $metadatas)) {
                $class = $classInvolved;
                $classConfig = $metadatas[$classInvolved];
                break;
            }
        }

        foreach ($this->config as $id => $node) {
            if ($node['class'] == $fileClass) {
                $identifier = $id;
                break;
            }
        }

        foreach ($classConfig as $field => $datas) {
            if ($datas['identifier'] == $identifier) {
                $property = $field;
                break;
            }
        }

        return $this->getHandler($class, $property);
    }

    /**
     * Get the first handler able to delete in the given filesystem
     *
     * @param  string                                           $class
     * @return \Vlabs\MediaBundle\Handler\MediaHandlerInterface
     */
    public function getHandlerForDelete($class)
    {
        foreach ($this->config as $node) {
            if ($node['class'] == $class) {
                $handler =  $this->factory->getHandler($node['handler']);
                break;
            }
        }
        
        return $handler;
    }

    /**
     * Get the first handler able to manage the given object
     *
     * @param  string                                           $class
     * @return \Vlabs\MediaBundle\Handler\MediaHandlerInterface
     */
    public function getHandlerForObject(BaseFileInterface $file)
    {
        $class = $this->adapter->getClass($file);

        foreach ($this->config as $node) {
            if ($node['class'] == $class) {
                $handler =  $this->factory->getHandler($node['handler']);
                break;
            }
        }

        return $handler;
    }

    /*
     * Extract the handler identifier in DIC from config
     * for a media identifier
     *
     * @param  string $identifier
     * @return string
     */
    private function extractService($identifier)
    {
        return $this->config[$identifier]['handler'];
    }

    /*
     * Extract the media namespace from config
     * for a media identifier
     *
     * @param  string $identifier
     * @return string class namespace
     */
    private function extractMediaClass($identifier)
    {
        return $this->config[$identifier]['class'];
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

}
