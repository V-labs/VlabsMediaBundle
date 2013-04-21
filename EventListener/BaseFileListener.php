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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormFactoryInterface;
use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Handler\HandlerManager;
use Vlabs\MediaBundle\Form\Type\DelFileType;

/**
 * Handle form process
 * Display a delete checkbox if needed
 * Triggers upload process or data setting
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class BaseFileListener implements EventSubscriberInterface
{
    private $handlerManager;
    private $factory;

    public function __construct(HandlerManager $handlerManager, FormFactoryInterface $factory)
    {
        $this->handlerManager = $handlerManager;
        $this->factory = $factory;
    }

    /**
     * The events the listener is subscribed to.
     *
     * @return array The array of events.
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::BIND => 'bind'
        );
    }

    /**
     * Add checkbox type if needed
     *
     * @param \Symfony\Component\Form\Event\DataEvent $event
     */
    public function preSetData(DataEvent $event)
    {
        $data = $event->getData();

        if ($data instanceof BaseFileInterface) {
            $form = $event->getForm();
            $addDelBox = $form->getConfig()->getOption('add_del');

            if ($addDelBox) {
                $delLabel = $form->getConfig()->getOption('del_label');
                $delBox = $this->factory->createNamed('del-'.$form->getName(), new DelFileType($delLabel));
                $form->getParent()->add($delBox);
            }
        }
    }

    /**
     * Field logic : set actual object in form or handle new file creation
     *
     * @param \Symfony\Component\Form\Event\DataEvent $event
     */
    public function bind(DataEvent $event)
    {
        $form = $event->getForm();

        if ($form->getNormData() instanceof BaseFileInterface && !$event->getData() instanceof UploadedFile) {
            $event->setData($form->getNormData());
        }

        if ($event->getData() instanceof UploadedFile) {
            $handler = $this->handlerManager->getHandler(
                $form->getParent()->getConfig()->getDataClass(),
                (string) $form->getPropertyPath()
            );

            $datas = $handler->create($event->getData());
            $event->setData($datas);
        }

        if (is_string($event->getData())) {
            $event->setData(null);
        }
    }
}
