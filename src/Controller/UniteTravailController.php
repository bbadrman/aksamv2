<?php

namespace App\Controller;

use App\Entity\UniteTravail;
use App\Form\UniteTravailType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UniteTravailRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/unitetravail')]
final class UniteTravailController extends AbstractController
{
    #[Route(name: 'app_unite_travail_index', methods: ['GET'])]
    public function index(UniteTravailRepository $uniteTravailRepository): Response
    {
        return $this->render('unite_travail/index.html.twig', [
            'unite_travails' => $uniteTravailRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_unite_travail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $uniteTravail = new UniteTravail();
        $form = $this->createForm(UniteTravailType::class, $uniteTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($uniteTravail);
            $entityManager->flush();

            return $this->redirectToRoute('app_unite_travail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('unite_travail/new.html.twig', [
            'unite_travail' => $uniteTravail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_unite_travail_show', methods: ['GET'])]
    public function show(UniteTravail $uniteTravail): Response
    {
        return $this->render('unite_travail/show.html.twig', [
            'unite_travail' => $uniteTravail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_unite_travail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UniteTravail $uniteTravail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UniteTravailType::class, $uniteTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_unite_travail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('unite_travail/edit.html.twig', [
            'unite_travail' => $uniteTravail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_unite_travail_delete', methods: ['POST'])]
    public function delete(Request $request, UniteTravail $uniteTravail, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $uniteTravail->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($uniteTravail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_unite_travail_index', [], Response::HTTP_SEE_OTHER);
    }
}
