<?php

namespace Vlabs\MediaBundle\Adapter;

use Doctrine\Common\EventArgs;

interface AdapterInterface
{
    /**
    * Gets the mapped object from the event arguments.
    *
    * @param EventArgs $e The event arguments.
    * @return object The mapped object.
    */
    public function getObject(EventArgs $e);

    /**
    * Gets the loaded metadatas
    *
    * @param EventArgs $e The event arguments.
    * @return object doctrine metadatas
    */
    public function getLoadedMetaDatas(EventArgs $e);

    /**
    * Get the class name
     *
     * @return string className
    */
    public function getClass($e);
}
