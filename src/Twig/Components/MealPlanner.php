<?php

namespace App\Twig\Components;

use App\Form\MealPlannerForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class MealPlanner extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    /**
     * @return FormInterface
     */
    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(MealPlannerForm::class);
    }
}
