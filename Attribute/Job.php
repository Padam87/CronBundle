<?php

namespace Padam87\CronBundle\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class Job
{
    public function __construct(
        public string $minute = '*',
        public string $hour = '*',
        public string $day = '*',
        public string $month = '*',
        public string $dayOfWeek = '*',
        public ?string $group = null,
        public ?string $logFile = null,
        public ?string $commandLine = null
    ) {
    }
}
