<?php

/*
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Extension;

use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Handler\HandlerManager;
use Vlabs\MediaBundle\Tools\ImageManipulatorInterface;

/**
 * Twig functions for displaying medias
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
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
        if($datas == null) {
            return;
        }
        
        $getter = 'get'.ucfirst($prop);
        $baseFile = $datas->$getter();

        if ($baseFile instanceof BaseFileInterface) {
            
            switch ($baseFile->getContentType()) {
                case 'image/jpeg': case 'image/png': case 'image/gif':
                    $template = $this->templates['form_image'];
                    break;
                default :
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

        if (!empty($options))
        {
// 			if(!is_array($options))
// 				throw new \Exception(sprintf('The "%s" filter require an array of options. You provided a %s (%s).',
// 					$filter,
// 					gettype($filterOptions),
// 					(string) $filterOptions
// 				));
				
			$this->im->handleImage($uri, $file->getName(), $options);
			$path = $this->im->getCachePath();
			$media = clone $file; // Handle same object multiple times with different sizes
			$media->setPath($path);
        }
        else
        {
        	$media = $file;
        	$media->setPath($uri);
        }

        unset($file);
        
        /* @var $template \Twig_TemplateInterface */
        if ($wantedTemplate != null) {
            if (array_key_exists($wantedTemplate, $this->templates)) {
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
