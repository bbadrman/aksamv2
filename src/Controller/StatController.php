<?php

namespace App\Controller;

use App\Form\SearchDateClaudType;
use App\Form\SearchDateType;
use App\Repository\CompartenaireRepository;
use App\Repository\ContratRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
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





    #[Route('/calendrie', name: 'prospects_calandri')]
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
                // Dates URL invalides, ignorer
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
            // Formulaire soumis = priorité absolue
            $searchStartDate = $form->get('startDate')->getData();
            $searchEndDate = $form->get('endDate')->getData();
            $shouldExecuteSearch = true;
        } elseif ($urlStartDate && $urlEndDate) {
            // Dates dans l'URL = exécution automatique
            try {
                $searchStartDate = new \DateTime($urlStartDate);
                $searchEndDate = new \DateTime($urlEndDate);
                $shouldExecuteSearch = true;
            } catch (\Exception $e) {
                // Dates invalides, pas d'exécution
            }
        }

        // Exécution de la recherche
        if ($shouldExecuteSearch && $searchStartDate && $searchEndDate) {
            if (!$searchStartDate instanceof \DateTimeInterface || !$searchEndDate instanceof \DateTimeInterface) {
                throw new \InvalidArgumentException('Les dates doivent être des instances de DateTimeInterface');
            }



            $prospects = $this->prospectRepository->findByDateInterval($searchStartDate, $searchEndDate);
            $contrats = $this->contratRepository->findByDateInterval($searchStartDate, $searchEndDate);
            $searchExecuted = true;
        }

        return $this->render('stat/calendrie.html.twig', [
            'prospects' => $prospects,
            'teams' => $teams,
            'contrats' => $contrats,
            'search_form' => $form->createView(),
            'searchExecuted' => $searchExecuted,
            'searchStartDate' => $searchStartDate,
            'searchEndDate' => $searchEndDate,
            'fromUrl' => ($urlStartDate && $urlEndDate && !$form->isSubmitted()),
        ]);
    }

    // interface pour lance filter :
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

        return $this->render('stat/searchproduction.html.twig', [
            'search_form' => $form->createView(),
            'showButtons' => $showButtons,
            'startDate' => $startDate ? $startDate->format('Y-m-d') : null,
            'endDate' => $endDate ? $endDate->format('Y-m-d') : null,
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

    #[Route('/prospectprod/{page<\d+>}', name: 'app_prospectstats', defaults: ['page' => 1])]
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
        $source = $request->query->get('source');
        $relance = $request->query->all('relance');

        // Déterminer si le formulaire a été soumis
        $formSubmitted = $request->query->count() > 0;

        // Vérifier si le champ obligatoire (source) est rempli
        $requiredFieldsFilled = true;
        if ($formSubmitted) {
            $requiredFieldsFilled = !empty($source);
        }

        // S'assurer que relanceExclude est un tableau
        if (!is_array($relance)) {
            $relance = [$relance];
        }
        // Récupération des données pour les filtres
        $teams = $this->teamRepository->findAll();
        $comrcls = $this->userRepository->findAll(); // À créer dans UserRepository
        $products = $this->productRepository->findAll();

        $pagination = null;
        $errorMessage = null;

        // Si le formulaire a été soumis et que le champ obligatoire est rempli
        if ($formSubmitted && $requiredFieldsFilled) {
            // Traitement des dates
            $startDateObj = $startDate ? new \DateTime($startDate) : null;
            $endDateObj = $endDate ? new \DateTime($endDate) : null;
            if ($endDateObj) {
                $endDateObj->setTime(23, 59, 59);
            }

            // Récupération de la requête de prospects filtrés
            $prospectsQuery = $this->prospectRepository->createFilteredQuerydeep(
                $startDateObj,
                $endDateObj,
                $teamFilter,
                $comrclFilter,
                $productFilter,
                $urlFilter,
                $activitesFilter,
                $typeProspectFilter,
                $source,
                $relance
            );

            // Pagination des résultats avec 10 éléments par page
            $pagination = $paginator->paginate(
                $prospectsQuery,     // Query à paginer, pas les résultats
                $page,               // Numéro de page
                10                   // Nombre d'éléments par page
            );
        } elseif ($formSubmitted && !$requiredFieldsFilled) {
            // Message d'erreur si le champ obligatoire n'est pas rempli
            $errorMessage = "Le champ Source est obligatoire.";
        }

        return $this->render('stat/prospectprod.html.twig', [
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
            'selectedSource' => $source,
            'selectedRelance' => $relance,
            'formSubmitted' => $formSubmitted,
            'errorMessage' => $errorMessage,
        ]);
    }
}
