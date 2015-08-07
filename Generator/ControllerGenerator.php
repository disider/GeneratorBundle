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

    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $this->renderController($bundle, $entity);
        $this->renderFilterForm($bundle, $entity, $metadata);
        return $this->renderServiceConfig($bundle, $entity);

    }

    protected function renderController(BundleInterface $bundle, $entity)
    {
        $className = $entity . 'Controller';
        $classPath = $bundle->getPath() . '/Controller/' . $className . '.php';

        if (!$this->filesystem->exists($classPath)) {
            $this->renderFile('DisideGeneratorBundle:Controller:controller.php.twig', $classPath, array(
                'security' => $this->security,
                'filters' => $this->filters,
                'namespace' => $bundle->getNamespace(),
                'entity' => $entity,
                'path' => $this->getPath($entity),
                'route_prefix' => $this->getEntityRoutePrefix($entity),
            ));
        }

        $baseClassPath = $bundle->getPath() . '/Controller/Base/Base' . $className . '.php';
        $this->renderFile('DisideGeneratorBundle:Controller:baseController.php.twig', $baseClassPath, array(
            'security' => $this->security,
            'filters' => $this->filters,
            'namespace' => $bundle->getNamespace(),
            'entity' => $entity,
            'path' => $this->getPath($entity),
            'route_prefix' => $this->getEntityRoutePrefix($entity),
        ));
    }

    protected function renderFilterForm(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        if ($this->filters) {
            $baseFormPath = $bundle->getPath() . '/Form/Base/Base' . $entity . 'FilterForm.php';
            $this->renderFile('DisideGeneratorBundle:Form:BaseFilterForm.php.twig', $baseFormPath, array(
                'namespace' => $bundle->getNamespace(),
                'entity' => $entity,
                'fields' => $this->getFieldsWithType($metadata),
                'route_prefix' => $this->getEntityRoutePrefix($entity),
            ));

            $formPath = $bundle->getPath() . '/Form/' . $entity . 'FilterForm.php';
            if (!$this->filesystem->exists($formPath)){
                $this->renderFile('DisideGeneratorBundle:Form:FilterForm.php.twig', $formPath, array(
                    'namespace' => $bundle->getNamespace(),
                    'entity' => $entity,
                ));
            }
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
