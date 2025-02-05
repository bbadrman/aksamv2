<?php

namespace App\Controller;

use App\Entity\Prospect;
use App\Form\ProspectType;
use App\Repository\ProspectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prospect')]
final class ProspectController extends AbstractController
{
    #[Route(name: 'app_prospect_index', methods: ['GET'])]
    public function index(ProspectRepository $prospectRepository): Response
    {
        return $this->render('prospect/index.html.twig', [
            'prospects' => $prospectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prospect_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prospect = new Prospect();
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prospect);
            $entityManager->flush();

            return $this->redirectToRoute('app_prospect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prospect/new.html.twig', [
            'prospect' => $prospect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prospect_show', methods: ['GET'])]
    public function show(Prospect $prospect): Response
    {
        return $this->render('prospect/show.html.twig', [
            'prospect' => $prospect,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prospect_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prospect $prospect, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prospect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prospect/edit.html.twig', [
            'prospect' => $prospect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prospect_delete', methods: ['POST'])]
    public function delete(Request $request, Prospect $prospect, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prospect->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prospect);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prospect_index', [], Response::HTTP_SEE_OTHER);
    }
}
