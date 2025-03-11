<?php

namespace App\Controller;

use App\Form\SearchProspectType;
use App\Repository\ProspectRepository;
use App\Search\SearchProspect;
use App\Service\StatsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;


#[IsGranted('IS_AUTHENTICATED')]
#[Route('/traitement')]
final class TraitementController extends AbstractController
{
    public function __construct(

        private RequestStack $requestStack,
        private ProspectRepository $prospectRepository,
        private Security $security,
    ) {}


    #[Route('/', name: 'app_traitement')]
    public function index(StatsService $statsService): Response
    {
        $stats = $statsService->getStats();
        return $this->render('traitement/table.html.twig', [
            'stats'    => $stats
        ]);
    }

    #[Route('/newprospect', name: 'app_prospect_index', methods: ['GET', 'POST'])]
    public function newprospect(Request $request): Response
    {
        $data = new SearchProspect();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchProspectType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        $user = $this->security->getUser();
        $roles = $user->getRoles();
        $prospects = [];
        $duplicates = [
            'emails' => [],
            'phones' => [],
        ];

        if (in_array('ROLE_ADMIN', $roles, true) or in_array('ROLE_AFFECTALL', $roles, true)) {
            $prospects = $this->prospectRepository->findByAdminNewProsp($data);  // team null  
        } elseif (in_array('ROLE_CHEF', $roles, true)) {
            $prospects = $this->prospectRepository->findByChefNewProsp($data, $user);
        } else {
            $prospects = $this->prospectRepository->findByCmrclNewProsp($data, $user);
        }
        foreach ($prospects as $prospect) {
            $email = $prospect->getEmail();
            $phone = $prospect->getPhone(); // Assurez-vous que la méthode `getPhone()` existe.

            // Vérifier les doublons par email
            $existingEmailProspect = $this->prospectRepository->findOneBy(['email' => $email]);
            $isEmailDuplicate = $existingEmailProspect !== null && $existingEmailProspect->getId() !== $prospect->getId();
            $duplicates['emails'][$email] = $isEmailDuplicate;

            // Vérifier les doublons par téléphone
            if ($phone) { // Vérifier que le numéro de téléphone existe
                $existingPhoneProspect = $this->prospectRepository->findOneBy(['phone' => $phone]);
                $isPhoneDuplicate = $existingPhoneProspect !== null && $existingPhoneProspect->getId() !== $prospect->getId();
                $duplicates['phones'][$phone] = $isPhoneDuplicate;
            }
        }
        return $this->render('prospect/index.html.twig', [
            'duplicates' => $duplicates,
            'prospects' => $prospects,
            'search_form' => $form->createView()
        ]);
    }
    // afficher les nouveaux prospects 
    #[Route('/newprospectchef', name: 'newprospectchef_index', methods: ['GET', 'POST'])]

    public function newprospectchef(Request $request): Response

    {
        // $this->denyAccessUnlessGrantedAuthorizedRoles();

        $data = new SearchProspect();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchProspectType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());



        $user = $this->security->getUser();

        $prospects = [];


        $prospects = $this->prospectRepository->findByCmrclNewProsp($data, $user);



        return $this->render('prospect/index.html.twig', [
            'prospects' => $prospects,
            // 'duplicates' => $duplicates,
            'search_form' => $form->createView()
        ]);
    }
    /**
     * afficher les prospect no traiter   
     */
    #[Route('/notrait', name: 'notrait_index', methods: ['GET', 'POST'])]

    public function notrait(Request $request): Response

    {

        $data = new SearchProspect();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchProspectType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());


        $user = $this->security->getUser();
        $roles = $user->getRoles();

        $prospect = [];


        if (in_array('ROLE_SUPER_ADMIN', $roles, true) || in_array('ROLE_ADMIN', $roles, true)) {
            // admi peut voire toutes les no traite
            $prospect =  $this->prospectRepository->findProspectNonTraiter($data, null);
        } else if (in_array('ROLE_CHEF', $roles, true)) {
            // chef peut voire toutes les no traite atacher a leur equipe
            $prospect =  $this->prospectRepository->findProspectNonTraiterChef($data, $user, null);
        } else {
            // cmrcl peut voire seulement les no traite  atacher a lui
            $prospect =  $this->prospectRepository->findProspectNonTraiterCmrcl($data, $user, null);
        }



        return $this->render('prospect/index.html.twig', [
            'prospects' => $prospect,
            'search_form' => $form->createView()
        ]);
    }


    #[Route('/search', name: 'prospect_search', methods: ['GET'])]
    public function search(Request $request): Response
    {

        $data = new SearchProspect();
        $data->page = $request->get('page', 1);

        $form = $this->createForm(SearchProspectType::class, $data);
        $form->handleRequest($request);

        $user = $this->security->getUser();
        $roles = $user->getRoles();
        $prospect = [];



        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {

            if (in_array('ROLE_SUPER_ADMIN',  $roles, true) || in_array('ROLE_ADMIN',  $roles, true)) {
                // admi peut chercher toutes les prospects
                $prospect = $this->prospectRepository->findSearch($data, $user);
            } else if (in_array('ROLE_CHEF',  $roles, true)) {
                // chef peut chercher toutes les prospects atacher a leur equipe
                $prospect = $this->prospectRepository->findAllChefSearch($data, $user);
            } elseif (in_array('ROLE_USER',  $roles, true)) {
                // cmrcl peut chercher seulement les prospects atacher a lui
                $prospect = $this->prospectRepository->findByUserAffecterCmrcl($data, $user);
            }

            return $this->render('prospect/index.html.twig', [
                'prospects' => $prospect,
                'search_form' => $form->createView()
            ]);
        }
        return $this->render('prospect/search.html.twig', [
            'prospects' => $prospect,
            'search_form' => $form->createView(),
        ]);
    }

    /**
     * afficher les relance du jour 
     */
    #[Route('/relancejour', name: 'relancejour_index', methods: ['GET', 'POST'])]

    public function relancejour(Request $request,): Response

    {

        $data = new SearchProspect();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchProspectType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        $user = $this->security->getUser();
        $roles = $user->getRoles();

        $prospect = [];


        if (in_array('ROLE_SUPER_ADMIN',  $roles, true) || in_array('ROLE_ADMIN',  $roles, true)) {
            // admi peut voire toutes les relance du jour
            $prospect =  $this->prospectRepository->findRelancedJour($data, null);
        } else if (in_array('ROLE_CHEF',  $roles, true)) {
            // chef peut voire toutes les relance du jour atacher a leur equipe
            $prospect =  $this->prospectRepository->findRelancedJourChef($data, $user, null);
        } else {
            // cmrcl peut voire seulement les relance du jour  atacher a lui
            $prospect =  $this->prospectRepository->findRelancedJourCmrcl($data, $user, null);
        }


        return $this->render('prospect/index.html.twig', [
            'prospects' => $prospect,

            'now' => new \DateTime('+1 hour'),

            'search_form' => $form->createView()
        ]);
    }

    /**
     * les Relances à venir 
     */
    #[Route('/avenir', name: 'avenir_index', methods: ['GET', 'POST'])]
    public function avenir(Request $request,    Security $security): Response

    {

        $data = new SearchProspect();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchProspectType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        $user = $this->security->getUser();
        $roles = $user->getRoles();


        $prospect = [];
        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {

            $user = $security->getUser();
            if (in_array('ROLE_SUPER_ADMIN', $roles, true) || in_array('ROLE_ADMIN', $roles, true)) {

                $prospect =  $this->prospectRepository->findAvenir($data, null);
            } else if (in_array('ROLE_CHEF', $roles, true)) {

                $prospect =  $this->prospectRepository->findAvenirChef($data, $user, null);
            } else {

                $prospect =  $this->prospectRepository->findAvenirCmrcl($data, $user, null);
            }


            return $this->render('prospect/index.html.twig', [
                'prospects' => $prospect,
                'search_form' => $form->createView()
            ]);
        }


        return $this->render('prospect/search.html.twig', [
            'prospects' => $prospect,
            'search_form' => $form->createView(),
        ]);
    }


    /**
     * afficher les relances no traiter  
     */
    #[Route('/relancenotraite', name: 'relancenotraite_index', methods: ['GET', 'POST'])]
    public function relancenotraite(Request $request): Response

    {
        // $this->denyAccessUnlessGrantedAuthorizedRoles();

        $data = new SearchProspect();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchProspectType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        $user = $this->security->getUser();
        $roles = $user->getRoles();
        $prospect = [];

        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {
            if (in_array('ROLE_SUPER_ADMIN', $roles, true) || in_array('ROLE_ADMIN', $roles, true)) {

                $prospect =  $this->prospectRepository->findRelancesNonTraitees($data, null);

                // dd(count($prospect));
            } else if (in_array('ROLE_CHEF', $roles, true)) {

                $prospect =   $this->prospectRepository->RelancesNonTraiteesChef($data, $user, null);
            } else {

                $prospect =   $this->prospectRepository->RelancesNonTraiteesCmrcl($data, $user, null);
            }


            return $this->render('prospect/index.html.twig', [
                'prospects' => $prospect,
                'search_form' => $form->createView()
            ]);
        }
        return $this->render('prospect/search.html.twig', [
            'prospects' => $prospect,
            'search_form' => $form->createView(),
        ]);
    }


    /**
     * afficher les prospects injoiniable  
     * 
     */
    #[Route('/unjoinable', name: 'app_unjoinable', methods: ['GET', 'POST'])]

    public function unjoinable(Request $request): Response

    {
        // $this->denyAccessUnlessGrantedAuthorizedRoles();

        $data = new SearchProspect();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchProspectType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        $user = $this->security->getUser();
        $roles = $user->getRoles();
        $prospect = [];


        if (in_array('ROLE_SUPER_ADMIN', $roles, true) || in_array('ROLE_ADMIN', $roles, true)) {

            $prospect =  $this->prospectRepository->findUnjoing($data, null);
        } else if (in_array('ROLE_CHEF', $roles, true)) {

            $prospect =  $this->prospectRepository->findUnjoingChef($data,  $user, null);
        } else {

            $prospect =  $this->prospectRepository->findUnjoingCmrcl($data, $user, null);
        }


        return $this->render('prospect/index.html.twig', [
            'prospects' => $prospect,
            'search_form' => $form->createView()
        ]);
    }
}
