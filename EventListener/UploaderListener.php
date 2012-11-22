<?php

/*
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Vlabs\MediaBundle\Handler\HandlerManager;
use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Tools\ImageManipulatorInterface;

/**
 * Handle file system operation
 * Move & remove medias
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class UploaderListener implements EventSubscriber
{
    private $handlerManager;
    private $im;
    private $toRemove = array();

    public function __construct(HandlerManager $handlerManager, ImageManipulatorInterface $im = null)
    {
        $this->handlerManager = $handlerManager;
        $this->im = $im;
    }

    /**
     * The events the listener is subscribed to.
     *
     * @return array The array of events.
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preRemove',
            'postRemove'
        );
    }

    /**
     * Checks for for file to move.
     *
     * @param EventArgs $args The event arguments.
     */
    public function prePersist(EventArgs $args)
    {
        $entity = $this->handlerManager->getAdapter()->getObject($args);

        if ($entity instanceof BaseFileInterface) {
            $handler = $this->handlerManager->getHandlerForClass(
                $this->handlerManager->getAdapter()->getClass($entity),
                array_keys($this->handlerManager->getAdapter()->getLoadedMetaDatas($args))
            );

            $handler->move($entity);
        }
    }

    /**
     * Store the paths to remove for entity
     *
     * @param EventArgs $args The event arguments.
     */
    public function preRemove(EventArgs $args)
    {
        $entity = $this->handlerManager->getAdapter()->getObject($args);

        if ($entity instanceof BaseFileInterface) {
            $handler = $this->handlerManager->getHandlerForObject($entity);
            $this->toRemove[get_class($handler)][] = $handler->getUri($entity);

            $cachedPaths = $this->im->getAllCachedPaths($entity->getName());
            $this->toRemove[get_class($handler)] = array_merge($this->toRemove[get_class($handler)], $cachedPaths);
        }
    }

    /**
     * Remove the file
     *
     * @param EventArgs $args The event arguments.
     */
    public function postRemove(EventArgs $args)
    {
        $entity = $this->handlerManager->getAdapter()->getObject($args);
        
        if ($entity instanceof BaseFileInterface) {
            $handler = $this->handlerManager->getHandlerForDelete(
                    $this->handlerManager->getAdapter()->getClass($entity)
                );
            
            foreach ($this->toRemove as $handlerClass => $paths) {
                if(get_class($handler) == $handlerClass) {
                    foreach($paths as $path) {
                        $handler->remove($path);
                    }
                }
            }
        }
    }
}
