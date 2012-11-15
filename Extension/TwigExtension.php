<?php

namespace Vlabs\MediaBundle\Extension;

use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Handler\HandlerManager;
use Vlabs\MediaBundle\Tools\ImageManipulatorInterface;

class TwigExtension extends \Twig_Extension
{
    /* @var \Twig_Environment */
    protected $environment;
    protected $handlerManager;
    protected $im;
    protected $templates = array();

    public function __construct(HandlerManager $handlerManager, ImageManipulatorInterface $im, $templates = array())
    {
        $this->handlerManager = $handlerManager;
        $this->im = $im;
        $this->templates = $templates;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'media' => new \Twig_Function_Method($this, 'getMedia'),
            'formPreview' => new \Twig_Function_Method($this, 'getPreview'),
        );
    }

    public function getPreview($prop, $datas, $options = array())
    {
        $getter = 'get'.ucfirst($prop);
        $baseFile = $datas->$getter();

        if ($baseFile instanceof BaseFileInterface) {
            $guesser = ExtensionGuesser::getInstance();
            $ext = $guesser->guess($baseFile->getContentType());

            switch ($ext) {
                case 'jpeg': case 'gif': case 'png':
                    $template = $this->templates['image'];
                    break;
                default :
                    $options = null;
                    $template = $this->templates['form_doc'];
                    break;
            }

            return $this->getMedia($baseFile, $template, $options);
        }
    }

    public function getMedia(BaseFileInterface $file = null, $wantedTemplate = null, $options = array())
    {
        if ($file == null) {
            return;
        }

        $handler = $this->handlerManager->getHandlerForObject($file);
        $uri = $handler->getUri($file);

        if (isset($options['resize'])) {
            $formats = $options['resize'];

            if (isset($formats['width']) && isset($formats['height'])) {
                $this->im->handleImage($uri, $file->getName(), $formats);
                $path = $this->im->getCachePath();
                $media = clone $file; //handle same object multiple times with different sizes
                $media->setPath($path);
            } else {
                throw new \Exception('Width & height must be set for resize');
            }
        } else {
            $media = $file;
            $media->setPath($uri);
        }

        unset($file);

        /* @var $template \Twig_TemplateInterface */
        if ($wantedTemplate != null) {
            if(array_key_exists($wantedTemplate, $this->templates)) {
                $wantedTemplate = $this->templates[$wantedTemplate];
            }
            
            $template = $this->environment->loadTemplate($wantedTemplate);
        } else {
            $template = $this->environment->loadTemplate($this->templates['default']);
        }

        return $template->display(array('media' => $media, 'options' => $options));
    }

    public function getName()
    {
        return 'vlabs_media_twig_extension';
    }
}
