<?php

/*
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class VlabsMediaExtension extends Extension
{
    private $driverTags = array (
        'orm' => 'doctrine.event_subscriber',
        'odm' => 'doctrine_mongodb.odm.event_subscriber'
    );

    private $adapterMap = array(
        'orm' => 'Vlabs\MediaBundle\Adapter\Orm\DoctrineOrmAdapter',
        'odm' => 'Vlabs\MediaBundle\Adapter\Odm\MongoDb\MongoDbAdapter'
    );

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('vlabs_media.cache_dir', $config['image_cache']['cache_dir']);

        $container->setParameter('vlabs_media.adapter.class', $this->adapterMap[$config['driver']]);

        $container->setParameter('vlabs_media.namer.class', $config['namer']);

        $this->addDefaultTemplates($config);
        $container->setParameter('vlabs_media.templates', $config['templates']);
        $container->setParameter('twig.form.resources', array_merge(
            $container->getParameter('twig.form.resources'),
            array($config['templates']['vlabs_file'], $config['templates']['vlabs_del'])
        ));

        $container->getDefinition('vlabs_media.listener.uploader')->addTag($this->driverTags[$config['driver']]);
        $container->getDefinition('vlabs_media.handler.manager')->addMethodCall('setConfig', array($config['mapping']));
        $container->getDefinition('vlabs_media.annotation.cdn_reader')->addMethodCall('setConfig', array($config['cdn']));
    }

    public function addDefaultTemplates(&$config)
    {
        if (!array_key_exists('vlabs_file', $config['templates'])) {
            $config['templates']['vlabs_file'] = 'VlabsMediaBundle:Form:vlabs_file.html.twig';
        }

        if (!array_key_exists('vlabs_del', $config['templates'])) {
            $config['templates']['vlabs_del'] = 'VlabsMediaBundle:Form:vlabs_del_file.html.twig';
        }
        
        if (!array_key_exists('form_doc', $config['templates'])) {
            $config['templates']['form_doc'] = 'VlabsMediaBundle:Form:form_doc.html.twig';
        }
        
        if (!array_key_exists('form_image', $config['templates'])) {
            $config['templates']['form_image'] = 'VlabsMediaBundle:Form:form_image.html.twig';
        }

        if (!array_key_exists('default', $config['templates'])) {
            $config['templates']['default'] = 'VlabsMediaBundle:Templates:default.html.twig';
        }

        if (!array_key_exists('image', $config['templates'])) {
            $config['templates']['image'] = 'VlabsMediaBundle:Templates:image.html.twig';
        }
    }
}
