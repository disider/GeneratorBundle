<?php

namespace Diside\GeneratorBundle\Generator;

use Diside\GeneratorBundle\Helper\Inflect;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;


class ControllerGenerator extends BaseGenerator
{
    /** @var bool */
    private $security;

    public function setSecurity($value)
    {
        $this->security = $value;
    }

    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $className = $entity . 'Controller';
        $classPath = $bundle->getPath() . '/Controller/' . $className . '.php';

        if (file_exists($classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s class as it already exists under the %s file', $className, $classPath));
        }

        $this->renderFile('DisideGeneratorBundle:Controller:controller.php.twig', $classPath, array(
            'security' => $this->security,
            'namespace' => $bundle->getNamespace(),
            'entity' => $entity,
            'path' => $this->getPath($entity),
            'route_prefix' => $this->getRoutePrefix($entity),
        ));

        return $this->render('DisideGeneratorBundle:Service:services.html.twig', array(
            'namespace' => $bundle->getNamespace(),
            'entity' => $entity,
        ));

    }

}
