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

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $this->basePath = $bundle->getPath() . '/Resources/views/' . $entity;
        $indexPath = $this->basePath . '/index.html.twig';

        $this->renderFile('DisideGeneratorBundle:Views:index.html.twig.twig', $indexPath, array(
            'fields' => $this->getFieldsFromMetadata($metadata),
            'route_prefix' => $this->getRoutePrefix($entity),
            'message_prefix' => $this->getRoutePrefix($entity),
            'entity' => $entity,
        ));

        $indexPath = $this->basePath . '/new.html.twig';
        $this->renderFile('DisideGeneratorBundle:Views:new.html.twig.twig', $indexPath, array(
            'route_prefix' => $this->getRoutePrefix($entity),
            'message_prefix' => $this->getRoutePrefix($entity)
        ));

        $indexPath = $this->basePath . '/edit.html.twig';
        $this->renderFile('DisideGeneratorBundle:Views:edit.html.twig.twig', $indexPath, array(
            'route_prefix' => $this->getRoutePrefix($entity),
            'message_prefix' => $this->getRoutePrefix($entity)
        ));

    }


}
