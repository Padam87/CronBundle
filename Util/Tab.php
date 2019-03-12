<?php

namespace Padam87\CronBundle\Util;

use Padam87\CronBundle\Annotation\Job;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;

class Tab implements \ArrayAccess
{
    /**
     * @var Job[]
     */
    private $jobs = [];
    private $vars;

    public function __construct()
    {
        $this->vars = new VariableBag();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->jobs[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * @return Job
     */
    public function offsetGet($offset)
    {
        return $this->jobs[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Job) {
            throw new \UnexpectedValueException(
                sprintf(
                    'The crontab should only contain instances of "%s", "%s" given',
                    'Padam87\CronBundle\Annotation\Job',
                    get_class($value)
                )
            );
        }

        if (is_null($offset)) {
            $this->jobs[] = $value;
        } else {
            $this->jobs[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->jobs[$offset]);
    }

    public function __toString(): string
    {
        $output = new BufferedOutput();
        $table = new Table($output);
        $table->setStyle('compact');

        foreach ($this->jobs as $job) {
            $table->addRow(
                [
                    str_replace('\/', '/', $job->minute),
                    $job->hour,
                    $job->day,
                    $job->month,
                    $job->dayOfWeek,
                    $job->commandLine . ($job->logFile ? ' >> ' . $job->logFile : ''),
                ]
            );
        }

        $table->render();

        return (string) $this->vars . PHP_EOL . $output->fetch();
    }

    /**
     * @return Job[]
     */
    public function getJobs(): array
    {
        return $this->jobs;
    }

    public function getVars(): VariableBag
    {
        return $this->vars;
    }
}
