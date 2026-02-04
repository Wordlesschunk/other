<?php

namespace App\Controller;

use App\Form\JobApplicationFlowType;
use App\Model\JobApplication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobApplicationController extends AbstractController
{
    #[Route('/apply', name: 'job_application')]
    public function apply(Request $request): Response
    {
        $flow = $this->createForm(JobApplicationFlowType::class, new JobApplication())
            ->handleRequest($request);

        if ($flow->isSubmitted() && $flow->isValid() && $flow->isFinished()) {
            $data = $flow->getData();

            dd($data);

            // Here you would typically:
            // - Save to database
            // - Send confirmation email
            // - etc.

            $this->addFlash('success', sprintf(
                'Thank you %s! Your application has been submitted.',
                $data->firstName ?? 'applicant'
            ));

            return $this->redirectToRoute('job_application_success');
        }

        return $this->render('job_application/apply.html.twig', [
            'form' => $flow->getStepForm(),
        ]);
    }

    #[Route('/apply/success', name: 'job_application_success')]
    public function success(): Response
    {
        return $this->render('job_application/success.html.twig');
    }
}
