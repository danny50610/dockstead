<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'build', description: '')]
class BuildCommand extends Command
{
    protected function configure(): void
    {
        $this->addOption('argv', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = Process::fromShellCommandline('docker compose build ' . $input->getOption('argv'));
        $process->setTty(true);
        $process->run();

        return Command::SUCCESS;
    }
}
