<?php

/*
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Adapter;

use Doctrine\Common\EventArgs;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
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
