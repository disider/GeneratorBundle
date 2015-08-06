<?php

namespace Diside\GeneratorBundle\Generator;

use Diside\GeneratorBundle\Helper\Inflect;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;


class ViewsGenerator extends BaseGenerator
{
    private $basePath;

    private $filters;

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $this->basePath = $bundle->getPath() . '/Resources/views/' . $entity;
        $indexPath = $this->basePath . '/index.html.twig';

        $this->renderFile('DisideGeneratorBundle:View:index.html.twig.twig', $indexPath, array(
            'filters' => $this->filters,
            'fields' => $this->getFieldsWithType($metadata),
            'route_prefix' => $this->getEntityRoutePrefix($entity),
            'message_prefix' => $this->getEntityRoutePrefix($entity),
            'entity' => $entity,
        ));

        $indexPath = $this->basePath . '/new.html.twig';
        $this->renderFile('DisideGeneratorBundle:View:new.html.twig.twig', $indexPath, array(
            'route_prefix' => $this->getEntityRoutePrefix($entity),
            'message_prefix' => $this->getEntityRoutePrefix($entity)
        ));

        $indexPath = $this->basePath . '/edit.html.twig';
        $this->renderFile('DisideGeneratorBundle:View:edit.html.twig.twig', $indexPath, array(
            'route_prefix' => $this->getEntityRoutePrefix($entity),
            'message_prefix' => $this->getEntityRoutePrefix($entity)
        ));

    }


}
