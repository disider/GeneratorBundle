<?php

namespace Diside\GeneratorBundle\Command;


use Diside\GeneratorBundle\Generator\ViewsGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateViewsCommand extends GenerateDoctrineCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('diside:generate:views')
            ->addArgument('entity', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $bundle  = $this->getApplication()->getKernel()->getBundle($bundle);
        /** @var ViewsGenerator $generator */
        $generator = $this->getGenerator($bundle);

        $generator->generate($bundle, $entity);

        $output->writeln('The new views file has been created.');
    }

    protected function createGenerator()
    {
        return new ViewsGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
    }

}