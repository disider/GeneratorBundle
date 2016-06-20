<?php

namespace Diside\GeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;


class FilterFormGenerator extends BaseGenerator
{
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $force = false)
    {
        $formPath = $bundle->getPath() . '/Form/Filter/' . $entity . 'FilterForm.php';
        if (!$this->filesystem->exists($formPath) || $force) {
            $this->renderFile('DisideGeneratorBundle:Form:filterForm.php.twig', $formPath, array(
                'namespace' => $bundle->getNamespace(),
                'entity' => $entity,
                'fields' => $this->getFieldsWithType($metadata),
                'route_prefix' => $this->getEntityRoutePrefix($entity),
            ));

            return true;
        }

        return false;
    }
}
