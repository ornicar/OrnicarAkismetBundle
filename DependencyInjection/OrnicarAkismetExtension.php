<?php

namespace Ornicar\AkismetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Configures the DI container for OrnicarAkismetBundle
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class OrnicarAkismetExtension extends Extension
{
    /**
     * Loads and processes configuration to configure the Container.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->process($configuration->getConfigTree(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('config.xml');

        $container->getDefinition('ornicar_akismet.adapter.buzz')
            ->replaceArgument(0, $config['url'])
            ->replaceArgument(1, $config['api_key']);

        $container->getDefinition('ornicar_akismet.adapter.guzzle')
            ->replaceArgument(0, $config['url'])
            ->replaceArgument(1, $config['api_key']);

        $container->getDefinition('ornicar_akismet.akismet_real')
            ->replaceArgument(2, $config['throw_exceptions']);

        $container->setAlias('ornicar_akismet', $config['service']);
        $container->setAlias('ornicar_akismet.adapter', $config['adapter']);
    }
}
