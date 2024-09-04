<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class DockerComposeService
{
    /**
     * 生成 docker-compose.g.yml 檔案
     * 包含
     * - apache volumes
     * - nginx volumes
     * - php volumes
     * - TODO: 產生 php version，啟動對應的 php-fpm
     * - TODO: user ports map 設定 (在 php 上)
     *
     * @return void
     */
    public function generateDockerCompose()
    {
        $docksteadConfig = Yaml::parseFile('Dockstead.yaml');

        $volumes = [];
        foreach ($docksteadConfig['folders'] ?? [] as $folders) {
            $volumes[] = $folders['map'] . ':' . $folders['to'];
        }

        if (count($volumes) > 0) {
            $dockerCompose = [
                'services' => [
                    'apache' => [
                        'volumes' => $volumes,
                    ],
                    'nginx' => [
                        'volumes' => $volumes,
                    ],
                    'php' => [
                        'volumes' => $volumes,
                    ],
                ],
            ];

            $yaml = Yaml::dump($dockerCompose, 10, 2);
        } else {
            $yaml = '';
        }

        file_put_contents('docker-compose.override.yml', $yaml);
    }
}
