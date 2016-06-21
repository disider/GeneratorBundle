<?php

namespace Diside\GeneratorBundle\Command;

use Diside\GeneratorBundle\Generator\FeaturesGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFeaturesCommand extends BaseGenerateCommand
{
    /** @var bool */
    private $noSecurity;

    /** @var bool */
    private $noFilters;

    protected function configure()
    {
        $this
            ->setName('diside:generate:features')
            ->addArgument('entity', InputArgument::REQUIRED, 'A entity name')
            ->addOption(self::PARAMETERS_NO_SECURITY, null, InputOption::VALUE_NONE,  'No security annotation')
            ->addOption(self::PARAMETERS_NO_FILTERS, null, InputOption::VALUE_NONE,  'No filters')
            ->addOption(self::PARAMETERS_FORCE, 'f', InputOption::VALUE_NONE,
                'Cause the regeneration of the class');
    }

    protected function preExecute(InputInterface $input, OutputInterface $output)
    {
        $this->noSecurity = $input->getOption(self::PARAMETERS_NO_SECURITY);
        $this->noFilters = $input->getOption(self::PARAMETERS_NO_FILTERS);
    }

    protected function createGenerator()
    {
        $controller = new FeaturesGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));

        $controller->setSecurity(!$this->noSecurity);
        $controller->setFilters(!$this->noFilters);

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
