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
        ;
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function getConfiguration()
    {
        if (get_class($this->getApplication()) == 'Symfony\Bundle\FrameworkBundle\Console\Application') {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var ContainerInterface $container */
            $container = $this->getApplication()->getKernel()->getContainer();

            $config = $container->getParameter('padam87_cron');
        } else {
            throw new \Exception('Not implemented yet. PRs are welcome, as always.');
        }

        return $config;
    }
}
