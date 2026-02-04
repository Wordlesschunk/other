<?php

namespace App\Form;

use App\Form\JobApplication\Step1Type;
use App\Form\JobApplication\Step2Type;
use App\Model\JobApplication;
use Symfony\Component\Form\Flow\AbstractFlowType;
use Symfony\Component\Form\Flow\FormFlowBuilderInterface;
use Symfony\Component\Form\Flow\Type\NavigatorFlowType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobApplicationFlowType extends AbstractFlowType
{
    public function buildFormFlow(FormFlowBuilderInterface $builder, array $options): void
    {
        $builder
            ->addStep('step1', Step1Type::class)
            ->addStep('step2', Step2Type::class);

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
