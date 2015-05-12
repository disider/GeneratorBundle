<?php

namespace Diside\GeneratorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

//        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
    }

    public function getAlias()
    {
        return 'app';
    }
}
