<?php

namespace App\Model;

/**
 * Server category (e.g., Game Servers, Web Servers, Database Servers)
 */
class ServerCategory
{
    /** @var ServerTemplate[] */
    private array $templates = [];

    public function __construct(
        public readonly string $id,
        public readonly string $name,
    ) {}

    public function addTemplate(ServerTemplate $template): self
    {
        $this->templates[] = $template;
        return $this;
    }

    /** @return ServerTemplate[] */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /** @return array<string, string> [name => id] for form choices */
    public function getTemplateChoices(): array
    {
        $choices = [];
        foreach ($this->templates as $template) {
            $choices[$template->name] = $template->id;
        }
        return $choices;
    }
}
