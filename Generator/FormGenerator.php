<?php

namespace Diside\GeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class FormGenerator extends BaseGenerator
{
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);

        $className = $entityClass . 'Form';
        $dirPath = $bundle->getPath() . '/Form';
        $classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Form.php';

        if (file_exists($classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s form class as it already exists under the %s file', $className, $classPath));
        }

        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException('The form generator does not support entity classes with multiple primary keys.');
        }

        $parts = explode('\\', $entity);
        array_pop($parts);

        $this->renderFile('DisideGeneratorBundle:Form:formType.php.twig', $classPath, array(
            'fields' => $this->getFieldsFromMetadata($metadata),
            'namespace' => $bundle->getNamespace(),
            'entity_namespace' => implode('\\', $parts),
            'entity_class' => $entityClass,
            'bundle' => $bundle->getName(),
            'form_class' => $className,
            'form_type_name' => $this->getEntityRoutePrefix($entityClass)
        ));
    }

}
