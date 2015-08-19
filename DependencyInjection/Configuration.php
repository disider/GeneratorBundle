<?php

namespace Diside\GeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('diside_generator');


        $rootNode
            ->children()
                ->arrayNode('default_crud')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->integerNode('page_size')->defaultValue(10)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}