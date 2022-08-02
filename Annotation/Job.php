<?php

namespace Padam87\CronBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class Job
{
    public string $minute;
    public string $hour;
    public string $day ;
    public string $month;
    public string $dayOfWeek;
    public ?string $group = null;
    public ?string $logFile = null;
    public ?string $commandLine = null;

    public function __construct(
        string $minute = '*',
        string $hour = '*',
        string $day = '*',
        string $month = '*',
        string $dayOfWeek = '*',
        ?string $group = null,
        ?string $logFile = null,
        ?string $commandLine = null
    ) {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->day = $day;
        $this->month = $month;
        $this->dayOfWeek = $dayOfWeek;
        $this->group = $group;
        $this->logFile = $logFile;
        $this->commandLine = $commandLine;
    }
}
