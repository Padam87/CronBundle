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
     * @param string|null    $group
     * @param array|null     $config
     *
     * @return Tab
     */
    public function read(InputInterface $input = null, $group = null, array $config = null)
    {
        $tab = new Tab();

        foreach($this->application->all() as $command) {
            $annotations = $this->annotationReader->getClassAnnotations(new \ReflectionClass($command));

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Job) {
                    if ($group !== null && $group != $annotation->group) {
                        continue;
                    }

                    $annotation->commandLine = sprintf(
                        'php %s %s',
                        realpath($_SERVER['argv'][0]),
                        $annotation->commandLine === null ? $command->getName() : $annotation->commandLine
                    );

                    if ($input->hasOption('log-dir') && $annotation->logFile !== null) {
                        $logDir = rtrim($input->getOption('log-dir'), '\\/');
                        $annotation->logFile = $logDir . '/' . $annotation->logFile;
                    }

                    $tab[] = $annotation;
                }
            }
        }

        $vars = $tab->getVars();
        $vars['SYMFONY_ENV'] = $input->getOption('env');
        $vars['MAILTO'] = $input->getOption('mailto');

        if ($config != null && isset($config['path']) && $config['path'] != null) {
            $vars['PATH'] = $config['path'];
        }

        return $tab;
    }
}
