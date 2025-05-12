<?php

namespace App\Controller;


use App\Form\SearchDateType;
use App\Repository\CompartenaireRepository;
use App\Repository\ContratRepository;
use App\Repository\UserRepository;
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

            $stats[] = [
                'user' => $user,
                'total' => count($user->getProspects()),
                'contrat' => count($user->getContrats()),
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
}
