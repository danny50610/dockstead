<?php

namespace App\Command;


use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'provision', description: '')]
class ProvisionCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO:

        return Command::SUCCESS;
    }
}
