<?php

namespace App\Controller;

use App\Form\SearchDateClaudType;
use App\Repository\ContratRepository;
use App\Repository\ProspectRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Service\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// ... autres imports

class SatistiqueController extends AbstractController
{
    public function __construct(
        private StatisticsService $statisticsService,
        private ProspectRepository $prospectRepository,
        private ContratRepository $contratRepository,
        private TeamRepository $teamRepository,
        private UserRepository $userRepository
    ) {}

    // generer la page de statistiques avec sans calcul des twig
    #[Route('/calendrie2', name: 'prospects_calandri2')]
    public function prospectsCalendrie(Request $request): Response
    {
        // Récupération des paramètres URL
        $urlStartDate = $request->query->get('startDate');
        $urlEndDate = $request->query->get('endDate');

        // Création du formulaire
        $form = $this->createForm(SearchDateClaudType::class);

        // Pré-remplissage automatique si des dates URL existent
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

        // Initialisation
        $contrats = [];
        $teams = $this->teamRepository->findAll();
        $prospects = [];
        $searchExecuted = false;

        // Déterminer si on doit exécuter une recherche
        $shouldExecuteSearch = false;
        $searchStartDate = null;
        $searchEndDate = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $searchStartDate = $form->get('startDate')->getData();
            $searchEndDate = $form->get('endDate')->getData();
            $shouldExecuteSearch = true;
        } elseif ($urlStartDate && $urlEndDate) {
            try {
                $searchStartDate = new \DateTime($urlStartDate);
                $searchEndDate = new \DateTime($urlEndDate);
                $shouldExecuteSearch = true;
            } catch (\Exception $e) {
                // Dates invalides, pas d'exécution
            }
        }

        // Initialisation des statistiques
        $statistics = [
            'total' => [
                'prospects_count' => 0,
                'contrats_count' => 0,
                'cotisation_total' => 0,
                // ... autres valeurs par défaut
            ],
            'teams' => [],
            'users' => []
        ];

        // Exécution de la recherche et calcul des statistiques
        if ($shouldExecuteSearch && $searchStartDate && $searchEndDate) {
            if (!$searchStartDate instanceof \DateTimeInterface || !$searchEndDate instanceof \DateTimeInterface) {
                throw new \InvalidArgumentException('Les dates doivent être des instances de DateTimeInterface');
            }

            $prospects = $this->prospectRepository->findByDateInterval($searchStartDate, $searchEndDate);
            $contrats = $this->contratRepository->findByDateInterval($searchStartDate, $searchEndDate);
            $searchExecuted = true;

            // Calcul des statistiques totales
            $statistics['total'] = $this->calculateTotalStatistics($prospects, $contrats);

            // Calcul des statistiques par équipe
            foreach ($teams as $team) {
                $teamStats = $this->calculateTeamStatistics($team, $prospects, $contrats);
                $statistics['teams'][$team->getId()] = $teamStats;

                // Calcul des statistiques par utilisateur dans l'équipe
                foreach ($team->getUsers() as $user) {
                    $userStats = $this->calculateUserStatistics($user, $prospects, $contrats);
                    $statistics['users'][$user->getId()] = $userStats;
                }
            }
        }

        return $this->render('stat/statistique.html.twig', [
            'prospects' => $prospects,
            'teams' => $teams,
            'contrats' => $contrats,
            'statistics' => $statistics,
            'search_form' => $form->createView(),
            'searchExecuted' => $searchExecuted,
            'searchStartDate' => $searchStartDate,
            'searchEndDate' => $searchEndDate,
            'fromUrl' => ($urlStartDate && $urlEndDate && !$form->isSubmitted()),
        ]);
    }

    /**
     * Calcule les statistiques totales
     */
    private function calculateTotalStatistics(array $prospects, array $contrats): array
    {
        // Filtrer les prospects avec équipe
        $prospectsWithTeam = array_filter($prospects, fn($p) => $p->getTeam() !== null);

        // Filtrer les contrats avec status = 1
        $validContrats = array_filter($contrats, fn($c) => $c->getStatus() == 1);

        return [
            'prospects_count' => count($prospectsWithTeam),
            'contrats_count' => count($validContrats),
            'cotisation_total' => $this->calculateCotisationTotal($validContrats),
            'source' => [
                'saisir' => count(array_filter($prospectsWithTeam, fn($p) => $p->getSource() == 1)),
                'site_pub' => count(array_filter($prospectsWithTeam, fn($p) => $p->getSource() === null)),
                'revendeur' => count(array_filter($prospectsWithTeam, fn($p) => $p->getSource() == 2)),
            ],
            'type_prospect' => [
                'particulier' => count(array_filter($prospectsWithTeam, fn($p) => $p->getTypeProspect() == 1)),
                'professionnel' => count(array_filter($prospectsWithTeam, fn($p) => $p->getTypeProspect() == 2)),
            ],
            'motif_saise' => [
                1 => count(array_filter($prospectsWithTeam, fn($p) => $p->getMotifSaise() == 1)),
                2 => count(array_filter($prospectsWithTeam, fn($p) => $p->getMotifSaise() == 2)),
                3 => count(array_filter($prospectsWithTeam, fn($p) => $p->getMotifSaise() == 3)),
                4 => count(array_filter($prospectsWithTeam, fn($p) => $p->getMotifSaise() == 4)),
                5 => count(array_filter($prospectsWithTeam, fn($p) => $p->getMotifSaise() == 5)),
                6 => count(array_filter($prospectsWithTeam, fn($p) => $p->getMotifSaise() == 6)),
            ],
            'activites' => [
                1 => count(array_filter($prospectsWithTeam, fn($p) => $p->getActivites() == 1)),
                2 => count(array_filter($prospectsWithTeam, fn($p) => $p->getActivites() == 2)),
                3 => count(array_filter($prospectsWithTeam, fn($p) => $p->getActivites() == 3)),
                4 => count(array_filter($prospectsWithTeam, fn($p) => $p->getActivites() == 4)),
                5 => count(array_filter($prospectsWithTeam, fn($p) => $p->getActivites() == 5)),
                6 => count(array_filter($prospectsWithTeam, fn($p) => $p->getActivites() == 6)),
                7 => count(array_filter($prospectsWithTeam, fn($p) => $p->getActivites() == 7)),
                8 => count(array_filter($prospectsWithTeam, fn($p) => $p->getActivites() == 8)),
            ]
        ];
    }

    /**
     * Calcule les statistiques d'une équipe
     */
    private function calculateTeamStatistics($team, array $prospects, array $contrats): array
    {
        // Filtrer les prospects de cette équipe
        $teamProspects = array_filter($prospects, fn($p) => $p->getTeam() === $team);

        // Filtrer les contrats de cette équipe (status = 1)
        $teamContrats = array_filter($contrats, function ($c) use ($team) {
            return $c->getStatus() == 1 &&
                $c->getUser() !== null &&
                $team->getUsers()->contains($c->getUser());
        });

        return [
            'prospects_count' => count($teamProspects),
            'contrats_count' => count($teamContrats),
            'cotisation_total' => $this->calculateCotisationTotal($teamContrats),
            'source' => [
                'saisir' => count(array_filter($teamProspects, fn($p) => $p->getSource() == 1)),
                'site_pub' => count(array_filter($teamProspects, fn($p) => $p->getSource() === null)),
                'revendeur' => count(array_filter($teamProspects, fn($p) => $p->getSource() == 2)),
            ],
            'type_prospect' => [
                'particulier' => count(array_filter($teamProspects, fn($p) => $p->getTypeProspect() == 1)),
                'professionnel' => count(array_filter($teamProspects, fn($p) => $p->getTypeProspect() == 2)),
            ],
            'motif_saise' => [
                1 => count(array_filter($teamProspects, fn($p) => $p->getMotifSaise() == 1)),
                2 => count(array_filter($teamProspects, fn($p) => $p->getMotifSaise() == 2)),
                3 => count(array_filter($teamProspects, fn($p) => $p->getMotifSaise() == 3)),
                4 => count(array_filter($teamProspects, fn($p) => $p->getMotifSaise() == 4)),
                5 => count(array_filter($teamProspects, fn($p) => $p->getMotifSaise() == 5)),
                6 => count(array_filter($teamProspects, fn($p) => $p->getMotifSaise() == 6)),
            ],
            'activites' => [
                1 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 1)),
                2 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 2)),
                3 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 3)),
                4 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 4)),
                5 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 5)),
                6 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 6)),
                7 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 7)),
                8 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 8)),
                9 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 9)),
                10 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 10)),
                11 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 11)),
                12 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 12)),
                13 => count(array_filter($teamProspects, fn($p) => $p->getActivites() == 13)),
               
                
            ]
        ];
    }

    /**
     * Calcule les statistiques d'un utilisateur
     */
    private function calculateUserStatistics($user, array $prospects, array $contrats): array
    {
        // Filtrer les prospects de cet utilisateur
        $userProspects = array_filter($prospects, fn($p) => $p->getComrcl() === $user);

        // Filtrer les contrats de cet utilisateur (status = 1)
        $userContrats = array_filter($contrats, fn($c) => $c->getUser() === $user && $c->getStatus() == 1);

        return [
            'prospects_count' => count($userProspects),
            'contrats_count' => count($userContrats),
            'cotisation_total' => $this->calculateCotisationTotal($userContrats),
            'source' => [
                'saisir' => count(array_filter($userProspects, fn($p) => $p->getSource() == 1)),
                'site_pub' => count(array_filter($userProspects, fn($p) => $p->getSource() === null)),
                'revendeur' => count(array_filter($userProspects, fn($p) => $p->getSource() == 2)),
            ],
            'type_prospect' => [
                'particulier' => count(array_filter($userProspects, fn($p) => $p->getTypeProspect() == 1)),
                'professionnel' => count(array_filter($userProspects, fn($p) => $p->getTypeProspect() == 2)),
            ],
            'motif_saise' => [
                1 => count(array_filter($userProspects, fn($p) => $p->getMotifSaise() == 1)),
                2 => count(array_filter($userProspects, fn($p) => $p->getMotifSaise() == 2)),
                3 => count(array_filter($userProspects, fn($p) => $p->getMotifSaise() == 3)),
                4 => count(array_filter($userProspects, fn($p) => $p->getMotifSaise() == 4)),
                5 => count(array_filter($userProspects, fn($p) => $p->getMotifSaise() == 5)),
                6 => count(array_filter($userProspects, fn($p) => $p->getMotifSaise() == 6)),
            ],
            'activites' => [
                1 => count(array_filter($userProspects, fn($p) => $p->getActivites() == 1)),
                2 => count(array_filter($userProspects, fn($p) => $p->getActivites() == 2)),
                3 => count(array_filter($userProspects, fn($p) => $p->getActivites() == 3)),
                4 => count(array_filter($userProspects, fn($p) => $p->getActivites() == 4)),
                5 => count(array_filter($userProspects, fn($p) => $p->getActivites() == 5)),
                6 => count(array_filter($userProspects, fn($p) => $p->getActivites() == 6)),
                7 => count(array_filter($userProspects, fn($p) => $p->getActivites() == 7)),
                8 => count(array_filter($userProspects, fn($p) => $p->getActivites() == 8)),
            ]
        ];
    }

    /**
     * Calcule le total des cotisations
     */
    private function calculateCotisationTotal(array $contrats): float
    {
        return array_reduce($contrats, function ($carry, $contrat) {
            return $carry + ($contrat->getCotisation() ?? 0);
        }, 0);
    }
}
