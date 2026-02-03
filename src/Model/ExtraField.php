<?php

namespace App\Model;

/**
 * Mock ExtraField entity - replace with Doctrine entity when database is ready.
 */
class ExtraField
{
    public function __construct(
        public readonly string $fieldName,
        public readonly string $fieldType,
        public readonly array $options,
    ) {}
}
