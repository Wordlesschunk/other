<?php

namespace App\Form;

use App\Repository\ServerTemplateRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class ServerTemplateForm extends AbstractType
{
    public function __construct(
        private readonly ServerTemplateRepository $templateRepository,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('category', ChoiceType::class, [
                'choices' => $this->templateRepository->getCategoryChoices(),
                'placeholder' => 'Select server category',
                'label' => 'Server Category',
            ])
            ->addDependent('template', 'category', function (DependentField $field, ?string $category) {
                $choices = $category
                    ? $this->templateRepository->getTemplateChoicesByCategory($category)
                    : [];

                $field->add(ChoiceType::class, [
                    'choices' => $choices,
                    'placeholder' => $category
                        ? 'Select a template'
                        : 'Select a category first',
                    'disabled' => !$category,
                    'label' => 'Server Template',
                ]);
            });

        // Register all possible extra field names
        foreach ($this->templateRepository->getAllExtraFieldNames() as $fieldName) {
            $builder->addDependent($fieldName, 'template', function (DependentField $field, ?string $templateId) use ($fieldName) {
                $template = $templateId ? $this->templateRepository->find($templateId) : null;
                if (!$template) {
                    return;
                }

                foreach ($template->getExtraFields() as $extraField) {
                    if ($extraField->fieldName === $fieldName) {
                        $field->add($extraField->fieldType, $extraField->options);
                        return;
                    }
                }
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
