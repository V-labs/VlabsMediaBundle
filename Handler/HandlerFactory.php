<?php

namespace Vlabs\MediaBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;

class HandlerFactory
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
    * Return requested service from DIC
    *
    * @return \Vlabs\MediaBundle\Handler\MediaHandlerInterface
    */
    public function getHandler($identifier)
    {
        $handler = $this->container->get($identifier);

        if ($handler instanceof MediaHandlerInterface) {
            return $handler;
        }

        throw new \Exception(get_class($handler). 'must implements Vlabs\MediaBundle\BaseFileInterface');
    }

}
