<?php

/**
 * up
1. 設定 volumes, port map, php version (docker-compose.override.yml)
2. docker compose up
    - 如果沒有 images, 則會開始 build

build
1. 設定 volumes, port map, php version (docker-compose.override.yml)
2. docker compose build

provision (建立在已經啟動的狀況)
2. 產生 http v-host conf
3. 設定資料庫 user 、資料庫建立
 */

use App\Service\ApacheService;
use App\Service\DockerComposeService;
use App\Service\ProvisionService;

require __DIR__ . '/vendor/autoload.php';

// $a = new DockerComposeService();
// $a->generateDockerCompose();

$b = new ApacheService();
$b->generateVhostConf();

// $c = new ProvisionService();
// $c->doProvision();
