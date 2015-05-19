<?php

namespace Padam87\CronBundle\Command;

use Doctrine\Common\Annotations\AnnotationReader;
use Padam87\CronBundle\Util\Helper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cron:dump')
            ->setDescription('Dumps jobs to a crontab file')
            ->addOption('group', 'g', InputArgument::OPTIONAL)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $group = $input->getOption('group');

        $reader = new AnnotationReader();
        $helper = new Helper($this->getApplication(), $reader);

        $path = strtolower(
            sprintf(
                '%s.crontab',
                $this->getApplication()->getName()
            )
        );
        $content = $helper->read($group);
        file_put_contents($path, (string) $content);
    }
}
