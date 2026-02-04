<?php

namespace App\Form\JobApplication;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Earliest Start Date',
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(groups: ['availability']),
                    new Assert\GreaterThanOrEqual('today', groups: ['availability']),
                ],
            ])
            ->add('workType', ChoiceType::class, [
                'label' => 'Preferred Work Type',
                'choices' => [
                    'Full-time' => 'full_time',
                    'Part-time' => 'part_time',
                    'Contract' => 'contract',
                    'Freelance' => 'freelance',
                ],
                'constraints' => [
                    new Assert\NotBlank(groups: ['availability']),
                ],
            ])
            ->add('salaryExpectation', MoneyType::class, [
                'label' => 'Salary Expectation (Annual)',
                'currency' => 'EUR',
                'required' => false,
            ])
            ->add('remotePreference', ChoiceType::class, [
                'label' => 'Remote Work Preference',
                'choices' => [
                    'Fully Remote' => 'remote',
                    'Hybrid' => 'hybrid',
                    'On-site' => 'onsite',
                    'Flexible' => 'flexible',
                ],
                'constraints' => [
                    new Assert\NotBlank(groups: ['availability']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'inherit_data' => true,
        ]);
    }
}
