<?php

namespace Diside\GeneratorBundle\Command;

use Diside\GeneratorBundle\Generator\ApiFeaturesGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateApiFeaturesCommand extends BaseGenerateCommand
{
    /** @var bool */
    private $security;

    protected function configure()
    {
        $this
            ->setName('diside:behat:api-features-generator')
            ->addArgument('entity', InputArgument::REQUIRED, 'A entity name')
            ->addOption('add-security', null, InputOption::VALUE_NONE,  'Add security annotation');
    }

    protected function preExecute(InputInterface $input, OutputInterface $output)
    {
        $this->security = $input->getOption('add-security');
    }

    protected function createGenerator()
    {
        $controller = new ApiFeaturesGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
        $controller->setSecurity($this->security);

        return $controller;
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
