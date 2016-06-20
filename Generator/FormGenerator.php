<?php

namespace Diside\GeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class FormGenerator extends BaseGenerator
{
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $force = false)
    {
        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException('The form generator does not support entity classes with multiple primary keys.');
        }

        $className = $entity . 'Form';
        $classPath = $bundle->getPath() . '/Form/' . $className . '.php';

        if (!$this->filesystem->exists($classPath) || $force) {
            $this->renderFile('DisideGeneratorBundle:Form:formType.php.twig', $classPath, array(
                'fields' => $this->getFieldsWithType($metadata),
                'namespace' => $bundle->getNamespace(),
                'entity_class' => $entity,
                'form_class' => $className,
                'form_type_name' => $this->getEntityRoutePrefix($entity)
            ));

            return true;
        }

        return false;
    }

}
