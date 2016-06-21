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
            ->addOption('no-security', null, InputOption::VALUE_NONE,  'No security')
            ->addOption('no-filters', null, InputOption::VALUE_NONE,  'No filters')
            ->addOption('force', 'f', InputOption::VALUE_NONE,
                'Cause the regeneration classes');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityName = $input->getArgument('entity');
        $noSecurity = $input->getOption('no-security');
        $noFilters = $input->getOption('no-filters');

        $force = $input->getOption('force');

        $this->executeCommand(
            sprintf('app/console doctrine:generate:entities %s --path=src/ --no-backup', $entityName),
            $output);

        $this->executeCommand(
            sprintf('app/console diside:generate:form %s' . ($force ? ' --force' : ''), $entityName),
            $output);

        if(is_null($noFilters)) {
            $this->executeCommand(
                sprintf('app/console diside:generate:form %s' . ($force ? ' --force' : '') . ' --filter', $entityName),
                $output);
        }
        
        $this->executeCommand(
            sprintf('app/console diside:generate:controller %s' . ($force ? ' --force' : '') . ($noSecurity ? ' --no-security' : '') . ($noFilters ? ' --no-filters' : ''), $entityName),
            $output);

        $this->executeCommand(
            sprintf('app/console diside:generate:views %s' . ($noFilters ? ' --no-filters' : ''), $entityName) ,
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