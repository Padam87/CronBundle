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
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "%10s %10s %10s %10s %10s    %s",
            str_replace('\/', '/', $this->minute),
            $this->hour,
            $this->day,
            $this->month,
            $this->dayOfWeek,
            $this->commandLine
        );
    }
}
