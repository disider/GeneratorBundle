<?php

namespace Diside\GeneratorBundle\Command;


use Diside\GeneratorBundle\Generator\ControllerGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateControllerCommand extends BaseGenerateDoctrineCommand
{
    /** @var bool */
    private $addSecurity;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('diside:generate:controller')
            ->addArgument('entity', InputArgument::REQUIRED, 'A entity name')
            ->addOption('add-security', null, InputOption::VALUE_NONE,  'Add security annotation');
    }

    protected function preExecute(InputInterface $input, OutputInterface $output)
    {
        $this->addSecurity = $input->getOption('add-security');
    }

    protected function createGenerator()
    {
        $controller = new ControllerGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
        $controller->setSecurity($this->addSecurity);

        return $controller;
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