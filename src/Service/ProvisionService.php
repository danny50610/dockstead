<?php

namespace App\Service;

use Exception;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class ProvisionService
{
    protected $output;

    public function __construct($output)
    {
        $this->output = $output;
    }

    public function doProvision()
    {
        $this->waitDatabaseReady();
        $this->createHomesteadDatabaseUser();
        $this->createDatabase();
    }

    protected function waitDatabaseReady()
    {
        $process = Process::fromShellCommandline('docker compose ps --status=running --format=json');
        $exitCode = $process->run();
        if ($exitCode != 0) {
            throw new Exception('Can not check database is running');
        }
        $containers = json_decode($process->getOutput(), true);

        $findMysql = false;
        foreach ($containers as $container) {
            if ($container['Service'] == 'mysql-5.7') {
                $findMysql = true;
                break;
            }
        }

        if (! $findMysql) {
            throw new Exception('Database is not running');
        }

        for ($i = 0; $i < 60; $i++) {
            $process = Process::fromShellCommandline('docker compose exec mysql-5.7 mysqladmin -uroot -proot ping');
            $exitCode = $process->run();
            if ($exitCode == 0) {
                break;
            }

            $this->output->writeln('Wait mysql ready....');
            sleep(3);
        }
    }

    protected function createHomesteadDatabaseUser()
    {
        $process = Process::fromShellCommandline('docker compose exec mysql-5.7 mysql -uroot -proot');
        $process->setInput(<<<'EOF'
            CREATE USER IF NOT EXISTS 'homestead'@'%' IDENTIFIED BY 'secret';
            GRANT ALL PRIVILEGES ON *.* TO 'homestead'@'%' WITH GRANT OPTION;
        EOF);
        $process->run();
    }

    protected function createDatabase()
    {
        $docksteadConfig = Yaml::parseFile('Dockstead.yaml');

        $sqlScript = '';
        foreach ($docksteadConfig['databases'] as $database) {
            $sqlScript .= "CREATE DATABASE IF NOT EXISTS `{$database}` DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;" . PHP_EOL;
        }

        $process = Process::fromShellCommandline('docker compose exec mysql-5.7 mysql -uroot -proot');
        $process->setInput($sqlScript);
        $process->run();
    }
}
