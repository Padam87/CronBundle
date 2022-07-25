<?php

namespace Padam87\CronBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Job
{
    public string $minute = '*';
    public string $hour = '*';
    public string $day = '*';
    public string $month = '*';
    public string $dayOfWeek = '*';

    public ?string $group = null;

    public ?string $logFile = null;

    public ?string $commandLine = null;

    public function __construct(
        string|array $minute = '*',
        string $hour = '*',
        string $day = '*',
        string $month = '*',
        string $dayOfWeek = '*',
        string $group = '',
        string $logFile = '',
        ?string $commandLine = null
    ) {
        if (is_array($minute)) {
            // Invocation through annotations with an array parameter only
            $this->processAttribute($minute);
        } else {
            $this->commandLine = $commandLine;
            $this->minute = $minute;
            $this->hour = $hour;
            $this->day = $day;
            $this->month = $month;
            $this->dayOfWeek = $dayOfWeek;
            $this->group = $group;
            $this->logFile = $logFile;
        }
    }

    public function processAttribute(array $attributes): void
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }
    }
}
