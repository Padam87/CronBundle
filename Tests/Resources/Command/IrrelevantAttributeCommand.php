<?php

namespace Padam87\CronBundle\Tests\Resources\Command;

use Padam87\CronBundle\Annotation\Job;
use Symfony\Component\Console\Command\Command;

#[Job]
#[\stdClass]
class IrrelevantAttributeCommand extends Command
{

}