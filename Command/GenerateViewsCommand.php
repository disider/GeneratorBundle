<?php

namespace Diside\GeneratorBundle\Command;


use Diside\GeneratorBundle\Generator\ViewsGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
            ->addArgument('entity', InputArgument::REQUIRED, 'A entity name');
    }

    protected function createGenerator()
    {
        return new ViewsGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
    }

    protected function writeOutput(OutputInterface $output, $outputMessage)
    {
        $output->writeln('The new views file has been created.');
    }
}