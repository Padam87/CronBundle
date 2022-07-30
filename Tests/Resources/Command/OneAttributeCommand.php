<?php

namespace Padam87\CronBundle\Tests\Resources\Command;

use Padam87\CronBundle\Attribute\Job;
use Symfony\Component\Console\Command\Command;

#[Job]
class OneAttributeCommand extends Command
{

}
