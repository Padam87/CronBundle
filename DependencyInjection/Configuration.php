<?php

namespace Padam87\CronBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('padam87_cron');

        $rootNode
            ->children()
                ->scalarNode('mailto')
                    ->defaultValue('')
                    ->info('Cron mailto variable')
                ->end()
                ->scalarNode('log_dir')
                    ->defaultValue('')
                    ->info('Cron log directory')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
