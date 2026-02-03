<?php

namespace App\Twig\Components;

use App\Form\ServerTemplateForm;
use App\Repository\SavedServerConfigRepository;
use App\Repository\ServerTemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ServerTemplateSelector extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public ?string $loadedConfigId = null;

    #[LiveProp]
    public ?string $loadedConfigName = null;

    public function __construct(
        private readonly SavedServerConfigRepository $savedConfigRepository,
        private readonly ServerTemplateRepository $templateRepository,
    ) {}

    public function getSavedConfigs(): array
    {
        return $this->savedConfigRepository->findAll();
    }

    #[LiveAction]
    public function loadConfig(#[LiveArg] string $configId): void
    {
        $savedConfig = $this->savedConfigRepository->find($configId);
        if (!$savedConfig) {
            return;
        }

        $categoryId = $this->templateRepository->getCategoryForTemplate($savedConfig->templateId);
        
        // Build the form data with saved values
        $formData = array_merge(
            [
                'category' => $categoryId,
                'template' => $savedConfig->templateId,
            ],
            $savedConfig->config
        );

        $this->formValues = $formData;
        $this->loadedConfigId = $configId;
        $this->loadedConfigName = $savedConfig->name;
    }

    #[LiveAction]
    public function clearLoaded(): void
    {
        $this->loadedConfigId = null;
        $this->loadedConfigName = null;
        $this->formValues = [];
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ServerTemplateForm::class, $this->formValues ?? null);
    }
}
