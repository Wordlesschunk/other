<?php

namespace App\Model;

/**
 * A saved server configuration - user's filled-in template
 */
class SavedServerConfig
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $templateId,
        public readonly array $config,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
