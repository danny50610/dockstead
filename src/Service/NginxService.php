<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class NginxService
{
    public function generateSiteConf()
    {
        $filesystem = new Filesystem();
        $filesystem->remove('nginx/sites-enabled');

        $filesystem->mkdir('nginx/sites-enabled');

        $docksteadConfig = Yaml::parseFile('Dockstead.yaml');

        $siteTemplate = file_get_contents('assets/nginx-site.conf.example');
        foreach ($docksteadConfig['sites'] as $site) {
            if (($site['type'] ?? null) != 'nginx') {
                continue;
            }

            $siteConf = $siteTemplate;
            $siteConf = $this->replace($siteConf, [
                '{{ServerName}}' => $site['map'],
                '{{DocumentRoot}}' => $site['to'],
                '{{FpmPort}}' => match ($site['php'] ?? null) {
                    '7.4' => 9000,
                    '8.0' => 9001,
                    '8.1' => 9002,
                    '8.2' => 9003,
                    '8.3' => 9004,
                    default => 9004,
                },
            ]);

            file_put_contents("nginx/sites-enabled/{$site['map']}.conf", $siteConf);
        }
    }

    protected function replace($vhostConf, $replacements)
    {
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $vhostConf
        );
    }
}