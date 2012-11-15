<?php

namespace Vlabs\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DelFileType extends AbstractType
{
    private $label;
    
    public function __construct($label)
    {
        $this->label = $label;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
            'mapped' => false,
            'label' => $this->label
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'checkbox';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'vlabs_del_file';
    }
}
