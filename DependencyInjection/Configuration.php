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
