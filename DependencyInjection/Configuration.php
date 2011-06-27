<?php

namespace Ornicar\AkismetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * This class Configuration the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 */
class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('ornicar_akismet', 'array')
            ->children()
                ->scalarNode('url')->end()
                ->scalarNode('api_key')->end()
                ->scalarNode('service')->defaultValue('ornicar_akismet.real')->end()
                ->scalarNode('throw_exceptions')->defaultValue('%kernel.debug%')
            ->end()
        ->end();

        return $treeBuilder->buildTree();
    }
}
