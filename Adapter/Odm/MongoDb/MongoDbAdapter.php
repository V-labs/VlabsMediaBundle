<?php

namespace Vlabs\MediaBundle\Adapter\Odm\MongoDb;

use Doctrine\Common\EventArgs;
use Doctrine\ODM\MongoDB\Proxy;
use Vlabs\MediaBundle\Adapter\AdapterInterface;

class MongoDbAdapter implements AdapterInterface
{
    /**
    * {@inheritDoc}
    */
    public function getObject(EventArgs $e)
    {
        return $e->getDocument();
    }

    /**
    * {@inheritDoc}
    */
    public function getLoadedMetaDatas(EventArgs $e)
    {
        return $e->getDocumentManager()->getMetadataFactory()->getLoadedMetadata();
    }

    /**
    * {@inheritDoc}
    */
    public function getClass($e)
    {
        return ($e instanceof Proxy) ? get_parent_class($e) : get_class($e);
    }
}
