<?php

namespace Padam87\CronBundle\Tests\Resources\Command;

use Padam87\CronBundle\Attribute\Job;
use Symfony\Component\Console\Command\Command;

#[Job(group: 'my-group', logFile: 'myjob.log', commandLine: 'my:job 42 --first-option --second-option true')]
class ProcessTestCommand extends Command
{
    protected static $defaultName = 'my:job';
}
