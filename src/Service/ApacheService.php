<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class ApacheService
{
    public function generateVhostConf()
    {
        $filesystem = new Filesystem();
        $filesystem->remove('apache/conf/v-hosts');

        $filesystem->mkdir('apache/conf/v-hosts');

        $docksteadConfig = Yaml::parseFile('Dockstead.yaml');
        // var_dump($docksteadConfig);

        $vhostTemplate = file_get_contents('assets/httpd-vhosts.conf.example');
        foreach ($docksteadConfig['sites'] as $site) {
            if (($site['type'] ?? null) != 'apache') {
                continue;
            }

            $vhostConf = $vhostTemplate;
            $vhostConf = $this->replace($vhostConf, [
                '{{ServerName}}' => $site['map'],
                '{{DocumentRoot}}' => $site['to'],
                '{{FpmPort}}' => ($site['php'] ?? null) == '7.4' ? 9000 : 9001, // FIXME:
            ]);

            file_put_contents("apache/conf/v-hosts/{$site['map']}.conf", $vhostConf);
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
