<?php

namespace App\Command;

use App\Service\ApacheService;
use App\Service\DockerComposeService;
use App\Service\ProvisionService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'up', description: 'Start Application')]
class UpCommand extends Command
{
    protected function configure(): void
    {
        // TODO: 提供選項不使用 -d
        $this->addOption('argv', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dockerComposeService = new DockerComposeService();
        $dockerComposeService->generateDockerCompose();

        $apacheService = new ApacheService();
        $apacheService->generateVhostConf();

        $process = Process::fromShellCommandline('docker compose up -d' . $input->getOption('argv'));
        $process->setTty(true);
        $exitCode = $process->run();
        if ($exitCode != 0) {
            return Command::FAILURE;
        }

        // TODO: 第一次建立 $container 才需要跑 Provision

        $provision = new ProvisionService($output);
        $exitCode = $provision->doProvision();
        if ($exitCode != 0) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
