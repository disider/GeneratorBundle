<?php

namespace Diside\GeneratorBundle\Command;


use Diside\GeneratorBundle\Generator\ViewsGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateViewsCommand extends BaseGenerateDoctrineCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('diside:generate:views')
            ->addArgument('entity', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name');
    }

    protected function createGenerator()
    {
        return new ViewsGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
    }

    protected function writeOutput(OutputInterface $output, $outputMessage)
    {
        $output->writeln('New view files have been created.');
    }
}