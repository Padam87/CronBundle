<?php

namespace Padam87\CronBundle\Command;

use Doctrine\Common\Annotations\AnnotationReader;
use Padam87\CronBundle\Util\Helper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ImportCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cron:import')
            ->setDescription('Imports jobs to the crontab')
            ->addOption('user', 'u', InputArgument::OPTIONAL)
            ->addOption('group', 'g', InputArgument::OPTIONAL)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $input->getOption('user');
        $group = $input->getOption('group');

        $reader = new AnnotationReader();
        $helper = new Helper($this->getApplication(), $reader);

        $path = strtolower(
            sprintf(
                '%s/%s-%s.crontab',
                sys_get_temp_dir(),
                $this->getApplication()->getName(),
                time()
            )
        );
        $content = $helper->read($group);
        file_put_contents($path, (string) $content);

        $command = sprintf(
            'crontab%s %s',
            $user !== null ? ' -u ' . $user : '',
            $path
        );

        $process = new Process($command);
        $process->run();

        return $process->getExitCode();
    }
}
