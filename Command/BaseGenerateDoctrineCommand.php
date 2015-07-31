<?php

namespace Diside\GeneratorBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseGenerateDoctrineCommand extends GenerateDoctrineCommand
{

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->preExecute($input, $output);

        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle) . '\\' . $entity;
        $metadata = $this->getEntityMetadata($entityClass);
        $bundle = $this->getApplication()->getKernel()->getBundle($bundle);
        $generator = $this->getGenerator($bundle);

        $outputMessage = $generator->generate($bundle, $entity, $metadata[0]);

        $this->writeOutput($output, $outputMessage);
    }

    protected function preExecute(InputInterface $input, OutputInterface $output)
    {
    }

    abstract protected function writeOutput(OutputInterface $output, $outputMessage);

}
