<?php

namespace App\Repository;

use App\Model\SavedServerConfig;

/**
 * Repository for saved server configurations.
 * Mock data structured to match future database schema:
 * - id: string
 * - name: string (user-given name for this server)
 * - template_id: string (FK to server_templates)
 * - config: JSON (the user's filled-in values)
 * - created_at: datetime
 * - updated_at: datetime|null
 */
class SavedServerConfigRepository
{
    /** @var array<string, SavedServerConfig> */
    private array $configs = [];

    /**
     * Mock database rows
     * @var array<string, array{name: string, template_id: string, config: string, created_at: string, updated_at: string|null}>
     */
    private array $mockDbRows = [
        'srv-001' => [
            'name' => 'My Minecraft Server',
            'template_id' => 'minecraft',
            'config' => '{"serverVersion": "1.20.4", "maxPlayers": 20, "gameMode": "survival", "enableMods": false}',
            'created_at' => '2024-01-15 10:30:00',
            'updated_at' => '2024-02-01 14:22:00',
        ],
        'srv-002' => [
            'name' => 'Production Web Server',
            'template_id' => 'nginx',
            'config' => '{"workerProcesses": "auto", "enableSSL": true, "enableGzip": true, "serverName": "myapp.com"}',
            'created_at' => '2024-01-20 09:00:00',
            'updated_at' => null,
        ],
        'srv-003' => [
            'name' => 'Dev Database',
            'template_id' => 'postgresql',
            'config' => '{"pgVersion": "16", "maxConnections": 50, "enableExtensions": true}',
            'created_at' => '2024-02-05 16:45:00',
            'updated_at' => null,
        ],
        'srv-004' => [
            'name' => 'CS2 Competitive',
            'template_id' => 'csgo',
            'config' => '{"maxPlayers": 10, "serverType": "competitive", "enableVAC": true}',
            'created_at' => '2024-02-10 20:00:00',
            'updated_at' => '2024-02-12 18:30:00',
        ],
        'srv-005' => [
            'name' => 'Blog Backend',
            'template_id' => 'apache',
            'config' => '{"phpVersion": "8.3", "enableSSL": true, "enableModRewrite": true, "documentRoot": "/var/www/blog"}',
            'created_at' => '2024-02-15 11:00:00',
            'updated_at' => null,
        ],
    ];

    public function __construct()
    {
        $this->loadFromMockDb();
    }

    public function find(string $id): ?SavedServerConfig
    {
        return $this->configs[$id] ?? null;
    }

    /** @return SavedServerConfig[] */
    public function findAll(): array
    {
        return array_values($this->configs);
    }

    /** @return SavedServerConfig[] */
    public function findByTemplateId(string $templateId): array
    {
        return array_filter(
            $this->configs,
            fn(SavedServerConfig $c) => $c->templateId === $templateId
        );
    }

    public function save(SavedServerConfig $config): void
    {
        $this->configs[$config->id] = $config;
        // In real implementation, persist to database
    }

    public function delete(string $id): void
    {
        unset($this->configs[$id]);
        // In real implementation, delete from database
    }

    /**
     * Create a new config (simulates INSERT)
     */
    public function create(string $name, string $templateId, array $configValues): SavedServerConfig
    {
        $id = 'srv-' . str_pad((string)(count($this->configs) + 1), 3, '0', STR_PAD_LEFT);
        
        $config = new SavedServerConfig(
            $id,
            $name,
            $templateId,
            $configValues,
            new \DateTimeImmutable(),
        );
        
        $this->configs[$id] = $config;
        return $config;
    }

    /**
     * Update an existing config (simulates UPDATE)
     */
    public function update(string $id, string $name, array $configValues): ?SavedServerConfig
    {
        $existing = $this->configs[$id] ?? null;
        if (!$existing) {
            return null;
        }

        $updated = new SavedServerConfig(
            $id,
            $name,
            $existing->templateId,
            $configValues,
            $existing->createdAt,
            new \DateTimeImmutable(),
        );

        $this->configs[$id] = $updated;
        return $updated;
    }

    private function loadFromMockDb(): void
    {
        foreach ($this->mockDbRows as $id => $row) {
            $this->configs[$id] = new SavedServerConfig(
                $id,
                $row['name'],
                $row['template_id'],
                json_decode($row['config'], true),
                new \DateTimeImmutable($row['created_at']),
                $row['updated_at'] ? new \DateTimeImmutable($row['updated_at']) : null,
            );
        }
    }
}
