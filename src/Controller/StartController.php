<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StartController extends AbstractController
{
    #[Route('/start', name: 'app_start')]
    public function index(): Response
    {
        return $this->render('start/index.html.twig', [
            'controller_name' => 'StartController',
        ]);
    }
}
