<?php

namespace App\Controller;

use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TraitementController extends AbstractController
{

    #[Route('/traitement', name: 'app_traitement')]
    public function index(): Response
    {
        // $stats    = $this->statsService->getStats();
        return $this->render('traitement/table.html.twig', [
            // 'stats'    => $stats
        ]);
    }
}
