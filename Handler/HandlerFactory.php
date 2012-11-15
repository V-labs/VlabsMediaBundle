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

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
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
