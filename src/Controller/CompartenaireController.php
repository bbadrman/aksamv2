<?php

namespace App\Controller;

use App\Entity\Compartenaire;
use App\Form\CompartenaireType;
use App\Repository\CompartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/compartenaire')]
final class CompartenaireController extends AbstractController
{
    #[Route(name: 'app_compartenaire_index', methods: ['GET'])]
    public function index(CompartenaireRepository $compartenaireRepository): Response
    {
        return $this->render('compartenaire/index.html.twig', [
            'compartenaires' => $compartenaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_compartenaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $compartenaire = new Compartenaire();
        $form = $this->createForm(CompartenaireType::class, $compartenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($compartenaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_compartenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('compartenaire/new.html.twig', [
            'compartenaire' => $compartenaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compartenaire_show', methods: ['GET'])]
    public function show(Compartenaire $compartenaire): Response
    {
        return $this->render('compartenaire/show.html.twig', [
            'compartenaire' => $compartenaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_compartenaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Compartenaire $compartenaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompartenaireType::class, $compartenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_compartenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('compartenaire/edit.html.twig', [
            'compartenaire' => $compartenaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compartenaire_delete', methods: ['POST'])]
    public function delete(Request $request, Compartenaire $compartenaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compartenaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($compartenaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_compartenaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
