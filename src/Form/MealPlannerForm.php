<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class MealPlannerForm extends AbstractType
{
    private const MEALS = [
        'Breakfast' => 'breakfast',
        'Lunch' => 'lunch',
        'Dinner' => 'dinner',
    ];

    private const FOODS = [
        'breakfast' => [
            'Cereal' => 'cereal',
            'Toast' => 'toast',
        ],
        'lunch' => [
            'Sandwich' => 'sandwich',
            'Pizza' => 'pizza',
        ],
        'dinner' => [
            'Pasta' => 'pasta',
            'Pizza' => 'pizza',
        ],
    ];

    private const PIZZA_SIZES = [
        'Small' => 'small',
        'Medium' => 'medium',
        'Large' => 'large',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('meal', ChoiceType::class, [
                'choices' => self::MEALS,
                'placeholder' => 'Which meal is it?',
            ])

            ->addDependent('mainFood', 'meal', static function (DependentField $field, ?string $meal) {
                $field->add(ChoiceType::class, [
                    'choices' => $meal ? self::FOODS[$meal] ?? [] : [],
                    'placeholder' => $meal
                        ? sprintf("What's for %s?", ucfirst($meal))
                        : 'Select a meal first',
                    'disabled' => null === $meal,
                ]);
            })

            ->addDependent('pizzaSize', 'mainFood', static function (DependentField $field, ?string $food) {
                if ($food !== 'pizza') {
                    return;
                }

                $field->add(ChoiceType::class, [
                    'choices' => self::PIZZA_SIZES,
                    'placeholder' => 'What size pizza?',
                    'required' => true,
                ]);
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
