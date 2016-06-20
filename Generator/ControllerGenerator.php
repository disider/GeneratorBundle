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

    /** @var bool */
    private $filters;

    public function setSecurity($value)
    {
        $this->security = $value;
    }

    public function setFilters($value)
    {
        $this->filters = $value;
    }

    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $force = false)
    {
        $this->renderController($bundle, $entity, $force);
        return $this->renderServiceConfig($bundle, $entity);

    }

    protected function renderController(BundleInterface $bundle, $entity, $force)
    {
        $className = $entity . 'Controller';
        $classPath = $bundle->getPath() . '/Controller/' . $className . '.php';

        if (!$this->filesystem->exists($classPath) || $force) {
            $this->renderFile('DisideGeneratorBundle:Controller:controller.php.twig', $classPath, array(
                'security' => $this->security,
                'filters' => $this->filters,
                'namespace' => $bundle->getNamespace(),
                'entity' => $entity,
                'path' => $this->getPath($entity),
                'route_prefix' => $this->getEntityRoutePrefix($entity),
            ));
        }
    }
    
    protected function renderServiceConfig(BundleInterface $bundle, $entity)
    {
        return $this->render('DisideGeneratorBundle:Service:services.html.twig', array(
            'filters' => $this->filters,
            'namespace' => $bundle->getNamespace(),
            'entity' => $entity,
            'route_prefix' => $this->getEntityRoutePrefix($entity),
        ));
    }

}
