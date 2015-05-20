<?php

namespace Padam87\CronBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ConfigurationAwareCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addOption('group', 'g', InputArgument::OPTIONAL)
            ->addOption('mailto', '-m', InputArgument::OPTIONAL)
            ->addOption('log-dir', '-l', InputArgument::OPTIONAL)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getConfiguration();

        if ($this->getDefinition()->hasOption('mailto') && $input->getOption('mailto') === null) {
            $input->setOption('mailto', $config['mailto']);
        }

        if ($this->getDefinition()->hasOption('log-dir') && $input->getOption('log-dir') === null) {
            $input->setOption('log-dir', $config['log_dir']);
        }
    }

    public function getConfiguration()
    {
        if (get_class($this->getApplication()) == 'Symfony\Bundle\FrameworkBundle\Console\Application') {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var ContainerInterface $container */
            $container = $this->getApplication()->getKernel()->getContainer();

            return $container->getParameter('padam87_cron');
        } else {
            throw new \Exception('Not implemented yet. PRs are welcome, as always.');
        }
    }
}
