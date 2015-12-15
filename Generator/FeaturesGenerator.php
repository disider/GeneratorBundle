<?php

namespace Diside\GeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class FeaturesGenerator extends BaseGenerator
{
    /** @var bool */
    private $security;

    /** @var bool */
    private $filters;

    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);

        $dirPath = $bundle->getPath() . '/Features/Context';
        $classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Context.php';

        $parts = explode('\\', $entity);
        array_pop($parts);


        if (!$this->filesystem->exists($classPath)) {
            $this->renderFile('DisideGeneratorBundle:Feature:EntityContext.php.twig', $classPath, array(
                'fields' => $this->getFieldsWithType($metadata),
                'namespace' => $bundle->getNamespace(),
                'entity_namespace' => implode('\\', $parts),
                'entity_name' => $this->getEntityName($entity),
                'entity' => $entityClass,
                'bundle' => $bundle->getName(),
                'route_prefix' => $this->getEntityRoutePrefix($entity),
            ));
        }

        $this->renderDenyActions($bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('Delete.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('List.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('Create.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('Edit.feature', $bundle, $entity, $metadata, $entityClass);

        return $this->render('DisideGeneratorBundle:Feature:config.html.twig', array(
            'entity' => $entity,
            'route_prefix' => $this->getEntityRoutePrefix($entity),
        ));

    }

    protected function getFieldsWithDefaultValues(ClassMetadataInfo $metadata)
    {
        $fields = $this->getFieldsWithType($metadata);
        $values = array();

        foreach ($fields as $field) {
            $type = $field['type'];
            $value = 'TEXT';
            if ($type == 'integer' || $type == 'float')
                $value = 1;
            else if ($type == 'boolean')
                $value = 'true';
            else if ($type == 'date')
                $value = '01/09/2015';
            else if ($type == 'datetime')
                continue;

            $values[$field['name']] = array('name' => $field['name'], 'value' => $value, 'type' => $type);
        }

        return $values;
    }

    protected function getFieldsWithAlternativeValues(ClassMetadataInfo $metadata)
    {
        $fields = $this->getFieldsWithType($metadata);
        $values = array();

        foreach ($fields as $field) {
            $type = $field['type'];
            $value = 'OTHER_TEXT';
            if ($type == 'integer' || $type == 'float')
                $value = 2;
            else if ($type == 'boolean')
                $value = 'false';
            else if ($type == 'date')
                $value = '10/10/2015';
            else if ($type == 'datetime')
                continue;

            $values[$field['name']] = array('name' => $field['name'], 'value' => $value, 'type' => $type);
        }

        return $values;
    }

    protected function renderFeature($fileName, BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $entityClass)
    {
        $indexPath = sprintf($this->security ? '%s/Features/%s/User/%s' : '%s/Features/%s/%s',
            $bundle->getPath(),
            $entityClass,
            $fileName);

        $this->renderFile('DisideGeneratorBundle:Feature:' . strtolower($fileName) . '.twig', $indexPath, array(
            'security' => $this->security,
            'fields' => $this->getFieldsWithDefaultValues($metadata),
            'other_fields' => $this->getFieldsWithAlternativeValues($metadata),
            'entity_name' => $this->getEntityName($entity),
            'entity_path' => $this->getPath($entity),
            'entity' => $entityClass,
            'filters' => $this->filters
        ));
    }

    protected function renderDenyActions(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $entityClass)
    {
        if (!$this->security)
            return;

        $indexPath = sprintf('%s/Features/%s/Anonymous/DenyActions.feature', $bundle->getPath(), $entityClass);

        $this->renderFile('DisideGeneratorBundle:Feature:denyActions.feature.twig', $indexPath, array(
            'security' => $this->security,
            'fields' => $this->getFieldsWithDefaultValues($metadata),
            'entity_name' => $this->getEntityName($entity),
            'entity_path' => $this->getPath($entity),
            'entity' => $entityClass,
        ));
    }

    public function setSecurity($security)
    {
        $this->security = $security;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }
}
