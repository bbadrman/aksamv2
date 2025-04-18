<?php

namespace App\Controller;

use App\Entity\Sav;
use App\Form\SearchContratCldrType;
use App\Repository\ClientRepository;
use App\Repository\ContratRepository;
use App\Repository\PaymentRepository;
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



        return $this->render('contrat/liste_par_payment.twig', [
            'users' => $user,


        ]);
    }

    #[Route('/bypayment', name: 'baypayment', methods: ['GET'])]
    public function afficherbypayment(UserRepository $userRepository, TransactionRepository $transactionRepository,    PaymentRepository $paymentRepository): Response
    {
        $users = $userRepository->findByClientValid();

        $currentMonth = new \DateTime('first day of this month');
        $nextMonth = (clone $currentMonth)->modify('+1 month');

        $dataParCommercial = [];

        foreach ($users as $user) {
            $contrats = $user->getContrats();
            $transactionsFrais = [];

            foreach ($contrats as $contrat) {
                foreach ($contrat->getPayments() as $payment) {
                    if ($payment->getCreatAt() < $currentMonth || $payment->getCreatAt() >= $nextMonth) {
                        continue;
                    }

                    foreach (['transaction', 'transaction1', 'transaction2', 'transaction3'] as $field) {
                        $tx = $payment->{'get' . ucfirst($field)}();
                        if ($tx) {
                            $transactionEntity = $transactionRepository->findOneBy(['transaction' => $tx]);

                            $transactionsFrais[] = [
                                'transaction' => $tx,
                                'frais' => $payment->getFrais(),
                                'credit' => $transactionEntity ? $transactionEntity->getCredit() : null,
                            ];
                        }
                    }
                }
            }

            $dataParCommercial[] = [
                'user' => $user,
                'transactions' => $transactionsFrais,
            ];
        }

        return $this->render('contrat/liste_par_payment2.twig', [
            'users' => $user,
            'dataParCommercial' => $dataParCommercial,

        ]);
    }

    #[Route('/bypaymentcalendrie', name: 'baypaymentcalendrie', methods: ['GET'])]
    public function afficherbypaymentcalendrie(
        Request $request,
        UserRepository $userRepository,
        TransactionRepository $transactionRepository,
        PaymentRepository $paymentRepository
    ): Response {
        $start = $request->query->get('start');
        $end = $request->query->get('end');

        if (!$start || !$end) {
            $start = (new \DateTime('first day of this month'))->format('Y-m-d');
            $end = (new \DateTime('first day of next month'))->format('Y-m-d');
        }

        // 💡 Convertir en DateTime
        $startDate = \DateTime::createFromFormat('Y-m-d', $start);
        $endDate = \DateTime::createFromFormat('Y-m-d', $end);

        // Utiliser les dates au format string pour la requête SQL
        $users = $userRepository->findByContratValidBetweenDates($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));

        $dataParCommercial = [];

        foreach ($users as $user) {
            $contrats = $user->getContrats();
            $transactionsFrais = [];

            foreach ($contrats as $contrat) {
                foreach ($contrat->getPayments() as $payment) {
                    // ✅ Comparaison correcte
                    if ($payment->getCreatAt() < $startDate || $payment->getCreatAt() >= $endDate) {
                        continue;
                    }

                    foreach (['transaction', 'transaction1', 'transaction2', 'transaction3'] as $field) {
                        $tx = $payment->{'get' . ucfirst($field)}();
                        if ($tx) {
                            $transactionEntity = $transactionRepository->findOneBy(['transaction' => $tx]);

                            $transactionsFrais[] = [
                                'transaction' => $tx,
                                'frais' => $payment->getFrais(),
                                'credit' => $transactionEntity ? $transactionEntity->getCredit() : null,
                            ];
                        }
                    }
                }
            }

            $dataParCommercial[] = [
                'user' => $user,
                'transactions' => $transactionsFrais,
            ];
        }

        return $this->render('contrat/liste_par_calendrie.twig', [
            'dataParCommercial' => $dataParCommercial,
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

        return $this->render('contrat/liste_par_calendrie2.twig', [
            'users' => $users,


        ]);
    }
}
