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
        private readonly ServerTemplateRepository $repository,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('category', ChoiceType::class, [
                'choices' => $this->repository->getCategoryChoices(),
                'placeholder' => 'Select server category',
                'label' => 'Server Category',
            ])
            ->addDependent('template', 'category', function (DependentField $field, ?string $categoryId) {
                $category = $categoryId ? $this->repository->findCategory($categoryId) : null;

                $field->add(ChoiceType::class, [
                    'choices' => $category?->getTemplateChoices() ?? [],
                    'placeholder' => $category
                        ? 'Select a template'
                        : 'Select a category first',
                    'disabled' => null === $category,
                    'label' => 'Server Template',
                ]);
            });

        // Register all possible extra field names
        foreach ($this->repository->getAllExtraFieldNames() as $fieldName) {
            $builder->addDependent($fieldName, 'template', function (DependentField $field, ?string $templateId) use ($fieldName) {
                $template = $templateId ? $this->repository->findTemplate($templateId) : null;
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
