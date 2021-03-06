<?php

namespace Diside\GeneratorBundle\Command;

use Diside\GeneratorBundle\Generator\ViewsGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateViewsCommand extends BaseGenerateCommand
{
    /** @var bool */
    private $noFilters;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('diside:generate:views')
            ->addArgument('entity', InputArgument::REQUIRED, 'A entity name')
            ->addOption(self::PARAMETERS_NO_FILTERS, null, InputOption::VALUE_NONE,  'Add list filters');
    }

    protected function preExecute(InputInterface $input, OutputInterface $output)
    {
        $this->noFilters = $input->getOption(self::PARAMETERS_NO_FILTERS);
    }

    protected function createGenerator()
    {
        $generator = new ViewsGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
        $generator->setFilters(!$this->noFilters);
        return $generator;
    }

    protected function writeOutput(OutputInterface $output, $outputMessage)
    {
        $output->writeln(array(
            'New view files have been created.',
            '',
            'Add these keys to your translation files:',
            '',
            $outputMessage
        ));

    }
}