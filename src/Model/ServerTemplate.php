<?php

namespace App\Model;

/**
 * Server template with configurable options
 */
class ServerTemplate
{
    /** @var ExtraField[] */
    private array $extraFields = [];

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description = '',
    ) {}

    public function addExtraField(ExtraField $field): self
    {
        $this->extraFields[] = $field;
        return $this;
    }

    /** @return ExtraField[] */
    public function getExtraFields(): array
    {
        return $this->extraFields;
    }
}
