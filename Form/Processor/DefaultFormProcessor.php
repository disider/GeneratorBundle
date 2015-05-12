<?php

namespace Diside\GeneratorBundle\Form\Processor;

use Diside\GeneratorBundle\Entity\Repository\AbstractRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultFormProcessor extends AbstractFormProcessor
{
    /**
     * @var string
     */
    private $class;

    public function __construct(
        AbstractRepository $repository,
        FormFactoryInterface $formFactory,
        SecurityContextInterface $securityContext,
        $class
    ) {
        parent::__construct($repository, $formFactory, $securityContext);

        $this->class = $class;
    }

    protected function buildForm()
    {
        return new $this->class();
    }
}
