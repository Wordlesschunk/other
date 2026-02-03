<?php

namespace App\Repository;

use App\Model\ExtraField;
use App\Model\ServerCategory;
use App\Model\ServerTemplate;

/**
 * Repository for server template data.
 * Mock data structured to match future database schema:
 * - name: string
 * - config: JSON with field definitions
 */
class ServerTemplateRepository
{
    /** @var array<string, ServerCategory> */
    private array $categories;

    /** @var array<string, ServerTemplate> */
    private array $templates;

    /** 
     * Mock database rows - each has 'name' and 'config' (JSON)
     * @var array<string, array{name: string, category: string, config: string}>
     */
    private array $mockDbRows = [
        'minecraft' => [
            'name' => 'Minecraft',
            'category' => 'game',
            'config' => '{
                "fields": [
                    {"name": "serverVersion", "type": "choice", "options": {"choices": {"1.20.4": "1.20.4", "1.19.4": "1.19.4", "1.18.2": "1.18.2"}, "placeholder": "Select version", "required": true}},
                    {"name": "maxPlayers", "type": "integer", "options": {"label": "Max Players", "attr": {"min": 2, "max": 100}, "data": 20, "required": true}},
                    {"name": "gameMode", "type": "choice", "options": {"choices": {"Survival": "survival", "Creative": "creative", "Adventure": "adventure"}, "placeholder": "Select game mode", "required": true}},
                    {"name": "enableMods", "type": "checkbox", "options": {"label": "Enable mod support (Forge/Fabric)", "required": false}}
                ]
            }'
        ],
        'csgo' => [
            'name' => 'CS2 / CS:GO',
            'category' => 'game',
            'config' => '{
                "fields": [
                    {"name": "maxPlayers", "type": "integer", "options": {"label": "Max Players", "attr": {"min": 2, "max": 64}, "data": 10, "required": true}},
                    {"name": "serverType", "type": "choice", "options": {"choices": {"Competitive": "competitive", "Casual": "casual", "Deathmatch": "deathmatch"}, "placeholder": "Select server type", "required": true}},
                    {"name": "enableVAC", "type": "checkbox", "options": {"label": "Enable VAC (Valve Anti-Cheat)", "data": true, "required": false}}
                ]
            }'
        ],
        'valheim' => [
            'name' => 'Valheim',
            'category' => 'game',
            'config' => '{
                "fields": [
                    {"name": "worldName", "type": "text", "options": {"label": "World Name", "attr": {"placeholder": "e.g. MyVikingWorld"}, "required": true}},
                    {"name": "maxPlayers", "type": "integer", "options": {"label": "Max Players", "attr": {"min": 2, "max": 10}, "data": 10, "required": true}},
                    {"name": "enableCrossplay", "type": "checkbox", "options": {"label": "Enable crossplay", "required": false}}
                ]
            }'
        ],
        'apache' => [
            'name' => 'Apache HTTP Server',
            'category' => 'web',
            'config' => '{
                "fields": [
                    {"name": "phpVersion", "type": "choice", "options": {"choices": {"PHP 8.3": "8.3", "PHP 8.2": "8.2", "PHP 8.1": "8.1", "None": "none"}, "placeholder": "Select PHP version", "required": true}},
                    {"name": "enableSSL", "type": "checkbox", "options": {"label": "Enable SSL/TLS", "data": true, "required": false}},
                    {"name": "enableModRewrite", "type": "checkbox", "options": {"label": "Enable mod_rewrite", "data": true, "required": false}},
                    {"name": "documentRoot", "type": "text", "options": {"label": "Document Root", "attr": {"placeholder": "/var/www/html"}, "data": "/var/www/html", "required": true}}
                ]
            }'
        ],
        'nginx' => [
            'name' => 'Nginx',
            'category' => 'web',
            'config' => '{
                "fields": [
                    {"name": "workerProcesses", "type": "choice", "options": {"choices": {"Auto": "auto", "1": "1", "2": "2", "4": "4", "8": "8"}, "placeholder": "Worker processes", "required": true}},
                    {"name": "enableSSL", "type": "checkbox", "options": {"label": "Enable SSL/TLS", "data": true, "required": false}},
                    {"name": "enableGzip", "type": "checkbox", "options": {"label": "Enable Gzip compression", "data": true, "required": false}},
                    {"name": "serverName", "type": "text", "options": {"label": "Server Name", "attr": {"placeholder": "example.com"}, "required": true}}
                ]
            }'
        ],
        'nodejs' => [
            'name' => 'Node.js',
            'category' => 'web',
            'config' => '{
                "fields": [
                    {"name": "nodeVersion", "type": "choice", "options": {"choices": {"Node 20 LTS": "20", "Node 18 LTS": "18", "Node 21": "21"}, "placeholder": "Select Node.js version", "required": true}},
                    {"name": "packageManager", "type": "choice", "options": {"choices": {"npm": "npm", "yarn": "yarn", "pnpm": "pnpm"}, "placeholder": "Select package manager", "required": true}},
                    {"name": "port", "type": "integer", "options": {"label": "Port", "attr": {"min": 1024, "max": 65535}, "data": 3000, "required": true}},
                    {"name": "enablePM2", "type": "checkbox", "options": {"label": "Enable PM2 process manager", "required": false}}
                ]
            }'
        ],
        'mysql' => [
            'name' => 'MySQL',
            'category' => 'database',
            'config' => '{
                "fields": [
                    {"name": "mysqlVersion", "type": "choice", "options": {"choices": {"MySQL 8.0": "8.0", "MySQL 5.7": "5.7", "MariaDB 10.11": "mariadb-10.11"}, "placeholder": "Select version", "required": true}},
                    {"name": "maxConnections", "type": "integer", "options": {"label": "Max Connections", "attr": {"min": 10, "max": 1000}, "data": 150, "required": true}},
                    {"name": "enableRemoteAccess", "type": "checkbox", "options": {"label": "Enable remote access", "required": false}},
                    {"name": "rootPassword", "type": "text", "options": {"label": "Root Password", "attr": {"placeholder": "Enter secure password"}, "required": true}}
                ]
            }'
        ],
        'postgresql' => [
            'name' => 'PostgreSQL',
            'category' => 'database',
            'config' => '{
                "fields": [
                    {"name": "pgVersion", "type": "choice", "options": {"choices": {"PostgreSQL 16": "16", "PostgreSQL 15": "15", "PostgreSQL 14": "14"}, "placeholder": "Select version", "required": true}},
                    {"name": "maxConnections", "type": "integer", "options": {"label": "Max Connections", "attr": {"min": 10, "max": 500}, "data": 100, "required": true}},
                    {"name": "enableExtensions", "type": "checkbox", "options": {"label": "Enable common extensions (uuid-ossp, pg_trgm)", "data": true, "required": false}}
                ]
            }'
        ],
        'mongodb' => [
            'name' => 'MongoDB',
            'category' => 'database',
            'config' => '{
                "fields": [
                    {"name": "mongoVersion", "type": "choice", "options": {"choices": {"MongoDB 7.0": "7.0", "MongoDB 6.0": "6.0", "MongoDB 5.0": "5.0"}, "placeholder": "Select version", "required": true}},
                    {"name": "enableAuth", "type": "checkbox", "options": {"label": "Enable authentication", "data": true, "required": false}},
                    {"name": "replicaSet", "type": "checkbox", "options": {"label": "Configure as replica set", "required": false}},
                    {"name": "storageEngine", "type": "choice", "options": {"choices": {"WiredTiger": "wiredTiger", "In-Memory": "inMemory"}, "placeholder": "Select storage engine", "required": true}}
                ]
            }'
        ],
    ];

    private const TYPE_MAP = [
        'choice' => \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class,
        'text' => \Symfony\Component\Form\Extension\Core\Type\TextType::class,
        'textarea' => \Symfony\Component\Form\Extension\Core\Type\TextareaType::class,
        'integer' => \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
        'checkbox' => \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class,
    ];

    public function __construct()
    {
        $this->loadFromMockDb();
    }

    public function findCategory(string $id): ?ServerCategory
    {
        return $this->categories[$id] ?? null;
    }

    public function findTemplate(string $id): ?ServerTemplate
    {
        return $this->templates[$id] ?? null;
    }

    public function getCategoryForTemplate(string $templateId): ?string
    {
        return $this->mockDbRows[$templateId]['category'] ?? null;
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

    private function loadFromMockDb(): void
    {
        $this->templates = [];
        $this->categories = [
            'game' => new ServerCategory('game', 'Game Servers'),
            'web' => new ServerCategory('web', 'Web Servers'),
            'database' => new ServerCategory('database', 'Database Servers'),
        ];

        foreach ($this->mockDbRows as $id => $row) {
            $template = $this->hydrateTemplate($id, $row['name'], $row['config']);
            $this->templates[$id] = $template;
            
            if (isset($this->categories[$row['category']])) {
                $this->categories[$row['category']]->addTemplate($template);
            }
        }
    }

    private function hydrateTemplate(string $id, string $name, string $configJson): ServerTemplate
    {
        $template = new ServerTemplate($id, $name);
        $config = json_decode($configJson, true);

        if (!isset($config['fields']) || !is_array($config['fields'])) {
            return $template;
        }

        foreach ($config['fields'] as $fieldDef) {
            $fieldType = self::TYPE_MAP[$fieldDef['type']] ?? null;
            if (!$fieldType) {
                continue;
            }

            $template->addExtraField(new ExtraField(
                $fieldDef['name'],
                $fieldType,
                $fieldDef['options'] ?? []
            ));
        }

        return $template;
    }
}
