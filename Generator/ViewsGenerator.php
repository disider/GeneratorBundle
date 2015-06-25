<?php

namespace Diside\GeneratorBundle\Generator;

use Diside\GeneratorBundle\Helper\Inflect;
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

    public function generate(BundleInterface $bundle, $entity)
    {
        $this->basePath = $bundle->getPath() . '/Resources/views/' . $entity;
        $indexPath = $this->basePath . '/index.html.twig';

        $this->renderFile('DisideGeneratorBundle:Views:index.html.twig.twig', $indexPath, array(
            'route_prefix' => $this->getRoutePrefix($entity),
            'message_prefix' => $this->getRoutePrefix($entity),
            'entity' => $entity,
        ));
    }


}
