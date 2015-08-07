<?php

namespace Diside\GeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class FormGenerator extends BaseGenerator
{
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException('The form generator does not support entity classes with multiple primary keys.');
        }

        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);

        $className = $entityClass . 'Form';
        $dirPath = $bundle->getPath() . '/Form';
        $classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Form.php';
        $baseClassPath = $dirPath . '/Base/Base' . str_replace('\\', '/', $entity) . 'Form.php';

        $parts = explode('\\', $entity);
        array_pop($parts);

        $entityNamespace = implode('\\', $parts);
        $namespace = $bundle->getNamespace();

        $this->renderFile('DisideGeneratorBundle:Form:BaseFormType.php.twig', $baseClassPath, array(
            'fields' => $this->getFieldsWithType($metadata),
            'namespace' => $namespace,
            'entity_namespace' => $entityNamespace,
            'entity_class' => $entityClass,
            'form_class' => $className,
            'form_type_name' => $this->getEntityRoutePrefix($entityClass)
        ));

        if (!$this->filesystem->exists($classPath)){
            $this->renderFile('DisideGeneratorBundle:Form:FormType.php.twig', $classPath, array(
                'namespace' => $namespace,
                'entity_namespace' => $entityNamespace,
                'form_class' => $className,
            ));
        }
    }

}
