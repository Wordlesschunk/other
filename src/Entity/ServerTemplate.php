<?php

namespace App\Entity;

use App\Model\ExtraField;
use App\Repository\ServerTemplateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

#[ORM\Entity(repositoryClass: ServerTemplateRepository::class)]
#[ORM\Table(name: 'server_templates')]
class ServerTemplate
{
    private const TYPE_MAP = [
        'choice' => ChoiceType::class,
        'text' => TextType::class,
        'textarea' => TextareaType::class,
        'integer' => IntegerType::class,
        'checkbox' => CheckboxType::class,
    ];

    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private string $id;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 50)]
    private string $category;

    #[ORM\Column(type: 'json')]
    private array $config = [];

    /** @var ExtraField[]|null */
    private ?array $extraFields = null;

    public function __construct(string $id, string $name, string $category = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): static
    {
        $this->config = $config;
        $this->extraFields = null;
        return $this;
    }

    /** @return ExtraField[] */
    public function getExtraFields(): array
    {
        if ($this->extraFields !== null) {
            return $this->extraFields;
        }

        $this->extraFields = [];
        $fields = $this->config['fields'] ?? [];

        foreach ($fields as $fieldDef) {
            $fieldType = self::TYPE_MAP[$fieldDef['type']] ?? null;
            if (!$fieldType) {
                continue;
            }

            $this->extraFields[] = new ExtraField(
                $fieldDef['name'],
                $fieldType,
                $fieldDef['options'] ?? []
            );
        }

        return $this->extraFields;
    }
}
