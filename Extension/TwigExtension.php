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
use Vlabs\MediaBundle\Filter\FilterChain;
use Vlabs\MediaBundle\Filter\FilterInterface;

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
    protected $filterChain;
    protected $templates = array();

    public function __construct(HandlerManager $handlerManager, FilterChain $filterChain, $templates = array())
    {
        $this->handlerManager = $handlerManager;
        $this->filterChain = $filterChain;
        $this->templates = $templates;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'getBaseFile' => new \Twig_Function_Method($this, 'getBaseFile')
        );
    }

    public function getFilters()
    {
        return array(
            'vlabs_filter' => new \Twig_Filter_Method($this, 'filter'),
            'vlabs_media' => new \Twig_Filter_Method($this, 'displayTemplate')
        );
    }

    /**
     * Apply the wanted filter and return the media filtered
     *
     * @param \Vlabs\MediaBundle\Entity\BaseFileInterface $file
     * @param string $filterAlias
     * @param array $options
     * @return \Vlabs\MediaBundle\Entity\BaseFileInterface
     */
    public function filter(BaseFileInterface $file, $filterAlias, array $options = array())
    {
        $filter = $this->filterChain->getFilter($filterAlias);
        $handler = $this->handlerManager->getHandlerForObject($file);

        if($filter instanceof FilterInterface) {
            $filter->handle($file, $handler->getUri($file), $options);

            $media = clone $file; //handle same file with multiple filters on same page
            $media->setPath($filter->getCachePath());
        } else {
            $media = $file;
            $media->setPath($handler->getUri($file));
        }

        return $media;
    }

    /**
     * Display a template according to its identifier
     * Pass media & options
     *
     * @param \Vlabs\MediaBundle\Entity\BaseFileInterface $file
     * @param string $templateAlias
     * @param array $options
     * @return mixed
     */
    public function displayTemplate(BaseFileInterface $file, $templateAlias, array $options = array())
    {
        /* @var $template \Twig_TemplateInterface */
        if ($templateAlias != null) {
            if (array_key_exists($templateAlias, $this->templates)) {
                $templateAlias = $this->templates[$templateAlias];
            }

            $template = $this->environment->loadTemplate($templateAlias);
        } else {
            $template = $this->environment->loadTemplate($this->templates['default']);
        }

        return $template->display(array('media' => $file, 'options' => $options));
    }

    public function getBaseFile($prop, $datas)
    {
        if ($datas == null) {
            return;
        }

	// construct getter from property name.
	// if the property uses underscores each part will be camelized
	// otherwise there's only 1 part, which will also be camelized
	$getter = "get";
	foreach (explode("_", $prop) as $part) {
		$getter .= ucfirst($part);
	}

        return $datas->$getter();
    }

    public function getName()
    {
        return 'vlabs_media_twig_extension';
    }
}
