<?php

namespace Diside\GeneratorBundle\Command;

use Diside\GeneratorBundle\Generator\FormGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFormCommand extends BaseGenerateDoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('diside:generate:form')
            ->addArgument('entity', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name');;
    }

    protected function createGenerator()
    {
        return new FormGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
    }

    protected function writeOutput(OutputInterface $output, $outputMessage)
    {
        $output->writeln('The new form file has been created.');
    }
}
