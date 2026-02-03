<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

    /**
     * Extra fields configuration per food item.
     * Each food can have multiple extra fields with their own settings.
     */
    private const EXTRA_FIELDS = [
        'pizza' => [
            'pizzaSize' => [
                'type' => ChoiceType::class,
                'options' => [
                    'choices' => [
                        'Small' => 'small',
                        'Medium' => 'medium',
                        'Large' => 'large',
                    ],
                    'placeholder' => 'What size pizza?',
                    'required' => true,
                ],
            ],
            'toppings' => [
                'type' => ChoiceType::class,
                'options' => [
                    'choices' => [
                        'Pepperoni' => 'pepperoni',
                        'Mushrooms' => 'mushrooms',
                        'Olives' => 'olives',
                        'Extra Cheese' => 'extra_cheese',
                    ],
                    'placeholder' => 'Choose a topping',
                    'required' => false,
                ],
            ],
        ],
        'sandwich' => [
            'bread' => [
                'type' => ChoiceType::class,
                'options' => [
                    'choices' => [
                        'White' => 'white',
                        'Wheat' => 'wheat',
                        'Sourdough' => 'sourdough',
                    ],
                    'placeholder' => 'Choose bread type',
                    'required' => true,
                ],
            ],
        ],
        'pasta' => [
            'sauce' => [
                'type' => ChoiceType::class,
                'options' => [
                    'choices' => [
                        'Marinara' => 'marinara',
                        'Alfredo' => 'alfredo',
                        'Pesto' => 'pesto',
                    ],
                    'placeholder' => 'Choose a sauce',
                    'required' => true,
                ],
            ],
        ],
        'cereal' => [
            'milk' => [
                'type' => ChoiceType::class,
                'options' => [
                    'choices' => [
                        'Whole' => 'whole',
                        'Skim' => 'skim',
                        'Oat' => 'oat',
                        'Almond' => 'almond',
                    ],
                    'placeholder' => 'Choose milk type',
                    'required' => false,
                ],
            ],
        ],
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
            });

        // Dynamically add extra fields based on the EXTRA_FIELDS configuration
        foreach (self::EXTRA_FIELDS as $food => $fields) {
            foreach ($fields as $fieldName => $fieldConfig) {
                $builder->addDependent($fieldName, 'mainFood', static function (DependentField $field, ?string $selectedFood) use ($food, $fieldConfig) {
                    if ($selectedFood !== $food) {
                        return;
                    }

                    $field->add($fieldConfig['type'], $fieldConfig['options']);
                });
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
