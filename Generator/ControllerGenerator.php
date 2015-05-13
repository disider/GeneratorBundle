<?php

namespace Diside\GeneratorBundle\Generator;

use Diside\GeneratorBundle\Helper\Inflect;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;


class ControllerGenerator extends Generator
{
    private $filesystem;
    private $className;
    private $classPath;


    private $twigEngine;

    public function __construct(Filesystem $filesystem, TwigEngine $twigEngine)
    {
        $this->filesystem = $filesystem;
        $this->twigEngine = $twigEngine;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getClassPath()
    {
        return $this->classPath;
    }

    public function generate(BundleInterface $bundle, $entity)
    {
        $this->className = $entity . 'Controller';
        $this->classPath = $bundle->getPath() . '/Controller/' . $this->className . '.php';

//        if (file_exists($this->classPath)) {
//            throw new \RuntimeException(sprintf('Unable to generate the %s class as it already exists under the %s file', $this->className, $this->classPath));
//        }

        $this->renderFile('DisideGeneratorBundle:Controller:controller.php.twig', $this->classPath, array(
            'namespace' => $bundle->getNamespace(),
            'entity' => $entity,
            'path' => $this->getPath($entity),
            'route_prefix' => $this->getRoutePrefix($entity),

            'bundle' => $bundle->getName()
        ));
    }


    protected function getTwigEnvironment()
    {
        return $this->twigEngine;
    }

    private function getPath($entity)
    {
        $path = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $entity));

        return Inflect::pluralize($path);
    }

    private function getRoutePrefix($entity)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $entity));
    }

}
