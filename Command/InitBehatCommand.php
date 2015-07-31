<?php

namespace Diside\GeneratorBundle\Command;


use Diside\GeneratorBundle\Generator\BehatConfigGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitBehatCommand extends GeneratorCommand
{
    protected function configure()
    {
        $this
            ->setName('diside:behat:init')
            ->addArgument('bundle', InputArgument::REQUIRED, 'Bundle name')
            ->addArgument('serverName', InputArgument::REQUIRED, 'Server name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle = $input->getArgument('bundle');
        $serverName = $input->getArgument('serverName');

        $bundle = $this->getApplication()->getKernel()->getBundle($bundle);
        $generator = $this->getGenerator($bundle);

        $outputMessage = $generator->generate($bundle, $serverName);

        $this->writeOutput($output, $outputMessage);
    }

    protected function createGenerator()
    {
        return new BehatConfigGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('templating'));
    }

    protected function writeOutput(OutputInterface $output, $outputMessage)
    {
        $output->writeln(array(
            '',
            'Behat has been initialized.',
            '',
            'Add these lines:',
            '',
            $outputMessage
        ));
    }

}