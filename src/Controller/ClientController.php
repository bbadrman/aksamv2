<?php

namespace App\Controller;

use ApiPlatform\State\Pagination\PaginatorInterface;
use App\Entity\Client;
use App\Entity\Contrat;
use App\Entity\Payment;
use App\Entity\Transaction;
use App\Form\ClientType;
use App\Search\SearchClient;
use App\Form\ClientValideType;
use App\Form\SearchClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface as PagerPaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/client')]
final class ClientController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private Security $security,
        private ClientRepository $clientRepository,
    ) {}


    #[Route(name: 'client_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, PagerPaginatorInterface $paginator): Response
    {
        // Create search form
        $data = new SearchClient();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchClientType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        $client = [];


        // Apply search criteria if form is submitted
        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {
            $client =  $this->clientRepository->findClientAll($data,  null);

            // Calculer le nombre de paiements pour chaque client
            $clientsWithPayments = [];
            foreach ($client as $c) {
                $paymentsCount = 0;
                foreach ($c->getContrats() as $contrat) {
                    if ($contrat->getPayments()) {
                        $paymentsCount++;
                    }
                }
                $clientsWithPayments[] = [
                    'client' => $c,
                    'payments_count' => $paymentsCount
                ];
            }

            return $this->render('client/index.html.twig', [
                'clients' => $client,
                'clients_with_payments' => $clientsWithPayments,
                'search_form' => $form->createView()
            ]);
        }

        return $this->render('client/search.html.twig', [
            'clients' => $client,

            'search_form' => $form->createView()
        ]);
    }



    #[Route('/client/{id}/contracts', name: 'app_client_contracts', methods: ['GET'])]
    public function getClientContracts(Client $client): Response
    {
        // Only fetch active contracts
        $contracts = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from('App\Entity\Contrat', 'c')
            ->where('c.client = :client')
            ->andWhere('c.status = 1')
            ->setParameter('client', $client)
            ->getQuery()
            ->getResult();

        return $this->render('client/_contracts_list.html.twig', [
            'contracts' => $contracts,
            'client' => $client
        ]);
    }

    // Nouvelle route pour récupérer les SAV d'un contrat
    #[Route('/contract/{id}/savs', name: 'app_contract_savs', methods: ['GET'])]
    public function getContractSavs(Contrat $contrat): Response
    {
        // Récupérer tous les SAV du contrat
        $savs = $this->entityManager->createQueryBuilder()
            ->select('s')
            ->from('App\Entity\Sav', 's')
            ->where('s.contrat = :contrat')
            ->setParameter('contrat', $contrat)
            ->orderBy('s.id', 'DESC') // Optionnel : trier par date de création
            ->getQuery()
            ->getResult();

        return $this->render('client/_savs_list.html.twig', [
            'savs' => $savs,
            'contrat' => $contrat
        ]);
    }

    #[Route('/valide', name: 'client_valide_index', methods: ['GET'])]
    public function valide(Request $request,  Security $security): Response
    {
        // $this->denyAccessUnlessGrantedAuthorizedRoles();

        $data = new SearchClient();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchClientType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        $client = [];

        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {

            $user = $security->getUser();
            if (in_array('ROLE_DEV', $user->getRoles(), true) || in_array('ROLE_ADMIN', $user->getRoles(), true) || in_array('ROLE_VALID', $user->getRoles(), true)) {
                // admit peut voire toutes les nouveaux client
                $client =  $this->clientRepository->findClientAdmin($data, null);
            } elseif (in_array('ROLE_CHEF', $user->getRoles(), true)) {
                // chef peut voire toutes les nouveaux client atacher a leur equipe
                $client =  $this->clientRepository->findClientChef($data,  $user, null);
            } else {
                // cmrcl peut voire seulement les nouveaux client atacher a lui
                $client =  $this->clientRepository->findClientCmrcl($data, $user, null);
            }


            return $this->render('client/index.html.twig', [
                'clients' => $client,

                'search_form' => $form->createView()
            ]);
        }
        return $this->render('client/search.html.twig', [
            'clients' => $client,

            'search_form' => $form->createView()
        ]);
    }


    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/payments', name: 'client_payments', methods: ['GET'])]
    public function getClientPayments(Client $client, EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les numéros de transaction existants dans la table Transaction
        $transactionRepository = $entityManager->getRepository(Transaction::class);
        $existingTransactions = $transactionRepository->createQueryBuilder('t')
            ->select('t.transaction')
            ->where('t.transaction IS NOT NULL')
            ->getQuery()
            ->getResult();

        // Créer un tableau des numéros de transaction pour une recherche plus rapide
        $transactionNumbers = array_map(function ($item) {
            return $item['transaction'];
        }, $existingTransactions);

        // Récupérer tous les paiements du client via ses contrats
        $payments = [];

        foreach ($client->getContrats() as $contrat) {
            if ($contrat->getPayments()) {
                $payment = $contrat->getPayments();

                // Vérifier si au moins une des transactions du payment existe dans la table Transaction
                $hasMatchingTransaction = false;

                // Tableau des champs de transaction à vérifier dans Payment
                $paymentTransactions = [
                    $payment->getTransaction(),
                    $payment->getTransaction1(),
                    $payment->getTransaction2(),
                    $payment->getTransaction3()
                    // Note: il manque getTransaction4() dans votre entité Payment
                ];

                // Vérifier si au moins une transaction du payment correspond à une transaction existante
                foreach ($paymentTransactions as $paymentTransaction) {
                    if ($paymentTransaction !== null && in_array($paymentTransaction, $transactionNumbers)) {
                        $hasMatchingTransaction = true;
                        break;
                    }
                }

                // Ajouter le paiement seulement s'il a une transaction correspondante
                if ($hasMatchingTransaction) {
                    // Calculer les montants valides (qui ont des transactions correspondantes)
                    $validMontants = [];
                    if ($payment->getTransaction() && in_array($payment->getTransaction(), $transactionNumbers)) {
                        $validMontants[] = $payment->getMontant() ?? 0;
                    }
                    if ($payment->getTransaction1() && in_array($payment->getTransaction1(), $transactionNumbers)) {
                        $validMontants[] = $payment->getMontant1() ?? 0;
                    }
                    if ($payment->getTransaction2() && in_array($payment->getTransaction2(), $transactionNumbers)) {
                        $validMontants[] = $payment->getMontant2() ?? 0;
                    }
                    if ($payment->getTransaction3() && in_array($payment->getTransaction3(), $transactionNumbers)) {
                        $validMontants[] = $payment->getMontant3() ?? 0;
                    }

                    $payments[] = [
                        'payment' => $payment,
                        'contrat' => $contrat,
                        'client' => $client,
                        'valid_montants' => $validMontants,
                        'total_valid_montants' => array_sum($validMontants)
                    ];
                }
            }
        }

        // Trier les paiements par date de création (plus récent en premier)
        usort($payments, function ($a, $b) {
            $dateA = $a['payment']->getCreatAt() ?? new \DateTime('1970-01-01');
            $dateB = $b['payment']->getCreatAt() ?? new \DateTime('1970-01-01');
            return $dateB <=> $dateA;
        });

        return $this->render('client/payments.html.twig', [
            'client' => $client,
            'payments' => $payments,
        ]);
    }

    // Optionnel : Méthode AJAX pour charger les paiements dynamiquement
    #[Route('/{id}/payments/ajax', name: 'client_payments_ajax', methods: ['GET'])]
    public function getClientPaymentsAjax(Client $client): JsonResponse
    {
        $payments = [];

        foreach ($client->getContrats() as $contrat) {
            if ($contrat->getPayments()) {
                $payment = $contrat->getPayments();
                $payments[] = [
                    'id' => $payment->getId(),
                    'frais' => $payment->getFrais(),
                    'cotisation' => $payment->getCotisation(),
                    'creatAt' => $payment->getCreatAt() ? $payment->getCreatAt()->format('Y-m-d H:i') : null,
                    'contrat_id' => $contrat->getId(),
                    'total' => floatval($payment->getFrais() ?? 0) + floatval($payment->getCotisation() ?? 0)
                ];
            }
        }

        // Trier par date
        usort($payments, function ($a, $b) {
            return ($b['creatAt'] ?? '1970-01-01') <=> ($a['creatAt'] ?? '1970-01-01');
        });

        return $this->json([
            'success' => true,
            'payments' => $payments,
            'total_payments' => count($payments),
            'client_name' => $client->getNom() . ' ' . $client->getPrenom()
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'le Client a été modifié avec succès!');
            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/editvalide', name: 'client_valide_edit', methods: ['GET', 'POST'])]
    public function editvalide(Request $request, Client $client): Response
    {
        // $this->denyAccessUnlessGrantedAuthorizedRoles();

        $form = $this->createForm(ClientValideType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setModifie(1);
            $this->entityManager->flush();
            $this->addFlash('info', 'le Client a été modifié avec succès!');
            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/editvalid.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    }
}
