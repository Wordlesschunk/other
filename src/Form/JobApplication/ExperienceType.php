<?php

namespace App\Form\JobApplication;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('yearsOfExperience', IntegerType::class, [
                'label' => 'Years of Experience',
                'constraints' => [
                    new Assert\NotBlank(groups: ['experience']),
                    new Assert\PositiveOrZero(groups: ['experience']),
                ],
                'attr' => ['min' => 0, 'max' => 50],
            ])
            ->add('currentRole', TextareaType::class, [
                'label' => 'Current/Most Recent Role',
                'constraints' => [
                    new Assert\NotBlank(groups: ['experience']),
                ],
                'attr' => ['rows' => 3],
            ])
            ->add('skills', ChoiceType::class, [
                'label' => 'Key Skills',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'PHP' => 'php',
                    'Symfony' => 'symfony',
                    'JavaScript' => 'javascript',
                    'Python' => 'python',
                    'Database Management' => 'database',
                    'DevOps' => 'devops',
                ],
                'constraints' => [
                    new Assert\Count(min: 1, minMessage: 'Please select at least one skill.', groups: ['experience']),
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
