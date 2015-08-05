<?php

namespace Diside\GeneratorBundle\Generator;

use Diside\GeneratorBundle\Helper\Inflect;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
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


    abstract public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata);


    protected function getTwigEnvironment()
    {
        return $this->twigEngine;
    }

    protected function getPath($entity)
    {
        $path = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $entity));

        return Inflect::pluralize($path);
    }

    protected function getEntityRoutePrefix($entity)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $entity));
    }

    protected function getEntityName($entity)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $entity));
    }

    protected function getFieldsFromMetadata(ClassMetadataInfo $metadata)
    {
        $fields = (array) $metadata->fieldNames;

        // Remove the primary key field if it's not managed manually
        if (!$metadata->isIdentifierNatural()) {
            $fields = array_diff($fields, $metadata->identifier);
        }

        foreach ($metadata->associationMappings as $fieldName => $relation) {
            if ($relation['type'] !== ClassMetadataInfo::ONE_TO_MANY) {
                $fields[] = $fieldName;
            }
        }

        return $fields;
    }

    protected function getFieldsWithType(ClassMetadataInfo $metadata)
    {
        $fields = array();

        foreach ($metadata->fieldMappings as $fieldName => $values) {
            $fields[$fieldName] = array('name' => $fieldName, 'type' => $values['type']);
        }

        if (!$metadata->isIdentifierNatural()) {
            foreach ($metadata->identifier as $identifier)
                unset($fields[$identifier]);
        }

        return $fields;
    }


}
