<?php

namespace Diside\GeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class FeaturesGenerator extends BaseGenerator
{
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);

        $dirPath = $bundle->getPath() . '/Features/Context';
        $classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Context.php';

        $parts = explode('\\', $entity);
        array_pop($parts);

        $this->renderFile('DisideGeneratorBundle:Feature:EntityContext.php.twig', $classPath, array(
            'fields' => $this->getFieldsWithType($metadata),
            'namespace' => $bundle->getNamespace(),
            'entity_namespace' => implode('\\', $parts),
            'entity' => $entityClass,
            'bundle' => $bundle->getName(),
            'route_prefix' => $this->getEntityRoutePrefix($entity),
        ));

        $this->renderFeature('Delete.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('List.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('Create.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('Edit.feature', $bundle, $entity, $metadata, $entityClass);

        return $this->render('DisideGeneratorBundle:Feature:config.html.twig', array(
            'entity' => $entity,
            'route_prefix' => $this->getEntityRoutePrefix($entity),
        ));

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


    protected function getFieldsWithDefaultValues(ClassMetadataInfo $metadata)
    {
        $fields = $this->getFieldsWithType($metadata);
        $values = array();

        foreach ($fields as $field) {
            $value = 'TEXT';
            if ($field['type'] == 'integer')
                $value = 1;
            else if ($field['type'] == 'boolean')
                $value = true;

            $values[$field['name']] = array('name' => $field['name'], 'value' => $value);
        }

        return $values;
    }

    protected function renderFeature($fileName, BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $entityClass)
    {
        $indexPath = $bundle->getPath() . '/Features/' . $entityClass . '/' . $fileName;
        $this->renderFile('DisideGeneratorBundle:Feature:' . strtolower($fileName) . '.twig', $indexPath, array(
            'fields' => $this->getFieldsWithDefaultValues($metadata),
            'entity_name' => $this->getEntityName($entity),
            'entity_path' => $this->getPath($entity),
            'entity' => $entityClass,
        ));
        return $indexPath;
    }

}
