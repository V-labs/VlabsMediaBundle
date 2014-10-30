<?php

namespace Vlabs\MediaBundle\Tests;

use Doctrine\ODM\MongoDB\Proxy\Proxy;
use Symfony\Component\Validator\Constraints as Assert;
use Vlabs\MediaBundle\Annotation\Vlabs;

/**
 * Class DummyFileOdmProxy
 * Used to create a mock of Doctrine ODM Proxy
 *
 * @package Vlabs\MediaBundle\Tests
 */
abstract class DummyFileOdmProxy extends DummyFile implements Proxy
{
}