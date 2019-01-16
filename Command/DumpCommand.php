<?php

namespace Padam87\CronBundle\Command;

use Doctrine\Common\Annotations\AnnotationReader;
use Padam87\CronBundle\Util\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpCommand extends ConfigurationAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('cron:dump')
            ->setDescription('Dumps jobs to a crontab file')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dump($input);
    }

    protected function dump(InputInterface $input): string
    {
        $reader = new AnnotationReader();
        $helper = new Helper($this->getApplication(), $reader);

        $tab = $helper->createTab($input, $this->getConfiguration());

        $path = strtolower(
            sprintf(
                '%s.crontab',
                $this->getApplication()->getName()
            )
        );

        file_put_contents($path, (string) $tab);

        return $path;
    }
}
