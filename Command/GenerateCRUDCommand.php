<?php

namespace Diside\GeneratorBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GenerateCRUDCommand extends DoctrineCommand
{

    protected function configure()
    {
        $this
            ->setName('diside:generate:crud')
            ->addArgument('entity', InputArgument::REQUIRED, 'A entity name')
            ->addOption('add-security', null, InputOption::VALUE_NONE,  'Add security annotation')
            ->addOption('add-filters', null, InputOption::VALUE_NONE,  'Add filters to list action');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityName = $input->getArgument('entity');
        $addSecurity = $input->getOption('add-security');
        $addFilters = $input->getOption('add-filters');

        $this->executeCommand(
            sprintf('app/console doctrine:generate:entities %s --path=src/ --no-backup', $entityName),
            $output);

        $this->executeCommand(
            sprintf('app/console diside:generate:form %s', $entityName),
            $output);

        $this->executeCommand(
            sprintf('app/console diside:generate:controller %s' . ($addSecurity ? ' --add-security' : '') . ($addFilters ? ' --add-filters' : ''), $entityName),
            $output);

        $this->executeCommand(
            sprintf('app/console diside:generate:views %s' . ($addFilters ? ' --add-filters' : ''), $entityName) ,
            $output);
    }

    protected function executeCommand($command, OutputInterface $output)
    {
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $output->writeln($process->getOutput());
    }


}