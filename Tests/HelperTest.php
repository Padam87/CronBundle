<?php

namespace Padam87\CronBundle\Tests;

use Padam87\CronBundle\Tests\Resources\Command\IrrelevantAttributeCommand;
use Padam87\CronBundle\Tests\Resources\Command\OneAttributeCommand;
use Padam87\CronBundle\Tests\Resources\Command\ProcessTestCommand;
use Padam87\CronBundle\Tests\Resources\Command\TwoAttributesCommand;
use Padam87\CronBundle\Util\Helper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class HelperTest extends TestCase
{
    private function getConfig(): array
    {
        return [
            'log_dir' => '/var/log/cron',
            'variables' => [],
            'php_binary' => '/usr/bin/php',
        ];
    }

    /**
     * @test
     */
    public function should_register_single_job_attribute()
    {
        $commands = [
            new OneAttributeCommand(),
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $helper = new Helper($application);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(1, $tab->getJobs());
    }

    /**
     * @test
     */
    public function should_register_multiple_job_attributes()
    {
        $commands = [
            new TwoAttributesCommand(),
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $helper = new Helper($application);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(2, $tab->getJobs());
    }

    /**
     * @test
     */
    public function should_register_job_attributes_on_multiple_commands()
    {
        $stdClass = new \stdClass();
        $commands = [
            new TwoAttributesCommand(),
            new OneAttributeCommand(),
            $stdClass,
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $helper = new Helper($application);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(3, $tab->getJobs());
    }

    /**
     * @test
     */
    public function should_ignore_irrelevant_attributes()
    {
        $commands = [
            new IrrelevantAttributeCommand(),
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $helper = new Helper($application);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(1, $tab->getJobs());
    }

    /**
     * @test
     */
    public function should_process_jobs()
    {
        $commands = [new ProcessTestCommand()];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $helper = new Helper($application);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(1, $tab->getJobs());

        $job = $tab->getJobs()[0];

        // logFile parameter
        $this->assertEquals($this->getConfig()['log_dir'] . '/myjob.log', $job->logFile);

        // commandLine parameter
        $this->assertStringStartsWith($this->getConfig()['php_binary'], $job->commandLine);
        $this->assertStringEndsWith('my:job 42 --first-option --second-option true', $job->commandLine);
    }
}
