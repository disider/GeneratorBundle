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

    /** @var bool */
    private $filters;

    protected function configure()
    {
        $this
            ->setName('diside:behat:features-generator')
            ->addArgument('entity', InputArgument::REQUIRED, 'A entity name')
            ->addOption(self::PARAMETERS_ADD_SECURITY, null, InputOption::VALUE_NONE,  'Add security annotation')
            ->addOption(self::PARAMETERS_ADD_FILTERS, null, InputOption::VALUE_NONE,  'Add filters');
    }

    protected function preExecute(InputInterface $input, OutputInterface $output)
    {
        $this->security = $input->getOption(self::PARAMETERS_ADD_SECURITY);
        $this->filters = $input->getOption(self::PARAMETERS_ADD_FILTERS);
    }

    protected function createGenerator()
    {
        $controller = new FeaturesGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));

        $controller->setSecurity($this->security);
        $controller->setFilters($this->filters);

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
