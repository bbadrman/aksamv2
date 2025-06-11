<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Search\SearchTransaction;
use App\Form\SearchTransactionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/transaction')]
final class TransactionController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private TransactionRepository $transactionRepository,
    ) {}

    #[Route(name: 'app_transaction_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $data = new SearchTransaction();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchTransactionType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        $transaction = [];
        $totals = ['totalDebit' => 0, 'totalCredit' => 0];

        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {
            $transaction = $this->transactionRepository->findSearchTransaction($data, null);

            // Calculer les totaux des transactions affichées
            $totals = $this->calculateTotals($transaction);

            return $this->render('transaction/index.html.twig', [
                'transactions' => $transaction,
                'search_form' => $form->createView(),
                'totalDebit' => $totals['totalDebit'],
                'totalCredit' => $totals['totalCredit'],
            ]);
        }

        return $this->render('transaction/search.html.twig', [
            'transactions' => $transaction,
            'search_form' => $form->createView(),
            'totalDebit' => $totals['totalDebit'],
            'totalCredit' => $totals['totalCredit'],
        ]);
    }

    /**
     * Calcule les totaux des transactions
     */
    private function calculateTotals($transactions): array
    {
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($transactions as $transaction) {
            $totalDebit += $transaction->getDebit() ?? 0;
            $totalCredit += $transaction->getCredit() ?? 0;
        }

        return [
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit
        ];
    }

    #[Route('/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $transaction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
