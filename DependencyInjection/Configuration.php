<?php

namespace Ornicar\AkismetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class Configuration the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ornicar_akismet');
        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('ornicar_akismet', 'array');
        }

         $rootNode
            ->children()
                ->scalarNode('url')->isRequired()->end()
                ->scalarNode('api_key')->isRequired()->end()
                ->scalarNode('service')->defaultValue('ornicar_akismet.akismet_real')->end()
                ->scalarNode('adapter')->defaultValue('ornicar_akismet.adapter.guzzle')->end()
                ->scalarNode('throw_exceptions')->defaultValue('%kernel.debug%')->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
