<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\ContratRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


    //chiffre d'affaire 
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/', name: 'baypayment', methods: ['GET'])]
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

        return $this->render('contrat/liste_par_payment.twig', [
            'users' => $users,
            'dataParCommercial' => $dataParCommercial,

        ]);
    }

    //chiffre d'affaire par by calendrie
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
}
