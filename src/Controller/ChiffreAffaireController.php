<?php

namespace App\Controller;

use App\Entity\Sav;
use App\Form\SearchContratCldrType;
use App\Repository\ClientRepository;
use App\Repository\ContratRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Search\SearchContartCalendrie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ChiffreAffaireController extends AbstractController
{


    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private Security $security,
        private ClientRepository $clientRepository,
        private ContratRepository $contratRepository,
        private UserRepository $userRepository,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {}



    #[Route('/afficherContrats', name: 'contratstat_mont', methods: ['GET'])]
    public function prospectsContratMont(): Response
    {

        $totalFrais = $this->contratRepository->getTotalFrais();

        $contratsParComrcl = $this->contratRepository->countContratsAndTotalFraisByComrclForThisMonth();
        $totauxMois = $this->contratRepository->getTotalContratsAndFraisForThisMonth();

        return $this->render('contrat/statmoinscontrat.html.twig', [
            'totalFrais' => $totalFrais,
            'contratsParComrcl' => $contratsParComrcl,
            'totauxMois' => $totauxMois,
            'contrats' => $this->contratRepository->findAll(),


        ]);
    }

    #[Route('/contratname', name: 'contratstatname_mont', methods: ['GET'])]
    public function chiffrContratMont(): Response
    {

        $contrats = $this->contratRepository->findAll();

        $groupedByUser = [];

        foreach ($contrats as $contrat) {
            $user = $contrat->getUser();
            $username = $user ? $user->getUsername() : 'Inconnu';

            if (!isset($groupedByUser[$username])) {
                $groupedByUser[$username] = [];
            }

            $groupedByUser[$username][] = $contrat;
        }


        return $this->render('contrat/contratchiffre.twig', [

            'groupedContrats' => $groupedByUser,


        ]);
    }
    #[Route('/chifafr', name: 'contratstatname_mont', methods: ['GET'])]
    public function chiffrAffrContratMont(): Response
    {

        $contrats = $this->contratRepository->findAll();

        $groupedByUser = [];

        foreach ($contrats as $contrat) {
            $user = $contrat->getUser();
            $username = $user ? $user->getUsername() : 'Inconnu';

            if (!isset($groupedByUser[$username])) {
                $groupedByUser[$username] = [];
            }

            $groupedByUser[$username][] = $contrat;
        }


        return $this->render('contrat/chiffreaffaire.twig', [

            'groupedContrats' => $groupedByUser,


        ]);
    }

    #[Route('/', name: 'chiffrecontrat', methods: ['GET'])]
    public function afficherContratsParUser(UserRepository $userRepository, TransactionRepository $transactionRepository): Response
    {
        $user = $userRepository->findByContratValid();
        $transactions = $transactionRepository->findAll();
        $frais = $this->entityManager->getRepository(Sav::class)->findAll();

        return $this->render('contrat/liste_par_user2.twig', [
            'users' => $user,
            'transactions' => $transactions,
            'frais' => $frais,
        ]);
    }

    #[Route('/byclient', name: 'byclient', methods: ['GET'])]
    public function afficherbyclientParUser(UserRepository $userRepository, TransactionRepository $transactionRepository): Response
    {
        $user = $userRepository->findByClientValid();
        $transactions = $transactionRepository->findAll();
        $frais = $this->entityManager->getRepository(Sav::class)->findAll();

        return $this->render('contrat/liste_par_payment.twig', [
            'users' => $user,
            'transactions' => $transactions,
            'frais' => $frais,
        ]);
    }

    #[Route('/bycalendrie', name: 'bycalendrie', methods: ['GET'])]
    public function afficherContratsbycalendrie(Request $request, UserRepository $userRepository, TransactionRepository $transactionRepository): Response
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');

        // Par défaut : mois en cours
        if (!$start || !$end) {
            $start = (new \DateTime('first day of this month'))->format('Y-m-d');
            $end = (new \DateTime('first day of next month'))->format('Y-m-d');
        }

        $users = $userRepository->findByContratValidBetweenDates($start, $end);
        $transactions = $transactionRepository->findAll();
        return $this->render('contrat/liste_par_calendrie.twig', [
            'users' => $users,
            'transactions' => $transactions,

        ]);
    }
}
