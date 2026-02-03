<?php

namespace App\DataFixtures;

use App\Entity\SavedServerConfig;
use App\Entity\ServerTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $templates = [];

        // Game servers
        $templates['minecraft'] = $this->createTemplate('minecraft', 'Minecraft', 'game', [
            'fields' => [
                ['name' => 'serverVersion', 'type' => 'choice', 'options' => ['choices' => ['1.20.4' => '1.20.4', '1.19.4' => '1.19.4', '1.18.2' => '1.18.2'], 'placeholder' => 'Select version', 'required' => true]],
                ['name' => 'maxPlayers', 'type' => 'integer', 'options' => ['label' => 'Max Players', 'attr' => ['min' => 2, 'max' => 100], 'data' => 20, 'required' => true]],
                ['name' => 'gameMode', 'type' => 'choice', 'options' => ['choices' => ['Survival' => 'survival', 'Creative' => 'creative', 'Adventure' => 'adventure'], 'placeholder' => 'Select game mode', 'required' => true]],
                ['name' => 'enableMods', 'type' => 'checkbox', 'options' => ['label' => 'Enable mod support (Forge/Fabric)', 'required' => false]],
            ],
        ]);

        $templates['csgo'] = $this->createTemplate('csgo', 'CS2 / CS:GO', 'game', [
            'fields' => [
                ['name' => 'maxPlayers', 'type' => 'integer', 'options' => ['label' => 'Max Players', 'attr' => ['min' => 2, 'max' => 64], 'data' => 10, 'required' => true]],
                ['name' => 'serverType', 'type' => 'choice', 'options' => ['choices' => ['Competitive' => 'competitive', 'Casual' => 'casual', 'Deathmatch' => 'deathmatch'], 'placeholder' => 'Select server type', 'required' => true]],
                ['name' => 'enableVAC', 'type' => 'checkbox', 'options' => ['label' => 'Enable VAC (Valve Anti-Cheat)', 'data' => true, 'required' => false]],
            ],
        ]);

        $templates['valheim'] = $this->createTemplate('valheim', 'Valheim', 'game', [
            'fields' => [
                ['name' => 'worldName', 'type' => 'text', 'options' => ['label' => 'World Name', 'attr' => ['placeholder' => 'e.g. MyVikingWorld'], 'required' => true]],
                ['name' => 'maxPlayers', 'type' => 'integer', 'options' => ['label' => 'Max Players', 'attr' => ['min' => 2, 'max' => 10], 'data' => 10, 'required' => true]],
                ['name' => 'enableCrossplay', 'type' => 'checkbox', 'options' => ['label' => 'Enable crossplay', 'required' => false]],
            ],
        ]);

        // Web servers
        $templates['apache'] = $this->createTemplate('apache', 'Apache HTTP Server', 'web', [
            'fields' => [
                ['name' => 'phpVersion', 'type' => 'choice', 'options' => ['choices' => ['PHP 8.3' => '8.3', 'PHP 8.2' => '8.2', 'PHP 8.1' => '8.1', 'None' => 'none'], 'placeholder' => 'Select PHP version', 'required' => true]],
                ['name' => 'enableSSL', 'type' => 'checkbox', 'options' => ['label' => 'Enable SSL/TLS', 'data' => true, 'required' => false]],
                ['name' => 'enableModRewrite', 'type' => 'checkbox', 'options' => ['label' => 'Enable mod_rewrite', 'data' => true, 'required' => false]],
                ['name' => 'documentRoot', 'type' => 'text', 'options' => ['label' => 'Document Root', 'attr' => ['placeholder' => '/var/www/html'], 'data' => '/var/www/html', 'required' => true]],
            ],
        ]);

        $templates['nginx'] = $this->createTemplate('nginx', 'Nginx', 'web', [
            'fields' => [
                ['name' => 'workerProcesses', 'type' => 'choice', 'options' => ['choices' => ['Auto' => 'auto', '1' => '1', '2' => '2', '4' => '4', '8' => '8'], 'placeholder' => 'Worker processes', 'required' => true]],
                ['name' => 'enableSSL', 'type' => 'checkbox', 'options' => ['label' => 'Enable SSL/TLS', 'data' => true, 'required' => false]],
                ['name' => 'enableGzip', 'type' => 'checkbox', 'options' => ['label' => 'Enable Gzip compression', 'data' => true, 'required' => false]],
                ['name' => 'serverName', 'type' => 'text', 'options' => ['label' => 'Server Name', 'attr' => ['placeholder' => 'example.com'], 'required' => true]],
            ],
        ]);

        $templates['nodejs'] = $this->createTemplate('nodejs', 'Node.js', 'web', [
            'fields' => [
                ['name' => 'nodeVersion', 'type' => 'choice', 'options' => ['choices' => ['Node 20 LTS' => '20', 'Node 18 LTS' => '18', 'Node 21' => '21'], 'placeholder' => 'Select Node.js version', 'required' => true]],
                ['name' => 'packageManager', 'type' => 'choice', 'options' => ['choices' => ['npm' => 'npm', 'yarn' => 'yarn', 'pnpm' => 'pnpm'], 'placeholder' => 'Select package manager', 'required' => true]],
                ['name' => 'port', 'type' => 'integer', 'options' => ['label' => 'Port', 'attr' => ['min' => 1024, 'max' => 65535], 'data' => 3000, 'required' => true]],
                ['name' => 'enablePM2', 'type' => 'checkbox', 'options' => ['label' => 'Enable PM2 process manager', 'required' => false]],
            ],
        ]);

        // Database servers
        $templates['mysql'] = $this->createTemplate('mysql', 'MySQL', 'database', [
            'fields' => [
                ['name' => 'mysqlVersion', 'type' => 'choice', 'options' => ['choices' => ['MySQL 8.0' => '8.0', 'MySQL 5.7' => '5.7', 'MariaDB 10.11' => 'mariadb-10.11'], 'placeholder' => 'Select version', 'required' => true]],
                ['name' => 'maxConnections', 'type' => 'integer', 'options' => ['label' => 'Max Connections', 'attr' => ['min' => 10, 'max' => 1000], 'data' => 150, 'required' => true]],
                ['name' => 'enableRemoteAccess', 'type' => 'checkbox', 'options' => ['label' => 'Enable remote access', 'required' => false]],
                ['name' => 'rootPassword', 'type' => 'text', 'options' => ['label' => 'Root Password', 'attr' => ['placeholder' => 'Enter secure password'], 'required' => true]],
            ],
        ]);

        $templates['postgresql'] = $this->createTemplate('postgresql', 'PostgreSQL', 'database', [
            'fields' => [
                ['name' => 'pgVersion', 'type' => 'choice', 'options' => ['choices' => ['PostgreSQL 16' => '16', 'PostgreSQL 15' => '15', 'PostgreSQL 14' => '14'], 'placeholder' => 'Select version', 'required' => true]],
                ['name' => 'maxConnections', 'type' => 'integer', 'options' => ['label' => 'Max Connections', 'attr' => ['min' => 10, 'max' => 500], 'data' => 100, 'required' => true]],
                ['name' => 'enableExtensions', 'type' => 'checkbox', 'options' => ['label' => 'Enable common extensions (uuid-ossp, pg_trgm)', 'data' => true, 'required' => false]],
            ],
        ]);

        $templates['mongodb'] = $this->createTemplate('mongodb', 'MongoDB', 'database', [
            'fields' => [
                ['name' => 'mongoVersion', 'type' => 'choice', 'options' => ['choices' => ['MongoDB 7.0' => '7.0', 'MongoDB 6.0' => '6.0', 'MongoDB 5.0' => '5.0'], 'placeholder' => 'Select version', 'required' => true]],
                ['name' => 'enableAuth', 'type' => 'checkbox', 'options' => ['label' => 'Enable authentication', 'data' => true, 'required' => false]],
                ['name' => 'replicaSet', 'type' => 'checkbox', 'options' => ['label' => 'Configure as replica set', 'required' => false]],
                ['name' => 'storageEngine', 'type' => 'choice', 'options' => ['choices' => ['WiredTiger' => 'wiredTiger', 'In-Memory' => 'inMemory'], 'placeholder' => 'Select storage engine', 'required' => true]],
            ],
        ]);

        foreach ($templates as $template) {
            $manager->persist($template);
        }

        // Create some saved configs
        $savedConfigs = [
            $this->createSavedConfig('My Minecraft Server', $templates['minecraft'], [
                'serverVersion' => '1.20.4',
                'maxPlayers' => 20,
                'gameMode' => 'survival',
                'enableMods' => false,
            ]),
            $this->createSavedConfig('Production Web Server', $templates['nginx'], [
                'workerProcesses' => 'auto',
                'enableSSL' => true,
                'enableGzip' => true,
                'serverName' => 'myapp.com',
            ]),
            $this->createSavedConfig('Dev Database', $templates['postgresql'], [
                'pgVersion' => '16',
                'maxConnections' => 50,
                'enableExtensions' => true,
            ]),
        ];

        foreach ($savedConfigs as $config) {
            $manager->persist($config);
        }

        $manager->flush();
    }

    private function createTemplate(string $id, string $name, string $category, array $config): ServerTemplate
    {
        $template = new ServerTemplate($id, $name, $category);
        $template->setConfig($config);
        return $template;
    }

    private function createSavedConfig(string $name, ServerTemplate $template, array $config): SavedServerConfig
    {
        $savedConfig = new SavedServerConfig();
        $savedConfig->setName($name);
        $savedConfig->setTemplate($template);
        $savedConfig->setConfig($config);
        return $savedConfig;
    }
}
