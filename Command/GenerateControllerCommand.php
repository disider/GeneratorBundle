<?php

namespace Diside\GeneratorBundle\Command;


use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateControllerCommand extends DoctrineCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('diside:generate:controller')
            ->setDescription('Generates entity classes and method stubs from your mapping information')
            ->addArgument('name', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $entity = Validators::validateEntityName($input->getArgument('entity'));
//        list($bundle, $entity) = $this->parseShortcutNotation($entity);
//
//        $generator = $this->getGenerator($bundle);
//
    }
}