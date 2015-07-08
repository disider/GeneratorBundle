<?php

namespace Diside\GeneratorBundle\Command;

use Diside\GeneratorBundle\Generator\FeaturesGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFeaturesCommand extends BaseGenerateDoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('diside:generate:features')
            ->addArgument('entity', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name');;
    }

    protected function createGenerator()
    {
        return new FeaturesGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
    }

    protected function writeOutput(OutputInterface $output, $outputMessage)
    {
        $output->writeln(array(
            '',
            'New feature files have been created.',
            '',
            'Add these lines:',
            '',
            $outputMessage
        ));
    }
}
