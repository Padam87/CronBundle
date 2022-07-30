<?php

namespace Padam87\CronBundle\Tests\Resources\Command;

use Padam87\CronBundle\Attribute\Job;
use Symfony\Component\Console\Command\Command;

#[Job(logFile: 'myjob.log')]
class ProcessTestCommand extends Command
{
    protected static $defaultName = 'my:job';
}
