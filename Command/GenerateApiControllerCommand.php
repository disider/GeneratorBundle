<?php

namespace Diside\GeneratorBundle\Command;


use Diside\GeneratorBundle\Generator\ApiControllerGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateApiControllerCommand extends BaseGenerateCommand
{

    /** @var bool */
    private $security;

    protected function configure()
    {
        $this
            ->setName('diside:generate:api-controller')
            ->addArgument('entity', InputArgument::REQUIRED, 'A entity name')
            ->addOption('add-security', null, InputOption::VALUE_NONE,  'Add security annotation');
    }

    protected function preExecute(InputInterface $input, OutputInterface $output)
    {
        $this->security = $input->getOption('add-security');
    }

    protected function createGenerator()
    {
        $controller = new ApiControllerGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
        $controller->setSecurity($this->security);

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
        ), OutputInterface::OUTPUT_RAW);
    }

}