<?php

namespace Padam87\CronBundle\Util;

use Doctrine\Common\Annotations\AnnotationReader;
use Padam87\CronBundle\Annotation\Job;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LazyCommand;
use Symfony\Component\Console\Input\InputInterface;

class Helper
{
    private $application;
    private $annotationReader;

    public function __construct(Application $application, AnnotationReader $annotationReader)
    {
        $this->application = $application;
        $this->annotationReader = $annotationReader;
    }

    public function createTab(InputInterface $input, ?array $config = null): Tab
    {
        $tab = new Tab();

        foreach ($this->application->all() as $command) {
            $commandInstance = $command instanceof LazyCommand
                ? $command->getCommand()
                : $command;

            $reflectionClass = new \ReflectionClass($commandInstance);

            if (PHP_MAJOR_VERSION >= 8.0) {
                $attributes = $reflectionClass->getAttributes(Job::class);

                if (count($attributes) > 0) {
                    foreach ($attributes as $attribute) {
                        $this->processJob(new Job(...$attribute->getArguments()), $input, $config, $commandInstance, $tab);
                    }

                    // Don't process annotations
                    continue;
                }
            }

            $jobs = $this->annotationReader->getClassAnnotations($reflectionClass);

            foreach ($jobs as $job) {
                if ($job instanceof Job) {
                    $this->processJob($job, $input, $config, $commandInstance, $tab);
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

    private function processJob(Job $job, InputInterface $input, array $config, Command $commandInstance, Tab $tab): void
    {
        $group = $input->hasOption('group') ? $input->getOption('group') : null;

        if ($group !== null && $group !== $job->group) {
            return;
        }

        $job->commandLine = sprintf(
            '%s %s %s',
            $config['php_binary'],
            realpath($_SERVER['argv'][0]),
            $annotation->commandLine ?? $commandInstance->getName()
        );

        if ($config['log_dir'] !== null && $job->logFile !== null) {
            $logDir = rtrim($config['log_dir'], '\\/');
            $job->logFile = $logDir.DIRECTORY_SEPARATOR.$job->logFile;
        }

        $tab[] = $job;
    }
}
