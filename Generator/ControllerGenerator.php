<?php

namespace Diside\GeneratorBundle\Generator;

use Diside\GeneratorBundle\Helper\Inflect;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;


class ControllerGenerator extends BaseGenerator
{
    private $className;
    private $classPath;

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

        if (file_exists($this->classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s class as it already exists under the %s file', $this->className, $this->classPath));
        }

        $this->renderFile('DisideGeneratorBundle:Controller:controller.php.twig', $this->classPath, array(
            'namespace' => $bundle->getNamespace(),
            'entity' => $entity,
            'path' => $this->getPath($entity),
            'route_prefix' => $this->getRoutePrefix($entity),
        ));

        return $this->render('DisideGeneratorBundle:Service:repository.html.twig', array(
            'namespace' => $bundle->getNamespace(),
            'entity' => $entity,
        ));

    }

}
