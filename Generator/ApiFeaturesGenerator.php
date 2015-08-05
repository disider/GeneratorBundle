<?php

namespace Diside\GeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class ApiFeaturesGenerator extends BaseGenerator
{
    /** @var bool */
    private $security;

    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);

        $dirPath = $bundle->getPath() . '/Features/Context';
        $classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Context.php';

        if (!file_exists($classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s class, missing entity context', $classPath));
        }

        $this->renderFeatureContext($bundle);
        $this->renderDenyActions($bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('Delete.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('List.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('Create.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('Edit.feature', $bundle, $entity, $metadata, $entityClass);
        $this->renderFeature('Get.feature', $bundle, $entity, $metadata, $entityClass);

        return $this->render('DisideGeneratorBundle:Feature:ApiFeatureConfig.html.twig', array(
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
        $indexPath = sprintf($this->security ? '%s/Features/Api/%s/User/%s' : '%s/Features/Api/%s/%s',
            $bundle->getPath(),
            $entityClass,
            $fileName);

        $this->renderFile('DisideGeneratorBundle:Feature:api' . $fileName . '.twig', $indexPath, array(
            'security' => $this->security,
            'fields' => $this->getFieldsWithDefaultValues($metadata),
            'entity_name' => $this->getEntityName($entity),
            'entity_path' => $this->getPath($entity),
            'entity' => $entityClass,
        ));
    }

    protected function renderDenyActions(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $entityClass)
    {
        if (!$this->security)
            return;

        $indexPath = sprintf('%s/Features/Api/%s/Anonymous/DenyActions.feature', $bundle->getPath(), $entityClass);

        $this->renderFile('DisideGeneratorBundle:Feature:apiDenyActions.feature.twig', $indexPath, array(
            'security' => $this->security,
            'fields' => $this->getFieldsWithDefaultValues($metadata),
            'entity_name' => $this->getEntityName($entity),
            'entity_path' => $this->getPath($entity),
            'entity' => $entityClass,
        ));
    }

    protected function renderFeatureContext(BundleInterface $bundle)
    {
        $indexPath = sprintf('%s/Features/Context/ApiFeatureContext.php', $bundle->getPath());

        if (!$this->filesystem->exists($indexPath))
            $this->renderFile('DisideGeneratorBundle:Feature:apiFeatureContext.php.twig', $indexPath, array(
                'namespace' => $bundle->getNamespace()
            ));

    }

    public function setSecurity($security)
    {
        $this->security = $security;
    }
}
