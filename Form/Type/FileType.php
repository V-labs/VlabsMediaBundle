<?php

namespace Vlabs\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Vlabs\MediaBundle\Form\DataTransformer\BaseFileToStringTransformer;
use Vlabs\MediaBundle\EventListener\BaseFileListener;
use Vlabs\MediaBundle\Handler\HandlerManager;

class FileType extends AbstractType
{
    private $handlerManager;

    public function __construct(HandlerManager $manager)
    {
        $this->handlerManager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber(new BaseFileListener($this->handlerManager, $builder->getFormFactory()))
            ->addViewTransformer(new BaseFileToStringTransformer())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'data' => $form->getViewData()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => false,
            'add_del' => false,
            'del_label' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'vlabs_file';
    }
}
