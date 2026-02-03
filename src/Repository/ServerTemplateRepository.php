<?php

namespace App\Repository;

use App\Model\ExtraField;
use App\Model\ServerCategory;
use App\Model\ServerTemplate;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Repository for server template data.
 */
class ServerTemplateRepository
{
    /** @var array<string, ServerCategory> */
    private array $categories;

    /** @var array<string, ServerTemplate> */
    private array $templates;

    public function __construct()
    {
        $this->initializeMockData();
    }

    public function findCategory(string $id): ?ServerCategory
    {
        return $this->categories[$id] ?? null;
    }

    public function findTemplate(string $id): ?ServerTemplate
    {
        return $this->templates[$id] ?? null;
    }

    /** @return array<string, string> [name => id] */
    public function getCategoryChoices(): array
    {
        $choices = [];
        foreach ($this->categories as $category) {
            $choices[$category->name] = $category->id;
        }
        return $choices;
    }

    /** @return string[] All unique extra field names across all templates */
    public function getAllExtraFieldNames(): array
    {
        $names = [];
        foreach ($this->templates as $template) {
            foreach ($template->getExtraFields() as $field) {
                $names[$field->fieldName] = true;
            }
        }
        return array_keys($names);
    }

    private function initializeMockData(): void
    {
        $this->templates = [
            // Game Servers
            'minecraft' => $this->createMinecraftServer(),
            'csgo' => $this->createCSGOServer(),
            'valheim' => $this->createValheimServer(),
            // Web Servers
            'apache' => $this->createApacheServer(),
            'nginx' => $this->createNginxServer(),
            'nodejs' => $this->createNodeJSServer(),
            // Database Servers
            'mysql' => $this->createMySQLServer(),
            'postgresql' => $this->createPostgreSQLServer(),
            'mongodb' => $this->createMongoDBServer(),
        ];

        $this->categories = [
            'game' => (new ServerCategory('game', 'Game Servers'))
                ->addTemplate($this->templates['minecraft'])
                ->addTemplate($this->templates['csgo'])
                ->addTemplate($this->templates['valheim']),
            'web' => (new ServerCategory('web', 'Web Servers'))
                ->addTemplate($this->templates['apache'])
                ->addTemplate($this->templates['nginx'])
                ->addTemplate($this->templates['nodejs']),
            'database' => (new ServerCategory('database', 'Database Servers'))
                ->addTemplate($this->templates['mysql'])
                ->addTemplate($this->templates['postgresql'])
                ->addTemplate($this->templates['mongodb']),
        ];
    }

    private function createMinecraftServer(): ServerTemplate
    {
        return (new ServerTemplate('minecraft', 'Minecraft', 'Java Edition game server'))
            ->addExtraField(new ExtraField('serverVersion', ChoiceType::class, [
                'choices' => ['1.20.4' => '1.20.4', '1.19.4' => '1.19.4', '1.18.2' => '1.18.2'],
                'placeholder' => 'Select version',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('maxPlayers', IntegerType::class, [
                'label' => 'Max Players',
                'attr' => ['min' => 2, 'max' => 100],
                'data' => 20,
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('gameMode', ChoiceType::class, [
                'choices' => ['Survival' => 'survival', 'Creative' => 'creative', 'Adventure' => 'adventure'],
                'placeholder' => 'Select game mode',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('enableMods', CheckboxType::class, [
                'label' => 'Enable mod support (Forge/Fabric)',
                'required' => false,
            ]));
    }

    private function createCSGOServer(): ServerTemplate
    {
        return (new ServerTemplate('csgo', 'CS2 / CS:GO', 'Counter-Strike dedicated server'))
            ->addExtraField(new ExtraField('maxPlayers', IntegerType::class, [
                'label' => 'Max Players',
                'attr' => ['min' => 2, 'max' => 64],
                'data' => 10,
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('serverType', ChoiceType::class, [
                'choices' => ['Competitive' => 'competitive', 'Casual' => 'casual', 'Deathmatch' => 'deathmatch'],
                'placeholder' => 'Select server type',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('enableVAC', CheckboxType::class, [
                'label' => 'Enable VAC (Valve Anti-Cheat)',
                'data' => true,
                'required' => false,
            ]));
    }

    private function createValheimServer(): ServerTemplate
    {
        return (new ServerTemplate('valheim', 'Valheim', 'Viking survival game server'))
            ->addExtraField(new ExtraField('worldName', TextType::class, [
                'label' => 'World Name',
                'attr' => ['placeholder' => 'e.g. MyVikingWorld'],
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('maxPlayers', IntegerType::class, [
                'label' => 'Max Players',
                'attr' => ['min' => 2, 'max' => 10],
                'data' => 10,
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('enableCrossplay', CheckboxType::class, [
                'label' => 'Enable crossplay',
                'required' => false,
            ]));
    }

    private function createApacheServer(): ServerTemplate
    {
        return (new ServerTemplate('apache', 'Apache HTTP Server', 'Classic web server'))
            ->addExtraField(new ExtraField('phpVersion', ChoiceType::class, [
                'choices' => ['PHP 8.3' => '8.3', 'PHP 8.2' => '8.2', 'PHP 8.1' => '8.1', 'None' => 'none'],
                'placeholder' => 'Select PHP version',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('enableSSL', CheckboxType::class, [
                'label' => 'Enable SSL/TLS',
                'data' => true,
                'required' => false,
            ]))
            ->addExtraField(new ExtraField('enableModRewrite', CheckboxType::class, [
                'label' => 'Enable mod_rewrite',
                'data' => true,
                'required' => false,
            ]))
            ->addExtraField(new ExtraField('documentRoot', TextType::class, [
                'label' => 'Document Root',
                'attr' => ['placeholder' => '/var/www/html'],
                'data' => '/var/www/html',
                'required' => true,
            ]));
    }

    private function createNginxServer(): ServerTemplate
    {
        return (new ServerTemplate('nginx', 'Nginx', 'High-performance web server'))
            ->addExtraField(new ExtraField('workerProcesses', ChoiceType::class, [
                'choices' => ['Auto' => 'auto', '1' => '1', '2' => '2', '4' => '4', '8' => '8'],
                'placeholder' => 'Worker processes',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('enableSSL', CheckboxType::class, [
                'label' => 'Enable SSL/TLS',
                'data' => true,
                'required' => false,
            ]))
            ->addExtraField(new ExtraField('enableGzip', CheckboxType::class, [
                'label' => 'Enable Gzip compression',
                'data' => true,
                'required' => false,
            ]))
            ->addExtraField(new ExtraField('serverName', TextType::class, [
                'label' => 'Server Name',
                'attr' => ['placeholder' => 'example.com'],
                'required' => true,
            ]));
    }

    private function createNodeJSServer(): ServerTemplate
    {
        return (new ServerTemplate('nodejs', 'Node.js', 'JavaScript runtime server'))
            ->addExtraField(new ExtraField('nodeVersion', ChoiceType::class, [
                'choices' => ['Node 20 LTS' => '20', 'Node 18 LTS' => '18', 'Node 21' => '21'],
                'placeholder' => 'Select Node.js version',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('packageManager', ChoiceType::class, [
                'choices' => ['npm' => 'npm', 'yarn' => 'yarn', 'pnpm' => 'pnpm'],
                'placeholder' => 'Select package manager',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('port', IntegerType::class, [
                'label' => 'Port',
                'attr' => ['min' => 1024, 'max' => 65535],
                'data' => 3000,
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('enablePM2', CheckboxType::class, [
                'label' => 'Enable PM2 process manager',
                'required' => false,
            ]));
    }

    private function createMySQLServer(): ServerTemplate
    {
        return (new ServerTemplate('mysql', 'MySQL', 'Popular relational database'))
            ->addExtraField(new ExtraField('mysqlVersion', ChoiceType::class, [
                'choices' => ['MySQL 8.0' => '8.0', 'MySQL 5.7' => '5.7', 'MariaDB 10.11' => 'mariadb-10.11'],
                'placeholder' => 'Select version',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('maxConnections', IntegerType::class, [
                'label' => 'Max Connections',
                'attr' => ['min' => 10, 'max' => 1000],
                'data' => 150,
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('enableRemoteAccess', CheckboxType::class, [
                'label' => 'Enable remote access',
                'required' => false,
            ]))
            ->addExtraField(new ExtraField('rootPassword', TextType::class, [
                'label' => 'Root Password',
                'attr' => ['placeholder' => 'Enter secure password'],
                'required' => true,
            ]));
    }

    private function createPostgreSQLServer(): ServerTemplate
    {
        return (new ServerTemplate('postgresql', 'PostgreSQL', 'Advanced open-source database'))
            ->addExtraField(new ExtraField('pgVersion', ChoiceType::class, [
                'choices' => ['PostgreSQL 16' => '16', 'PostgreSQL 15' => '15', 'PostgreSQL 14' => '14'],
                'placeholder' => 'Select version',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('maxConnections', IntegerType::class, [
                'label' => 'Max Connections',
                'attr' => ['min' => 10, 'max' => 500],
                'data' => 100,
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('enableExtensions', CheckboxType::class, [
                'label' => 'Enable common extensions (uuid-ossp, pg_trgm)',
                'data' => true,
                'required' => false,
            ]));
    }

    private function createMongoDBServer(): ServerTemplate
    {
        return (new ServerTemplate('mongodb', 'MongoDB', 'NoSQL document database'))
            ->addExtraField(new ExtraField('mongoVersion', ChoiceType::class, [
                'choices' => ['MongoDB 7.0' => '7.0', 'MongoDB 6.0' => '6.0', 'MongoDB 5.0' => '5.0'],
                'placeholder' => 'Select version',
                'required' => true,
            ]))
            ->addExtraField(new ExtraField('enableAuth', CheckboxType::class, [
                'label' => 'Enable authentication',
                'data' => true,
                'required' => false,
            ]))
            ->addExtraField(new ExtraField('replicaSet', CheckboxType::class, [
                'label' => 'Configure as replica set',
                'required' => false,
            ]))
            ->addExtraField(new ExtraField('storageEngine', ChoiceType::class, [
                'choices' => ['WiredTiger' => 'wiredTiger', 'In-Memory' => 'inMemory'],
                'placeholder' => 'Select storage engine',
                'required' => true,
            ]));
    }
}
