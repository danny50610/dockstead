<?php

use App\Service\ApacheService;
use App\Service\DockerComposeService;

require __DIR__ . '/vendor/autoload.php';

$a = new DockerComposeService();
$a->generateDockerCompose();

$b = new ApacheService();
$b->generateVhostConf();
