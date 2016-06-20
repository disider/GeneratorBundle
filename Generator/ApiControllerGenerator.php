<?php

namespace Diside\GeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;


class ApiControllerGenerator extends BaseGenerator
{
    /** @var bool */
    private $security;

    public function setSecurity($value)
    {
        $this->security = $value;
    }

    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $force = false)
    {
        $this->generateEntityController($bundle, $entity, $metadata);
        $this->generateBaseController($bundle, $entity);
        $this->generateException($bundle);
        $this->generateModel($bundle);
        $this->generateListener($bundle);

        return $this->render('DisideGeneratorBundle:Controller:apiConfig.html.twig', array());

    }

    protected function generateBaseController(BundleInterface $bundle, $entity)
    {
        $baseControllerPath = $bundle->getPath() . '/Controller/Api/BaseApiController.php';

        if (!$this->filesystem->exists($baseControllerPath)){
            $this->renderFile('DisideGeneratorBundle:Controller:baseApiController.php.twig', $baseControllerPath, array(
                'namespace' => $bundle->getNamespace(),
                'route_prefix' => $this->getEntityRoutePrefix($entity),
            ));
        }
    }

    protected function generateException(BundleInterface $bundle)
    {
        $exceptionPath = $bundle->getPath() . '/Exception/ApiException.php';

        if (!$this->filesystem->exists($exceptionPath)){
            $this->renderFile('DisideGeneratorBundle:Exception:apiException.php.twig', $exceptionPath, array(
                'namespace' => $bundle->getNamespace(),
            ));
        }
    }

    protected function generateModel(BundleInterface $bundle)
    {
        $exceptionPath = $bundle->getPath() . '/Model/ApiProblem.php';

        if (!$this->filesystem->exists($exceptionPath)){
            $this->renderFile('DisideGeneratorBundle:Model:apiProblem.php.twig', $exceptionPath, array(
                'namespace' => $bundle->getNamespace(),
            ));
        }
    }

    protected function generateListener(BundleInterface $bundle)
    {
        $exceptionPath = $bundle->getPath() . '/Listener/ApiExceptionListener.php';

        if (!$this->filesystem->exists($exceptionPath)){
            $this->renderFile('DisideGeneratorBundle:Listener:apiExceptionListener.php.twig', $exceptionPath, array(
                'namespace' => $bundle->getNamespace(),
            ));
        }
    }

    protected function generateEntityController(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $className = 'Api' . $entity . 'Controller';
        $classPath = $bundle->getPath() . '/Controller/Api/' . $className . '.php';

        if (file_exists($classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s class as it already exists under the %s file', $className, $classPath));
        }

        $this->renderFile('DisideGeneratorBundle:Controller:apiEntityController.php.twig', $classPath, array(
            'security' => $this->security,
            'namespace' => $bundle->getNamespace(),
            'entity' => $entity,
            'path' => $this->getPath($entity),
            'route_prefix' => $this->getEntityRoutePrefix($entity),
            'fields' => $this->getEntityFields($metadata)
        ));
    }

    private function getEntityFields(ClassMetadataInfo $metadata)
    {
        $fields = $metadata->getFieldNames();
        return array_diff($fields, array('id'));
    }
}
