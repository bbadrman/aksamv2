<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\User;
use App\Form\SearchDateClaudType;
use App\Repository\ProspectRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProspectStatsController extends AbstractController
{
    private ProspectRepository $prospectRepository;
    private TeamRepository $teamRepository;
    private UserRepository $userRepository;

    public function __construct(
        ProspectRepository $prospectRepository,
        TeamRepository $teamRepository,
        UserRepository $userRepository
    ) {
        $this->prospectRepository = $prospectRepository;
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/stats/prospects', name: 'app_prospect_stats')]
    public function prospectStats(Request $request): Response
    {
        // Récupération des paramètres URL
        $urlStartDate = $request->query->get('startDate');
        $urlEndDate = $request->query->get('endDate');
        $teamId = $request->query->get('team');
        $commercialId = $request->query->get('commercial');
        $filterType = $request->query->get('filter', 'team'); // 'team' ou 'commercial'

        // Création du formulaire de recherche
        $form = $this->createForm(SearchDateClaudType::class);

        // Pré-remplissage avec les dates URL
        if ($urlStartDate && $urlEndDate) {
            try {
                $startDateObj = new \DateTime($urlStartDate);
                $endDateObj = new \DateTime($urlEndDate);
                $form->get('startDate')->setData($startDateObj);
                $form->get('endDate')->setData($endDateObj);
            } catch (\Exception $e) {
                $urlStartDate = null;
                $urlEndDate = null;
            }
        }

        $form->handleRequest($request);

        // Détermination des dates finales
        $finalStartDate = null;
        $finalEndDate = null;
        $searchExecuted = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $finalStartDate = $form->get('startDate')->getData();
            $finalEndDate = $form->get('endDate')->getData();
            $searchExecuted = true;
        } elseif ($urlStartDate && $urlEndDate) {
            try {
                $finalStartDate = new \DateTime($urlStartDate);
                $finalEndDate = new \DateTime($urlEndDate);
                $searchExecuted = true;
            } catch (\Exception $e) {
                // Dates invalides
            }
        }

        // Ajustement des dates pour inclure toute la journée
        if ($finalStartDate && $finalEndDate) {
            $adjustedStartDate = clone $finalStartDate;
            $adjustedEndDate = clone $finalEndDate;
            $adjustedStartDate->setTime(0, 0, 0);
            $adjustedEndDate->setTime(23, 59, 59);
            $finalStartDate = $adjustedStartDate;
            $finalEndDate = $adjustedEndDate;
        }

        // Récupération des données
        $teams = $this->teamRepository->findAll();
        $commercials = $this->userRepository->findAll();
        $statsData = [];
        $totalProspects = 0;

        if ($searchExecuted || $teamId || $commercialId) {
            if ($filterType === 'team') {
                $statsData = $this->getTeamStats($finalStartDate, $finalEndDate, $teamId);
            } else {
                $statsData = $this->getCommercialStats($finalStartDate, $finalEndDate, $commercialId);
            }

            // Comptage total
            if ($teamId) {
                $team = $this->teamRepository->find($teamId);
                $prospects = $team ? $this->prospectRepository->findByTeamAndDateRange($team, $finalStartDate, $finalEndDate) : [];
                $totalProspects = count($prospects);
            } elseif ($commercialId) {
                $commercial = $this->userRepository->find($commercialId);
                $prospects = $commercial ? $this->prospectRepository->findByCommercialAndDateRange($commercial, $finalStartDate, $finalEndDate) : [];
                $totalProspects = count($prospects);
            } else {
                $totalProspects = $this->prospectRepository->countProspectsByDateRange($finalStartDate, $finalEndDate);
            }
        }

        return $this->render('stat/prospect_stats.html.twig', [
            'search_form' => $form->createView(),
            'statsData' => $statsData,
            'teams' => $teams,
            'commercials' => $commercials,
            'totalProspects' => $totalProspects,
            'searchExecuted' => $searchExecuted,
            'finalStartDate' => $finalStartDate,
            'finalEndDate' => $finalEndDate,
            'selectedTeam' => $teamId,
            'selectedCommercial' => $commercialId,
            'filterType' => $filterType,
            'fromUrl' => ($urlStartDate && $urlEndDate && !$form->isSubmitted()),
        ]);
    }

    #[Route('/stats/prospects/team/{id}', name: 'app_prospect_stats_team_detail')]
    public function teamDetailStats(Team $team, Request $request): Response
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        $finalStartDate = null;
        $finalEndDate = null;

        if ($startDate && $endDate) {
            $finalStartDate = new \DateTime($startDate);
            $finalEndDate = new \DateTime($endDate);
            $finalStartDate->setTime(0, 0, 0);
            $finalEndDate->setTime(23, 59, 59);
        }

        $prospects = $this->prospectRepository->findByTeamAndDateRange($team, $finalStartDate, $finalEndDate);

        return $this->render('stat/team_detail_stats.html.twig', [
            'team' => $team,
            'prospects' => $prospects,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    #[Route('/stats/prospects/commercial/{id}', name: 'app_prospect_stats_commercial_detail')]
    public function commercialDetailStats(User $commercial, Request $request): Response
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        $finalStartDate = null;
        $finalEndDate = null;

        if ($startDate && $endDate) {
            $finalStartDate = new \DateTime($startDate);
            $finalEndDate = new \DateTime($endDate);
            $finalStartDate->setTime(0, 0, 0);
            $finalEndDate->setTime(23, 59, 59);
        }

        $prospects = $this->prospectRepository->findByCommercialAndDateRange($commercial, $finalStartDate, $finalEndDate);

        return $this->render('stat/commercial_detail_stats.html.twig', [
            'commercial' => $commercial,
            'prospects' => $prospects,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    private function getTeamStats(?\DateTime $startDate, ?\DateTime $endDate, ?string $teamId): array
    {
        $teams = $teamId ? [$this->teamRepository->find($teamId)] : $this->teamRepository->findAll();
        $statsData = [];

        foreach ($teams as $team) {
            if (!$team) continue;

            $prospects = $this->prospectRepository->findByTeamAndDateRange($team, $startDate, $endDate);

            $teamData = [
                'entity' => $team,
                'totalProspects' => count($prospects),
                'sourceStats' => [],
                'typeStats' => [],
                'activiteStats' => [],
                'monthlyStats' => [],
            ];

            // Calcul des statistiques détaillées
            $teamData = $this->calculateDetailedStats($teamData, $prospects);

            if ($teamData['totalProspects'] > 0) {
                $statsData[] = $teamData;
            }
        }

        return $statsData;
    }

    private function getCommercialStats(?\DateTime $startDate, ?\DateTime $endDate, ?string $commercialId): array
    {
        $commercials = $commercialId ? [$this->userRepository->find($commercialId)] : $this->userRepository->findAll();
        $statsData = [];

        foreach ($commercials as $commercial) {
            if (!$commercial) continue;

            $prospects = $this->prospectRepository->findByCommercialAndDateRange($commercial, $startDate, $endDate);

            $commercialData = [
                'entity' => $commercial,
                'totalProspects' => count($prospects),
                'sourceStats' => [],
                'typeStats' => [],
                'activiteStats' => [],
                'monthlyStats' => [],
            ];

            // Calcul des statistiques détaillées
            $commercialData = $this->calculateDetailedStats($commercialData, $prospects);

            if ($commercialData['totalProspects'] > 0) {
                $statsData[] = $commercialData;
            }
        }

        return $statsData;
    }

    private function calculateDetailedStats(array $data, array $prospects): array
    {
        // Mappings pour les choix du formulaire
        $sourceMapping = [
            '1' => 'Parrainage',
            '2' => 'Appel Entrant',
            '3' => 'Avenant',
            '4' => 'Ancienne contrat',
            '5' => 'Propre site',
            '6' => 'Revendeur',
            '7' => 'Hors cible',
            '8' => 'Test',
        ];

        $typeMapping = [
            '1' => 'Particulier',
            '2' => 'Professionnel',
        ];

        $activiteMapping = [
            '1' => 'TPM',
            '2' => 'VTC',
            '3' => 'Sociéte',
            '4' => 'Décenale',
            '5' => 'Dommage',
            '6' => 'Marchandise',
            '7' => 'Négociant',
            '8' => 'Prof auto',
            '9' => 'Garage',
        ];

        foreach ($prospects as $prospect) {
            // Statistiques par source (motifSaise)
            $source = $prospect->getMotifSaise();
            $sourceLabel = $sourceMapping[$source] ?? 'Inconnu';
            $data['sourceStats'][$sourceLabel] = ($data['sourceStats'][$sourceLabel] ?? 0) + 1;

            // Statistiques par type
            $type = $prospect->getTypeProspect();
            $typeLabel = $typeMapping[$type] ?? 'Inconnu';
            $data['typeStats'][$typeLabel] = ($data['typeStats'][$typeLabel] ?? 0) + 1;

            // Statistiques par activité
            $activite = $prospect->getActivites();
            if ($activite) {
                $activiteLabel = $activiteMapping[$activite] ?? 'Inconnu';
                $data['activiteStats'][$activiteLabel] = ($data['activiteStats'][$activiteLabel] ?? 0) + 1;
            }

            // Statistiques mensuelles
            if ($prospect->getCreatAt()) {
                $month = $prospect->getCreatAt()->format('Y-m');
                $data['monthlyStats'][$month] = ($data['monthlyStats'][$month] ?? 0) + 1;
            }
        }

        // Tri des statistiques
        ksort($data['sourceStats']);
        ksort($data['typeStats']);
        ksort($data['activiteStats']);
        ksort($data['monthlyStats']);

        return $data;
    }
}
