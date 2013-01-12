<?php

/**
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class VlabsFilterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if(!$container->hasDefinition('vlabs_media.filter_chain')) {
            return;
        }

        $definition = $container->getDefinition('vlabs_media.filter_chain');
        $taggedServices = $container->findTaggedServiceIds('vlabs_media.filter');

        foreach($taggedServices as $id => $tagAttributes)
        {
            foreach ($tagAttributes as $key => $attributes)
            {
                if($key == "alias") {
                    $container->getDefinition($id)->addMethodCall('setAlias', array($attributes['alias']));
                }

                $definition->addMethodCall(
                    'addFilter',
                    array(
                        new Reference($id),
                        $attributes["alias"]
                    )
                );
            }
        }
    }
}