<?php

namespace Diside\GeneratorBundle\Generator;

use Diside\GeneratorBundle\Helper\Inflect;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;


abstract class BaseGenerator extends Generator
{
    protected $twigEngine;

    public function __construct(Filesystem $filesystem, TwigEngine $twigEngine)
    {
        $this->filesystem = $filesystem;
        $this->twigEngine = $twigEngine;
    }


    abstract public function generate(BundleInterface $bundle, $entity);


    protected function getTwigEnvironment()
    {
        return $this->twigEngine;
    }

    protected function getPath($entity)
    {
        $path = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $entity));

        return Inflect::pluralize($path);
    }

    protected function getRoutePrefix($entity)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $entity));
    }

}
