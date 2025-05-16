<?php

namespace App\Controller;

use App\Form\SearchDateClaudType;
use App\Form\SearchDateType;
use App\Form\SearchFilterType;
use App\Repository\CompartenaireRepository;
use App\Repository\ContratRepository;
use App\Repository\ProductRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use StatsCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
final class StatController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private CompartenaireRepository $compartenaireRepository,
        private ContratRepository $contratRepository,
        private \App\Repository\ProspectRepository $prospectRepository,
        private \App\Repository\TeamRepository $teamRepository,
        private ProductRepository $productRepository,
        private \Symfony\Component\HttpFoundation\RequestStack $requestStack
    ) {}


    #[Route('/stat', name: 'app_stat')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllWithProspects();
        $stats = [];

        foreach ($users as $user) {
            // Initialize counts with all possible values
            $counts = ['2' => 0, '3' => 0, 'null' => 0];
            $motifs = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, 'null' => 0];
            $partenaireIds = [];

            foreach ($user->getProspects() as $prospect) {
                $source = $prospect->getSource();
                $motif = $prospect->getMotifSaise();

                // Count sources
                if (in_array($source, ['2', '3'])) {
                    $counts[$source]++;
                } elseif ($source === null) {
                    $counts['null']++;
                }

                // Count motifs - fixed variable name from $motifs to $motif
                if ($motif !== null && in_array($motif, ['1', '2', '3', '4', '5', '6'])) {
                    $motifs[$motif]++;
                } elseif ($motif === null) {
                    $motifs['null']++;
                }
            }
            // Parcourir ses contrats pour compter les partenaires uniques
            foreach ($user->getContrats() as $contrat) {
                if ($contrat->getPartenaire()) {
                    $partenaireIds[$contrat->getPartenaire()->getId()] = true;
                }
            }
            $stats[] = [
                'user' => $user,
                'total' => count($user->getProspects()),
                'contrat' => count($user->getContrats()),
                'partenaire' => count($partenaireIds),

                'sources' => $counts,
                'motifs' => $motifs,
            ];
        }

        return $this->render('stat/index.html.twig', [
            'stats' => $stats,
        ]);
    }

    #[Route('/calendrie', name: 'prospects_calandri')]
    public function prospectsCalendrie(): Response
    {

        $form = $this->createForm(SearchDateType::class);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        $contrats = [];
        $teams = $this->teamRepository->findAll();
        $prospects = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $form->get('startDate')->getData();
            $endDate = $form->get('endDate')->getData();

            if (!$startDate instanceof \DateTimeInterface || !$endDate instanceof \DateTimeInterface) {
                throw new \InvalidArgumentException('Les dates doivent être des instances de DateTimeInterface');
            }


            $prospects = $this->prospectRepository->findByDateInterval($startDate, $endDate);
            $contrats = $this->contratRepository->findByDateInterval($startDate, $endDate);
        } else {
            // Sinon, affichez tous les prospects
            $prospects = [];
            $contrats = [];
        }



        return $this->render('stat/calendrie.html.twig', [
            // 'calendrie' => $calendrie,
            'prospects' => $prospects,
            'teams' => $teams,
            'contrats' => $contrats,

            'search_form' => $form->createView()
        ]);
    }

    #[Route('/partenaire', name: 'app_stat_partenaire')]
    public function table(
        CompartenaireRepository $compartenaireRepository,
        Request $request
    ): Response {

        $form = $this->createForm(SearchDateType::class);
        $form->handleRequest($request);

        $stats = [];
        $users = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $form->get('startDate')->getData();
            $endDate = $form->get('endDate')->getData();

            $partenaires = $compartenaireRepository->findAll();

            foreach ($partenaires as $partenaire) {
                $contrats = $partenaire->getContrats();
                $nbContrats = 0;
                $cotisationTotal = 0;
                $chiffreAffaire = 0;
                $prospectIds = [];

                foreach ($contrats as $contrat) {
                    $dateSuspension = $contrat->getDateSuspension();
                    $client = $contrat->getClient();
                    $prospect = $client?->getProspect();
                    $creatAt = $prospect?->getCreatAt();

                    $matchDate = false;

                    if (
                        ($startDate && $endDate) &&
                        (
                            ($dateSuspension && $dateSuspension >= $startDate && $dateSuspension <= $endDate) ||
                            ($creatAt && $creatAt >= $startDate && $creatAt <= $endDate)
                        )
                    ) {
                        $matchDate = true;
                        $user = $contrat->getUser();
                        if ($user) {
                            $users[$user->getId()] = $user;
                        }
                    }

                    if ($matchDate) {
                        $nbContrats++;
                        if ($contrat->getCotisation() !== null) {
                            $cotisationTotal += (float)$contrat->getCotisation();
                        }

                        $payment = $contrat->getPayments();
                        if ($payment && $payment->getFrais() !== null) {
                            $chiffreAffaire += (float) $payment->getFrais();
                        }

                        if ($prospect) {
                            $prospectIds[$prospect->getId()] = true;
                        }
                    }
                }

                $stats[] = [
                    'partenaire' => $partenaire,
                    'nbContrats' => $nbContrats,
                    'cotisationTotal' => $cotisationTotal,
                    'nbProspects' => count($prospectIds),
                    'ca' => $chiffreAffaire,
                    'users' => array_values($users),
                ];
            }
        }

        return $this->render('stat/partenaire.html.twig', [
            'stats' => $stats,
            'search_form' => $form->createView(),
        ]);
    }



    #[Route('/partenaires', name: 'app_stat_partenaires')]
    public function partenaires(CompartenaireRepository $compartenaireRepository): Response
    {
        $partenaires = $compartenaireRepository->countContratsByPartenaire();

        return $this->render('stat/partenaires.html.twig', [
            'partenaires' => $partenaires,
        ]);
    }

    #[Route('/usercontrat', name: 'app_usercontrat')]
    public function usercontrat(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('stat/usercontrat.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/usercontratgpt', name: 'app_usercontrattes')]
    public function usercontrattest(UserRepository $userRepository, ContratRepository $contratRepository): Response
    {
        $users = $userRepository->findAll();

        $data = [];

        foreach ($users as $user) {
            $contrats = $user->getContrats();

            $partenaireData = [];

            foreach ($contrats as $contrat) {
                $partenaireName = $contrat->getPartenaire()?->getNom() ?? 'Inconnu';

                if (!isset($partenaireData[$partenaireName])) {
                    $partenaireData[$partenaireName] = [
                        'nombre_contrats' => 0,
                        'total_cotisation' => 0,
                    ];
                }

                $partenaireData[$partenaireName]['nombre_contrats']++;
                $partenaireData[$partenaireName]['total_cotisation'] += (float) $contrat->getCotisation();
            }

            $data[] = [
                'user' => $user,
                'partenaires' => $partenaireData,
            ];
        }

        return $this->render('stat/usercontratgpt.html.twig', [
            'data' => $data,
        ]);
    }
    #[Route('/usercontratdeep', name: 'app_usercontratdeep')]
    public function usercontratdeep(UserRepository $userRepository, CompartenaireRepository $compartenaireRepository): Response

    {
        // Récupérer tous les partenaires existants
        $allPartenaires = $compartenaireRepository->findAll();

        // Créer une structure de données pour les utilisateurs
        $usersData = [];
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            $userStats = [];

            // Initialiser les stats à 0 pour tous les partenaires
            foreach ($allPartenaires as $p) {
                $userStats[$p->getNom()] = [
                    'count' => 0,
                    'cotisation' => 0.0
                ];
            }

            // Calculer les stats réelles
            foreach ($user->getContrats() as $contrat) {
                $partenaire = $contrat->getPartenaire();
                if ($partenaire) {
                    $nom = $partenaire->getNom();
                    $userStats[$nom]['count']++;
                    $userStats[$nom]['cotisation'] += (float)$contrat->getCotisation();
                }
            }

            $usersData[] = [
                'user' => $user,
                'stats' => $userStats
            ];
        }

        return $this->render('stat/usercontratdeep.html.twig', [
            'usersData' => $usersData,
            'allPartenaires' => $allPartenaires
        ]);
    }

    #[Route('/usercontratdeep2', name: 'app_usercontratdeep2')]
    public function usercontratdeep2(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        $data = [];

        foreach ($users as $user) {
            foreach ($user->getTeams() as $team) {
                $teamName = $team->getName();

                if (!isset($data[$teamName])) {
                    $data[$teamName] = [];
                }

                $contrats = $user->getContrats();
                $partenaireData = [];

                foreach ($contrats as $contrat) {
                    $partenaireName = $contrat->getPartenaire()?->getNom() ?? 'Inconnu';

                    if (!isset($partenaireData[$partenaireName])) {
                        $partenaireData[$partenaireName] = [
                            'nombre_contrats' => 0,
                            'total_cotisation' => 0,
                        ];
                    }

                    $partenaireData[$partenaireName]['nombre_contrats']++;
                    $partenaireData[$partenaireName]['total_cotisation'] += (float) $contrat->getCotisation();
                }

                $data[$teamName][] = [
                    'user' => $user,
                    'partenaires' => $partenaireData,
                ];
            }
        }

        return $this->render('stat/usercontratdeep2.html.twig', [
            'data' => $data,
        ]);
    }

    // Controller
    #[Route('/usercontratdeep3', name: 'app_usercontratdeep3')]
    public function usercontratdeep3(TeamRepository $teamRepository): Response
    {
        $teams = $teamRepository->findAll();

        $teamData = [];
        foreach ($teams as $team) {
            $usersData = [];
            $totalRows = 0;

            foreach ($team->getUsers() as $user) {
                $partenaires = [];

                foreach ($user->getContrats() as $contrat) {
                    $partenaire = $contrat->getPartenaire();
                    if ($partenaire) {
                        $nom = $partenaire->getNom();
                        $partenaires[$nom] ??= ['count' => 0, 'cotisation' => 0.0];
                        $partenaires[$nom]['count']++;
                        $partenaires[$nom]['cotisation'] += (float)$contrat->getCotisation();
                    }
                }

                ksort($partenaires);
                $usersData[] = [
                    'user' => $user,
                    'partenaires' => $partenaires,
                    'rowspan' => count($partenaires)
                ];
                $totalRows += count($partenaires);
            }

            $teamData[] = [
                'team' => $team,
                'users' => $usersData,
                'totalRows' => $totalRows
            ];
        }

        return $this->render('stat/usercontratdeep3.html.twig', [
            'teamData' => $teamData
        ]);
    }


    #[Route('/usercontratfiltre', name: 'app_usercontratfiltre')]
    public function usercontratfilter(
        Request $request,
        TeamRepository $teamRepository,
        CompartenaireRepository $compartenaireRepository,
        UserRepository $userRepository
    ): Response {
        // Récupération des paramètres
        $teamId = $request->query->get('team');
        $userId = $request->query->get('user');
        $partenaireId = $request->query->get('partenaire');
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        // Récupération des données pour le formulaire
        $allTeams = $teamRepository->findAll();
        $allPartenaires = $compartenaireRepository->findAll();
        $filteredUsers = $userRepository->findAll();
        $teamData = [];

        // Vérifier si le formulaire a été soumis (présence d'au moins un paramètre de filtre)
        $formSubmitted = $request->query->count() > 0;

        if ($formSubmitted) {
            // Initialisation des objets
            $selectedTeam = $teamId ? $teamRepository->find($teamId) : null;
            $selectedUser = $userId ? $userRepository->find($userId) : null;
            $selectedPartenaire = $partenaireId ? $compartenaireRepository->find($partenaireId) : null;

            // Mise à jour des utilisateurs filtrés si une équipe est sélectionnée
            if ($selectedTeam) {
                $filteredUsers = $selectedTeam->getUsers();
            }

            // Configuration des dates
            $startDateObj = $startDate ? \DateTime::createFromFormat('Y-m-d', $startDate) : null;
            $endDateObj = $endDate ? \DateTime::createFromFormat('Y-m-d', $endDate) : null;
            if ($endDateObj) $endDateObj->setTime(23, 59, 59);

            // Récupération des données
            $teamsToProcess = $selectedTeam ? [$selectedTeam] : $allTeams;

            $teamData = [];
            foreach ($teamsToProcess as $team) {
                $usersData = [];
                $totalRows = 0;

                foreach ($team->getUsers() as $user) {
                    // Filtre utilisateur
                    if ($selectedUser && $user->getId() !== $selectedUser->getId()) continue;

                    $partenaires = [];
                    foreach ($user->getContrats() as $contrat) {
                        // Filtres contrat
                        if ($startDateObj && $contrat->getDateSouscrpt() < $startDateObj) continue;
                        if ($endDateObj && $contrat->getDateSouscrpt() > $endDateObj) continue;
                        if ($selectedPartenaire && $contrat->getPartenaire() !== $selectedPartenaire) continue;

                        $partenaire = $contrat->getPartenaire();
                        if ($partenaire) {
                            $nom = $partenaire->getNom();
                            $partenaires[$nom] ??= ['count' => 0, 'cotisation' => 0.0];
                            $partenaires[$nom]['count']++;
                            $partenaires[$nom]['cotisation'] += (float)$contrat->getCotisation();
                        }
                    }

                    if (!empty($partenaires)) {
                        ksort($partenaires);
                        $usersData[] = [
                            'user' => $user,
                            'partenaires' => $partenaires,
                            'rowspan' => count($partenaires)
                        ];
                        $totalRows += count($partenaires);
                    }
                }

                if (!empty($usersData)) {
                    $teamData[] = [
                        'team' => $team,
                        'users' => $usersData,
                        'totalRows' => $totalRows
                    ];
                }
            }
        }

        return $this->render('stat/usercontratdeepfiltre.html.twig', [
            'teamData' => $teamData,
            'allTeams' => $allTeams,
            'allPartenaires' => $allPartenaires,
            'filteredUsers' => $filteredUsers,
            'selectedTeam' => $selectedTeam ?? null,
            'selectedUser' => $selectedUser ?? null,
            'selectedPartenaire' => $selectedPartenaire ?? null,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'formSubmitted' => $formSubmitted
        ]);
    }

    #[Route('/usercontratfiter2', name: 'app_usercontratfilter2')]
    public function usercontratfilter2(
        Request $request,
        TeamRepository $teamRepository,
    ): Response {
        $form = $this->createForm(SearchFilterType::class);
        $form->handleRequest($request);

        // Initialisation des filtres avec des valeurs par défaut
        $filters = $form->getData() ?? [];

        // Récupération sécurisée des valeurs
        $selectedTeam = $filters['team'] ?? null;
        $selectedUser = $filters['user'] ?? null;
        $selectedPartenaire = $filters['partenaire'] ?? null;
        $startDate = $filters['startDate'] ?? null;
        $endDate = $filters['endDate'] ?? null;

        // Configuration de la date de fin
        if ($endDate instanceof \DateTime) {
            $endDate->setTime(23, 59, 59);
        }

        // Récupération des données
        $teams = $teamRepository->findFiltered(
            $selectedTeam,
            $selectedUser,
            $selectedPartenaire,
            $startDate,
            $endDate
        );

        return $this->render('stat/usercontratfilter2.html.twig', [
            'teamData' => $teams,
            'form' => $form->createView()
        ]);
    }

    #[Route('/usercontratform', name: 'app_usercontratform')]
    public function usercontratform(Request $request, TeamRepository $teamRepository): Response
    {
        $form = $this->createForm(SearchFilterType::class);
        $form->handleRequest($request);

        $teamData = [];

        // Modification ici : Vérifier la soumission sans la validation
        if ($form->isSubmitted()) {
            $filters = $form->getData();

            $teams = $teamRepository->findFiltered(

                $filters['team'],
                $filters['user'],
                $filters['partenaire'],
                $filters['startDate'],
                $filters['endDate']
            );
        }
        dd($teams, $teamData);
        foreach ($teams as $team) {
            $usersData = [];
            $totalRows = 0;

            foreach ($team->getUsers() as $user) {
                // Ajouter le chargement explicite des contrats
                $user->getContrats()->initialize();

                $partenaires = $user->getPartenairesStats();

                if (!empty($partenaires)) {
                    $usersData[] = [
                        'user' => $user,
                        'partenaires' => $partenaires,
                        'rowspan' => count($partenaires)
                    ];
                    $totalRows += count($partenaires);
                }
            }

            if (!empty($usersData)) {
                $teamData[] = [
                    'team' => $team,
                    'users' => $usersData,
                    'totalRows' => $totalRows
                ];
            }
        }

        return $this->render('stat/usercontratdeepform.html.twig', [
            'teamData' => $teamData,

            'form' => $form->createView()
        ]);
    }
    #[Route('/statistique', name: 'app_statistique')]
    public function statistique()
    {
        return $this->render('stat/statistique.html.twig');
    }


    // claud soulition :
    #[Route('/search', name: 'app_search_dates')]
    public function searchDates(Request $request): Response
    {
        $form = $this->createForm(SearchDateClaudType::class);
        $form->handleRequest($request);

        $showButtons = false;
        $startDate = null;
        $endDate = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $form->get('startDate')->getData();
            $endDate = $form->get('endDate')->getData();

            if ($startDate instanceof \DateTimeInterface && $endDate instanceof \DateTimeInterface) {
                $showButtons = true;
            }
        }

        return $this->render('stat/searchclaud.html.twig', [
            'search_form' => $form->createView(),
            'showButtons' => $showButtons,
            'startDate' => $startDate ? $startDate->format('Y-m-d') : null,
            'endDate' => $endDate ? $endDate->format('Y-m-d') : null,
        ]);
    }

    #[Route('/prospects', name: 'app_prospects')]
    public function prospects(Request $request): Response
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        $startDateObj = $startDate ? new \DateTime($startDate) : null;
        $endDateObj = $endDate ? new \DateTime($endDate) : null;

        $prospects = [];
        $teams = $this->teamRepository->findAll();

        if ($startDateObj && $endDateObj) {
            $prospects = $this->prospectRepository->findByDateIntervalClaud($startDateObj, $endDateObj);
        }

        return $this->render('stat/prospects.html.twig', [
            'prospects' => $prospects,
            'teams' => $teams,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    #[Route('/contrats', name: 'app_contrats')]
    public function contrats(Request $request, CompartenaireRepository $partenaireRepository): Response
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $commercialId = $request->query->get('commercial');
        $partenaireNom = $request->query->get('partenaire');
        $teamId = $request->query->get('team'); // Nouveau paramètre pour le filtre par équipe

        $startDateObj = $startDate ? new \DateTime($startDate) : null;
        $endDateObj = $endDate ? new \DateTime($endDate) : null;

        $contrats = [];
        $commercialsData = [];
        $totalRows = 0;
        $teams = $this->teamRepository->findAll();

        // Récupération des listes de commerciaux et partenaires pour les filtres
        $commercials = $this->userRepository->findAll();
        $partenaires = $partenaireRepository->findAll();

        if ($startDateObj && $endDateObj) {
            $result = $this->contratRepository->findByDateIntervalGroupedByCommercialAndPartenaire(
                $startDateObj,
                $endDateObj,
                $commercialId,
                $partenaireNom,
                $teamId
            );
            $commercialsData = $result['commercialsData'];
            $totalRows = $result['totalRows'];
            $contrats = $this->contratRepository->findByDateIntervalCalud($startDateObj, $endDateObj, $commercialId, $partenaireNom, $teamId);
        }

        return $this->render('stat/contrats.html.twig', [
            'contrats' => $contrats,
            'commercialsData' => $commercialsData,
            'totalRows' => $totalRows,
            'teams' => $teams,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'commercials' => $commercials,
            'partenaires' => $partenaires,
            'selectedCommercial' => $commercialId,
            'selectedPartenaire' => $partenaireNom,
            'selectedTeam' => $teamId,
        ]);
    }

    #[Route('/prospectsgpt', name: 'app_prospectsgpt')]
    public function prospectsgpt(Request $request): Response
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $teamId = $request->query->get('team');
        $comrclId = $request->query->get('comrcl');
        $productId = $request->query->get('product');
        $url = $request->query->get('url');
        $activites = $request->query->get('activites');
        $typeProspect = $request->query->get('typeProspect');

        $startDateObj = $startDate ? new \DateTime($startDate) : null;
        $endDateObj = $endDate ? new \DateTime($endDate) : null;

        $prospects = [];
        $teams = $this->teamRepository->findAll();
        $products = $this->productRepository->findAll();
        $comrcls = $this->userRepository->findAll();

        if ($startDateObj && $endDateObj) {
            $prospects = $this->prospectRepository->findByFilters(
                $startDateObj,
                $endDateObj,
                $teamId,
                $comrclId,
                $productId,
                $url,
                $activites,
                $typeProspect
            );
        }

        return $this->render('stat/prospectsgpt.html.twig', [
            'prospects' => $prospects,
            'teams' => $teams,
            'products' => $products,
            'comrcls' => $comrcls,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedTeam' => $teamId,
            'selectedComrcl' => $comrclId,
            'selectedProduct' => $productId,
            'selectedUrl' => $url,
            'selectedActivites' => $activites,
            'selectedTypeProspect' => $typeProspect,
        ]);
    }
    #[Route('/prospectsdeep/{page<\d+>}', name: 'app_prospectsdeep', defaults: ['page' => 1])]
    public function prospectsdeep(Request $request, PaginatorInterface $paginator, int $page = 1): Response
    {
        // Récupération des filtres
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $teamFilter = $request->query->get('team');
        $comrclFilter = $request->query->get('comrcl');
        $productFilter = $request->query->get('product');
        $urlFilter = $request->query->get('url');
        $activitesFilter = $request->query->get('activites');
        $typeProspectFilter = $request->query->get('typeProspect');

        // Traitement des dates
        $startDateObj = $startDate ? new \DateTime($startDate) : null;
        $endDateObj = $endDate ? new \DateTime($endDate) : null;
        if ($endDateObj) {
            $endDateObj->setTime(23, 59, 59);
        }

        // Récupération des données pour les filtres
        $teams = $this->teamRepository->findAll();
        $comrcls = $this->userRepository->findAll(); // À créer dans UserRepository
        $products = $this->productRepository->findAll();

        // Récupération de la requête de prospects filtrés au lieu des résultats directs
        $prospectsQuery = $this->prospectRepository->createFilteredQuerydeep(
            $startDateObj,
            $endDateObj,
            $teamFilter,
            $comrclFilter,
            $productFilter,
            $urlFilter,
            $activitesFilter,
            $typeProspectFilter
        );

        // Pagination des résultats avec 10 éléments par page
        $pagination = $paginator->paginate(
            $prospectsQuery,     // Query à paginer, pas les résultats
            $page,               // Numéro de page
            10                   // Nombre d'éléments par page
        );

        return $this->render('stat/prospectsdeep.html.twig', [
            'pagination' => $pagination,  // Résultats paginés
            'teams' => $teams,
            'comrcls' => $comrcls,
            'products' => $products,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedTeam' => $teamFilter,
            'selectedComrcl' => $comrclFilter,
            'selectedProduct' => $productFilter,
            'selectedUrl' => $urlFilter,
            'selectedActivites' => $activitesFilter,
            'selectedTypeProspect' => $typeProspectFilter,
        ]);
    }
}
