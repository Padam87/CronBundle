<?php

namespace Padam87\CronBundle\Command;

use Doctrine\Common\Annotations\AnnotationReader;
use Padam87\CronBundle\Util\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ImportCommand extends DumpCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('cron:import')
            ->setDescription('Imports jobs to the crontab')
            ->addOption('user', 'u', InputArgument::OPTIONAL)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->dump($input);

        $user = $input->getOption('user');

        $command = ['crontab'];

        if ($user !== null) {
            $command[] = '-u ' . $user;
        }

        $command[] = $path;

        $process = new Process($command);
        $process->run();

        if ($process->getExitCode() !== 0) {
            $output->writeln($process->getExitCodeText());
            $output->writeln($process->getErrorOutput());
        }

        unlink($path);

        return $process->getExitCode();
    }
}
