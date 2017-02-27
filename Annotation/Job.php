<?php

namespace Padam87\CronBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Job
{
    /**
     * @var string
     */
    public $minute = '*';

    /**
     * @var string
     */
    public $hour = '*';

    /**
     * @var string
     */
    public $day = '*';

    /**
     * @var string
     */
    public $month = '*';

    /**
     * @var string
     */
    public $dayOfWeek = '*';

    /**
     * @var string
     */
    public $commandLine;

    /**
     * @var string
     */
    public $group;

    /**
     * @var string
     */
    public $logFile;
}
