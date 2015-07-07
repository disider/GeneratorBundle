<?php

namespace Diside\GeneratorBundle\Generator;

use Diside\GeneratorBundle\Helper\Inflect;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;


class BehatConfigGenerator extends Generator
{
    protected $twigEngine;

    public function __construct(Filesystem $filesystem, TwigEngine $twigEngine)
    {
        $this->filesystem = $filesystem;
        $this->twigEngine = $twigEngine;
    }

    protected function getTwigEnvironment()
    {
        return $this->twigEngine;
    }

    protected function getPath($entity)
    {
        $path = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $entity));

        return Inflect::pluralize($path);
    }

    public function generate(BundleInterface $bundle)
    {
        $this->basePath = $bundle->getPath();

        $featureContextPath = $bundle->getPath() . '/Features/Context/FeatureContext.php';

        if (file_exists($featureContextPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s class, it already exists', $featureContextPath));
        }

        $this->renderFile('DisideGeneratorBundle:Feature:featureContext.php.twig', $featureContextPath, array(
            'namespace' => $bundle->getNamespace()
        ));

    }


}
