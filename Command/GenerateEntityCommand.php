<?php

namespace Diside\GeneratorBundle\Command;


use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateEntityCommand extends DoctrineCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('diside:generate:entity')
            ->setDescription('Generates entity classes and method stubs from your mapping information')
            ->addArgument('name', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityName = $input->getArgument('name');
        $output->writeln('Generating...');
        $this->generateEntity($output, $entityName);
        $this->generateFormType($output, $entityName);

    }

    protected function generateEntity(OutputInterface $output, $entityName)
    {
        $command = $this->getApplication()->find('doctrine:generate:entities');
        $arguments = array(
            'command' => 'doctrine:generate:entities',
            'name' => $entityName,
            '--path' => 'src/',
            '--no-backup' => true
        );

        $params = new ArrayInput($arguments);
        $command->run($params, $output);
        $output->writeln('Generated entity.');
    }

    protected function generateFormType(OutputInterface $output, $entityName)
    {
        $command = $this->getApplication()->find('doctrine:generate:form');
        $arguments = array(
            'command' => 'doctrine:generate:form',
            'entity' => $entityName
        );

        $command->run(new ArrayInput($arguments), $output);
        $output->writeln('Generated form.');
    }
}