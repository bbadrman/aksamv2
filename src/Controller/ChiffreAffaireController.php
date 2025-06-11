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

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/', name: 'paymentfilter', methods: ['GET'])]
    public function afficherbycalendrie(
        Request $request,
        UserRepository $userRepository,
        TransactionRepository $transactionRepository,
        PaymentRepository $paymentRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Récupérer les dates du formulaire ou utiliser le mois courant par défaut
        $dateFrom = $request->query->get('date_from');
        $dateTo = $request->query->get('date_to');

        if ($dateFrom && $dateTo) {
            $startDate = new \DateTime($dateFrom);
            $endDate = (new \DateTime($dateTo))->setTime(23, 59, 59); // Inclure toute la journée
        } else {
            // Par défaut : mois courant
            $startDate = new \DateTime('first day of this month');
            $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);
        }

        // Utiliser la méthode qui filtre par creatAt au lieu de datePayment
        $users = $userRepository->findByClientValidWithCreatAtRange($startDate, $endDate);

        // Récupérer toutes les transactions existantes une seule fois pour optimiser
        $existingTransactions = $transactionRepository->createQueryBuilder('t')
            ->select('t.transaction')
            ->where('t.transaction IS NOT NULL')
            ->getQuery()
            ->getResult();

        $transactionNumbers = array_column($existingTransactions, 'transaction');

        $dataParCommercial = [];

        foreach ($users as $user) {
            $contrats = $user->getContrats();
            $transactionsFrais = [];

            foreach ($contrats as $contrat) {
                $payment = $contrat->getPayments();

                if (!$payment) {
                    continue;
                }

                // Vérifier si le payment a été créé dans l'intervalle sélectionné
                $paymentCreatAt = $payment->getDatePayment();
                $isInDateRange = $paymentCreatAt &&
                    $paymentCreatAt >= $startDate &&
                    $paymentCreatAt <= $endDate;

                if (!$isInDateRange) {
                    continue; // Ignorer ce payment s'il n'est pas dans la période
                }

                // Vérifier chaque champ de transaction du payment
                $paymentTransactions = [
                    'transaction' => [
                        'value' => $payment->getTransaction(),
                        'montant' => $payment->getMontant(),
                        'datePayment' => $payment->getDatePayment()
                    ],
                    'transaction1' => [
                        'value' => $payment->getTransaction1(),
                        'montant' => $payment->getMontant1(),
                        'datePayment' => $payment->getDatePayment1()
                    ],
                    'transaction2' => [
                        'value' => $payment->getTransaction2(),
                        'montant' => $payment->getMontant2(),
                        'datePayment' => $payment->getDatePayment2()
                    ],
                    'transaction3' => [
                        'value' => $payment->getTransaction3(),
                        'montant' => $payment->getMontant3(),
                        'datePayment' => $payment->getDatePayment3()
                    ]
                ];

                foreach ($paymentTransactions as $field => $transactionData) {
                    $transactionValue = $transactionData['value'];
                    $montantValue = $transactionData['montant'];
                    $datePaymentValue = $transactionData['datePayment'];

                    if ($transactionValue && in_array($transactionValue, $transactionNumbers)) {
                        // La transaction existe dans la table Transaction
                        $transactionEntity = $transactionRepository->findOneBy(['transaction' => $transactionValue]);

                        $transactionsFrais[] = [
                            'transaction' => $transactionValue,
                            'montant' => $montantValue, // Montant correspondant à cette transaction
                            'credit' => $transactionEntity ? $transactionEntity->getCredit() : null,
                            'payment' => $payment, // Référence au payment pour accéder aux frais et cotisation
                            'contrat' => $contrat, // Référence au contrat pour afficher le nom
                            'datePayment' => $datePaymentValue, // Date de paiement de cette transaction
                            'creatAt' => $paymentCreatAt, // Date de création du payment
                        ];
                    }
                }
            }

            // N'ajouter l'utilisateur que s'il a des transactions valides
            if (!empty($transactionsFrais)) {
                $dataParCommercial[] = [
                    'user' => $user,
                    'transactions' => $transactionsFrais,
                ];
            }
        }

        return $this->render('contrat/liste_par_paymcalendrie.twig', [
            'users' => $users,
            'dataParCommercial' => $dataParCommercial,
            'dateFrom' => $dateFrom ?: $startDate->format('Y-m-d'),
            'dateTo' => $dateTo ?: $endDate->format('Y-m-d'),
        ]);
    }
}
