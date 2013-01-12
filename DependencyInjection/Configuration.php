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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vlabs_media');

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue('orm')->end()
            ->end()
            ->children()
                ->scalarNode('namer')->defaultValue('Vlabs\MediaBundle\Tools\Namer')->end()
            ->end()
            ->children()
                ->arrayNode('templates')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('image_cache')
                    ->children()
                        ->scalarNode('cache_dir')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('cdn')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('mapping')->isRequired()
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('class')->isRequired()->end()
                        ->scalarNode('handler')->defaultValue('vlabs_media.handler.local_file_system')->end()
                    ->end()
                ->end()
                ->requiresAtLeastOneElement()
            ->end()
        ;

        return $treeBuilder;
    }
}
