<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InstallCommand extends Command
{
    protected static $defaultName = 'install';
    protected static $defaultDescription = 'Istall empty project';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $translator = $this->container->get('translator');
        $translator->setLocale("pl");

        $schemaCmd = $this->getApplication()->find('doctrine:schema:update');
        $arguments = [
            '--force'  => true,
        ];
        $forceInput = new ArrayInput($arguments);
        
        $returnCode = $schemaCmd->run($forceInput, $output);

        $fixtureCommand = $this->getApplication()->find('doctrine:fixtures:load');
        $returnCode = $fixtureCommand->run($input, $output);
        $output->writeln("=========");
        $output->writeln(
            $translator->trans("Your default admin login is")
                . ": <info>"
                . $_SERVER['ADMIN_EMAIL']
                . '</info>'
        );

        $output->writeln(
            $translator->trans("and password is")
                . ": <info>"
                . $_SERVER['ADMIN_PASSWORD']
                . "</info>"
        );
        $output->writeln("=========");
        return $returnCode;
    }
}
