<?php

namespace Padam87\CronBundle\Util;

use Doctrine\Common\Annotations\AnnotationReader;
use Padam87\CronBundle\Annotation\Job;
use Symfony\Component\Console\Application;

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
     * @param string $group
     *
     * @return array|Tab
     */
    public function read($group = null)
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

                    $tab[] = $annotation;
                }
            }
        }

        return $tab;
    }
}
