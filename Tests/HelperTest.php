<?php

namespace Padam87\CronBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Padam87\CronBundle\Annotation\Job;
use Padam87\CronBundle\Tests\Resources\Command\IrrelevantAttributeCommand;
use Padam87\CronBundle\Tests\Resources\Command\OneAttributeCommand;
use Padam87\CronBundle\Tests\Resources\Command\TwoAttributesCommand;
use Padam87\CronBundle\Util\Helper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
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
    public function should_register_single_job_annotaion()
    {
        $commands = [
            $this->createMock(Command::class),
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $annotationReader = $this->createMock(AnnotationReader::class);
        $annotationReader->expects($this->once())->method('getClassAnnotations')->willReturn([
            new Job(),
        ]);

        $helper = new Helper($application, $annotationReader);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(1, $tab->getJobs());
    }

    /**
     * @test
     */
    public function should_register_multiple_job_annotaions()
    {
        $commands = [
            $this->createMock(Command::class),
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $annotationReader = $this->createMock(AnnotationReader::class);
        $annotationReader->expects($this->once())->method('getClassAnnotations')->willReturn([
            new Job(),
            new Job(),
        ]);

        $helper = new Helper($application, $annotationReader);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(2, $tab->getJobs());
    }

    /**
     * @test
     */
    public function should_register_job_annotaions_on_multiple_commands()
    {
        $commands = [
            $this->createMock(Command::class),
            $this->createMock(Command::class),
            $this->createMock(Command::class),
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $annotationReader = $this->createMock(AnnotationReader::class);
        $annotationReader->expects($this->exactly(3))->method('getClassAnnotations')->willReturnOnConsecutiveCalls(
            [new Job(), new Job()],
            [new Job()],
            [],
        );

        $helper = new Helper($application, $annotationReader);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(3, $tab->getJobs());
    }

    /**
     * @test
     */
    public function should_ignore_irrelevant_annotations()
    {
        $commands = [
            $this->createMock(Command::class)
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $annotationReader = $this->createMock(AnnotationReader::class);
        $annotationReader->expects($this->once())->method('getClassAnnotations')->willReturn([
            new Job(),
            new \stdClass(),
        ]);

        $helper = new Helper($application, $annotationReader);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(1, $tab->getJobs());
    }

    /**
     * @test
     * @requires PHP 8.0
     */
    public function should_register_single_job_attribute()
    {
        $commands = [
            new OneAttributeCommand(),
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $annotationReader = $this->createMock(AnnotationReader::class);
        $annotationReader->expects($this->never())->method('getClassAnnotations');

        $helper = new Helper($application, $annotationReader);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(1, $tab->getJobs());
    }

    /**
     * @test
     * @requires PHP 8.0
     */
    public function should_register_multiple_job_attributes()
    {
        $commands = [
            new TwoAttributesCommand(),
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $annotationReader = $this->createMock(AnnotationReader::class);
        $annotationReader->expects($this->never())->method('getClassAnnotations');

        $helper = new Helper($application, $annotationReader);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(2, $tab->getJobs());
    }

    /**
     * @test
     * @requires PHP 8.0
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

        $annotationReader = $this->createMock(AnnotationReader::class);
        $annotationReader->expects($this->once())->method('getClassAnnotations')
            ->with(new \ReflectionClass($stdClass))->willReturn([]);

        $helper = new Helper($application, $annotationReader);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(3, $tab->getJobs());
    }

    /**
     * @test
     * @requires PHP 8.0
     */
    public function should_ignore_irrelevant_attributes()
    {
        $commands = [
            new IrrelevantAttributeCommand(),
        ];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $annotationReader = $this->createMock(AnnotationReader::class);
        $annotationReader->expects($this->never())->method('getClassAnnotations');

        $helper = new Helper($application, $annotationReader);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(1, $tab->getJobs());
    }

    /**
     * @test
     */
    public function should_process_jobs()
    {
        $command = $this->createMock(Command::class);
        $command->expects($this->once())->method('getName')->willReturn('my:job');

        $commands = [$command];

        $application = $this->createMock(Application::class);
        $application->expects($this->once())->method('all')->willReturn($commands);

        $annotationReader = $this->createMock(AnnotationReader::class);
        $annotationReader->expects($this->once())->method('getClassAnnotations')->willReturn([
            new Job('*', '*', '*', '*', '*', null, 'myjob.log'),
        ]);

        $helper = new Helper($application, $annotationReader);

        $input = $this->createMock(InputInterface::class);

        $tab = $helper->createTab($input, $this->getConfig());

        $this->assertCount(1, $tab->getJobs());

        $job = $tab->getJobs()[0];

        $this->assertEquals($this->getConfig()['log_dir'] . '/myjob.log', $job->logFile);
        $this->assertStringStartsWith($this->getConfig()['php_binary'], $job->commandLine);
        $this->assertStringEndsWith('my:job', $job->commandLine);
    }
}
