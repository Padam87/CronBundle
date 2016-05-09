<?php

namespace Padam87\CronBundle\Util;

use Doctrine\Common\Annotations\AnnotationReader;
use Padam87\CronBundle\Annotation\Job;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class Helper
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var AnnotationReader
     */
    protected $annotationReader;

    /**
     * @param Application      $application
     * @param AnnotationReader $annotationReader
     */
    public function __construct(Application $application, AnnotationReader $annotationReader)
    {
        $this->application = $application;
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param InputInterface $input
     * @param array|null     $config
     *
     * @return Tab
     */
    public function createTab(InputInterface $input, array $config = null)
    {
        $tab = new Tab();

        foreach($this->application->all() as $command) {
            $annotations = $this->annotationReader->getClassAnnotations(new \ReflectionClass($command));

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Job) {
                    $group = $input->hasOption('group') ? $input->getOption('group') : null;

                    if ($group !== null && $group != $annotation->group) {
                        continue;
                    }

                    $annotation->commandLine = sprintf(
                        'php %s %s',
                        realpath($_SERVER['argv'][0]),
                        $annotation->commandLine === null ? $command->getName() : $annotation->commandLine
                    );

                    if ($config['log_dir'] !== null && $annotation->logFile !== null) {
                        $logDir = rtrim($config['log_dir'], '\\/');
                        $annotation->logFile = $logDir . DIRECTORY_SEPARATOR . $annotation->logFile;
                    }

                    $tab[] = $annotation;
                }
            }
        }

        $vars = $tab->getVars();

        if ($input->hasOption('env')) {
            $vars['SYMFONY_ENV'] = $input->getOption('env');
        }

        if ($config !== null) {
            foreach ($config['variables'] as $name => $value) {
                if ($value === null) {
                    continue;
                }

                $vars[strtoupper($name)] = $value;
            }
        }

        return $tab;
    }
}
