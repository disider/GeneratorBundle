<?php

namespace Diside\GeneratorBundle\Command;

use Diside\GeneratorBundle\Generator\FeaturesGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFeaturesCommand extends BaseGenerateDoctrineCommand
{
    /** @var bool */
    private $security;

    protected function configure()
    {
        $this
            ->setName('diside:behat:features-generator')
            ->addArgument('entity', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name')
            ->addOption('add-security', null, InputOption::VALUE_NONE,  'Add security annotation');
    }

    protected function preExecute(InputInterface $input, OutputInterface $output)
    {
        $this->security = $input->getOption('add-security');
    }

    protected function createGenerator()
    {
        $controller = new FeaturesGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
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
