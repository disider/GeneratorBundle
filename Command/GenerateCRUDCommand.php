<?php

namespace Diside\GeneratorBundle\Command;


use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GenerateCRUDCommand extends DoctrineCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('diside:generate:crud')
            ->addArgument('name', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityName = $input->getArgument('name');

        $this->executeCommand(
            sprintf('app/console doctrine:generate:entities %s --path=src/ --no-backup', $entityName),
            $output);

        $this->executeCommand(
            sprintf('app/console doctrine:generate:form %s', $entityName),
            $output);

        $this->executeCommand(
            sprintf('app/console diside:generate:controller %s', $entityName),
            $output);

        $this->executeCommand(
            sprintf('app/console diside:generate:views %s', $entityName),
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