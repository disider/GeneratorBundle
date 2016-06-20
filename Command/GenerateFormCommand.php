<?php

namespace Diside\GeneratorBundle\Command;

use Diside\GeneratorBundle\Generator\FilterFormGenerator;
use Diside\GeneratorBundle\Generator\FormGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFormCommand extends BaseGenerateCommand
{
    private $generateFilterForm;

    protected function configure()
    {
        $this
            ->setName('diside:generate:form')
            ->addArgument('entity', InputArgument::REQUIRED, 'A entity name')
            ->addOption(self::PARAMETERS_FILTER, null, InputOption::VALUE_NONE,
                'Generation filter form')
            ->addOption(self::PARAMETERS_FORCE, 'f', InputOption::VALUE_NONE,
                    'Cause the regeneration of the class');
    }

    protected function preExecute(InputInterface $input, OutputInterface $output)
    {
        $this->generateFilterForm = true === $input->getOption(self::PARAMETERS_FILTER);
    }

    protected function createGenerator()
    {
        if ($this->generateFilterForm)
            return new FilterFormGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));

        return new FormGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
    }

    protected function writeOutput(OutputInterface $output, $outputMessage)
    {
        if ($outputMessage)
            $output->writeln('The form file has been created.');
        else
            $output->writeln('The form file already exists.');
    }
}
