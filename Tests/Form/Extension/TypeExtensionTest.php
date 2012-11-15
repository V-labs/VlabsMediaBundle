<?php

namespace Vlabs\MediaBundle\Tests\Form\Extension;

use Symfony\Component\Form\Extension\Core\CoreExtension;
use Vlabs\MediaBundle\Form\Type\FileType;
use Vlabs\MediaBundle\Handler\HandlerManager;
use Symfony\Component\Form\FormFactoryInterface;

class TypeExtensionTest extends CoreExtension
{
    private $handlerManager;
    private $factory;

    public function __construct(HandlerManager $handlerManager, FormFactoryInterface $factory)
    {
        $this->handlerManager = $handlerManager;
        $this->factory = $factory;
    }

    protected function loadTypes()
    {
        return array_merge(parent::loadTypes(), array(
            new FileType($this->handlerManager, $this->factory),
        ));
    }
}
