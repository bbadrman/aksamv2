<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\Payment;
use App\Form\PaymentType;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/payment')]
final class PaymentController extends AbstractController
{
    #[Route(name: 'app_payment_index', methods: ['GET'])]
    public function index(PaymentRepository $paymentRepository): Response
    {
        return $this->render('payment/index.html.twig', [
            'payments' => $paymentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}/new', name: 'app_payment_newpayment', methods: ['GET', 'POST'])]
    // public function newpayment(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(PaymentType::class, $payment);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $entityManager->flush();

    //         return $this->redirect($request->headers->get('referer'));
    //     }

    //     return $this->render('payment/new.html.twig', [
    //         'payment' => $payment,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/contrat/{id}/payment/new', name: 'app_payment_newpayment', methods: ['GET', 'POST'])]
    // public function newpayment(Request $request,  Contrat $contrat, EntityManagerInterface $entityManager): Response
    // {
    //     $payment = new Payment();
    //     $payment->setContrat($contrat); // Lier le payment au contrat

    //     $form = $this->createForm(PaymentType::class, $payment);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         if ($payment->getContrat()) {
    //             $entityManager->persist($payment->getContrat());
    //         }
    //         $entityManager->persist($payment);
    //         $entityManager->flush();
    //         // return $this->redirectToRoute('app_contrat_new', ['id' => $contrat->getId()]);
    //         return $this->redirect($request->headers->get('referer'));
    //     }

    //     return $this->render('partials/_addfrais_modaltest.html.twig', [
    //         'payment' => $payment,
    //         'form' => $form,
    //         'contrat' => $contrat,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_payment_show', methods: ['GET'])]
    public function show(Payment $payment): Response
    {
        return $this->render('payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    #[Route('/contrat/{id}/payment/new', name: 'app_payment_newpayment', methods: ['GET', 'POST'])]
    public function newpayment(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        // Ne pas créer un nouveau payment s'il existe déjà
        if ($contrat->getPayments()) {
            return $this->redirectToRoute('app_payment_editpayment', ['id' => $contrat->getId()]);
        }

        $payment = new Payment();
        $payment->setContrat($contrat);
        $contrat->setPayments($payment);

        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('partials/_addfrais_modaltest.html.twig', [
            'payment' => $payment,
            'form' => $form,
            'contrat' => $contrat,
        ]);
    }

    #[Route('/contrat/{id}/payment/edit', name: 'app_payment_editpayment', methods: ['GET', 'POST'])]
    public function editpayment(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $payment = $contrat->getPayments();

        if (!$payment) {
            // Si aucun payment, rediriger vers le formulaire de création
            return $this->redirectToRoute('app_payment_newpayment', ['id' => $contrat->getId()]);
        }

        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirect($request->headers->get('referer'));
        }
        $nmbr = $payment->getNmbrReglement();
        return $this->render('partials/_addfrais_modaltest.html.twig', [
            'payment' => $payment,
            'form' => $form,
            'contrat' => $contrat,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_payment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_delete', methods: ['POST'])]
    public function delete(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $payment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
    }
}
