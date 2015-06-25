<?php

namespace Diside\GeneratorBundle\Command;


use Diside\GeneratorBundle\Generator\ControllerGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateControllerCommand extends GenerateDoctrineCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('diside:generate:controller')
            ->addArgument('entity', InputArgument::REQUIRED, 'A bundle name, a namespace, or a class name');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $bundle = $this->getApplication()->getKernel()->getBundle($bundle);
        /** @var ControllerGenerator $generator */
        $generator = $this->getControllerGenerator($bundle);

        $outputMessage = $generator->generate($bundle, $entity);

        $output->writeln(array(
            'The new controller file has been created.',
            '',
            'Add this in doctrine.xml',
            '',
            $outputMessage,
            ));
    }

    protected function createGenerator()
    {
        return new ControllerGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
    }

}