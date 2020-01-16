<?php

namespace Padam87\CronBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
                ->scalarNode('log_dir')
                    ->defaultValue(null)
                    ->info('Cron log directory')
                ->end()
                ->arrayNode('variables')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('php_binary')
                    ->defaultValue(null)
                    ->info('Path to PHP binary')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
