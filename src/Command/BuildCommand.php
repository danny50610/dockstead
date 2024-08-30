<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'build', description: '')]
class BuildCommand extends Command
{
    protected function configure(): void
    {
        // TODO: 接收參數 -> docker compose
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO: 產生自動生成的檔案 ?
        $process = new Process(['docker', 'compose', 'build']);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) use ($output): void {
            $output->write($buffer);
        });

        return $process->getExitCode() == 0 ? Command::SUCCESS : Command::FAILURE;
    }
}
