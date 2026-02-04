<?php

namespace App\Form;

use App\Form\JobApplication\AvailabilityType;
use App\Form\JobApplication\ExperienceType;
use App\Form\JobApplication\PersonalInfoType;
use Symfony\Component\Form\Flow\AbstractFlowType;
use Symfony\Component\Form\Flow\FormFlowBuilderInterface;
use Symfony\Component\Form\Flow\Type\NavigatorFlowType;
use App\Model\JobApplication;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobApplicationFlowType extends AbstractFlowType
{
    public function buildFormFlow(FormFlowBuilderInterface $builder, array $options): void
    {
        $builder
            ->addStep('personal_info', PersonalInfoType::class)
            ->addStep('experience', ExperienceType::class)
            ->addStep('availability', AvailabilityType::class);

        $builder->add('navigator', NavigatorFlowType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobApplication::class,
            'step_property_path' => 'currentStep',
        ]);
    }
}
