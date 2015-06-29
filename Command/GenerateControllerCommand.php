<?php

namespace Diside\GeneratorBundle\Command;


use Diside\GeneratorBundle\Generator\ControllerGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateControllerCommand extends BaseGenerateDoctrineCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('diside:generate:controller')
            ->addArgument('entity', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name');
    }

    protected function createGenerator()
    {
        return new ControllerGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
    }

    protected function writeOutput(OutputInterface $output, $outputMessage)
    {
        $output->writeln(array(
            'The new controller file has been created.',
            '',
            'Add these lines to your services:',
            '',
            $outputMessage,
        ));
    }

}