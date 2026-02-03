<?php

namespace App\Repository;

use App\Entity\ServerTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServerTemplate>
 */
class ServerTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServerTemplate::class);
    }

    /** @return string[] All unique extra field names across all templates */
    public function getAllExtraFieldNames(): array
    {
        $templates = $this->findAll();
        $names = [];

        foreach ($templates as $template) {
            foreach ($template->getExtraFields() as $field) {
                $names[$field->fieldName] = true;
            }
        }

        return array_keys($names);
    }

    /** @return array<string, string> [name => id] */
    public function getCategoryChoices(): array
    {
        $templates = $this->findAll();
        $categories = [];

        foreach ($templates as $template) {
            $cat = $template->getCategory();
            if ($cat && !isset($categories[$cat])) {
                // Convert 'game' to 'Game Servers', etc.
                $label = ucfirst($cat) . ' Servers';
                $categories[$label] = $cat;
            }
        }

        return $categories;
    }

    /** @return array<string, string> [name => id] for templates in a category */
    public function getTemplateChoicesByCategory(string $category): array
    {
        $templates = $this->findBy(['category' => $category]);
        $choices = [];

        foreach ($templates as $template) {
            $choices[$template->getName()] = $template->getId();
        }

        return $choices;
    }
}
