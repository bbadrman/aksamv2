<?php

namespace App\Controller;

use App\Entity\History;
use App\Entity\Prospect;
use App\Entity\RelanceHistory;
use App\Form\ProspectType;
use App\Form\AffectProspectType;
use App\Form\RelanceProspectType;
use App\Repository\HistoryRepository;
use App\Repository\ProspectRepository;
use App\Repository\RelanceHistoryRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/prospect')]
final class ProspectController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ProspectRepository $prospectRepository) {}
    #[Route(name: 'app_prospect_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $prospects = $this->prospectRepository->findAll();


        return $this->render('prospect/index.html.twig', [
            'prospects' => $prospects,


        ]);
    }

    #[Route('/new', name: 'app_prospect_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {


        $prospect = new Prospect();
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $prospect->setAutor($this->getUser());
            $this->entityManager->persist($prospect);
            $teamHistory = new History();
            $teamHistory->setProspect($prospect); // $prospect est votre instance de Prospect 

            if ($prospect->getTeam() !== null && $prospect->getComrcl() !== null) {
                $actionType =  $prospect->getTeam()->getName() . ' et commercial ' . $prospect->getComrcl()->getUserIdentifier(); // Les deux sont associés
            } elseif ($prospect->getTeam() !== null) {
                $actionType =  $prospect->getTeam()->getName(); // Seulement associé à l'équipe
            } elseif ($prospect->getComrcl() !== null) {
                $actionType =  $prospect->getComrcl()->getUserIdentifier(); // Seulement associé au commercial
            } else {
                $actionType = 'None'; // Aucune association
            }

            $teamHistory->setActionType($actionType);

            $teamHistory->setActionDate(new \DateTime());

            $this->entityManager->persist($teamHistory);
            $entityManager->flush();

            return $this->redirectToRoute('app_prospect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prospect/new.html.twig', [
            'prospect' => $prospect,
            'form' => $form,

        ]);
    }

    #[Route('/{id}', name: 'app_prospect_show', methods: ['GET'])]
    public function show(Prospect $prospect, HistoryRepository $historyRepository, RelanceHistoryRepository $relanceHistory): Response
    {
        $teamHistory = $historyRepository->findBy(['prospect' => $prospect]);
        $relanceHistory = $relanceHistory->findBy(['prospect' => $prospect]);
        return $this->render('prospect/show.html.twig', [
            'prospect' => $prospect,
            'teamHistory' => $teamHistory,
            'relanceHistory' => $relanceHistory,
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

    #[Route('/{id}/affect', name: 'app_affect_edit', methods: ['GET', 'POST'])]
    public function affect(Request $request, Prospect $prospect, TeamRepository $teamRepository): Response
    {
        $form = $this->createForm(AffectProspectType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timezone = new \DateTimeZone('Europe/Paris');
            $prospect->setAffectAt(new \DateTimeImmutable('now', $timezone));

            $teamHistory = new History();
            $teamHistory->setProspect($prospect); // $prospect est votre instance de Prospect 

            if ($prospect->getTeam() !== null && $prospect->getComrcl() !== null) {
                $actionType =  $prospect->getTeam()->getName() . ' => ' . $prospect->getComrcl()->getUserIdentifier(); // Les deux sont associés
            } elseif ($prospect->getTeam() !== null) {
                $actionType =  $prospect->getTeam()->getName(); // Seulement associé à l'équipe
            } elseif ($prospect->getComrcl() !== null) {
                $actionType =  $prospect->getComrcl()->getUserIdentifier(); // Seulement associé au commercial
            } else {
                $actionType = 'None'; // Aucune association
            }

            $teamHistory->setActionType($actionType);

            $teamHistory->setActionDate(new \DateTime('now', $timezone));

            $this->entityManager->persist($teamHistory);
            $this->entityManager->flush();

            $this->addFlash('info', 'Votre Prospect a été affecté avec succès!');
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('partials/_affect_modal.html.twig', [
            'prospect' => $prospect,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}/relance', name: 'app_relance_edit', methods: ['GET', 'POST'])]
    public function relance(Request $request, Prospect $prospect, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelanceProspectType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($prospect->getRelanceAt() === null) {
                $timezone = new \DateTimeZone('Europe/Paris');
                $prospect->setRelanceAt(new \DateTimeImmutable('now', $timezone));
            }


            $history = new RelanceHistory();
            $history->setProspect($prospect);
            $history->setMotifRelance($prospect->getRelance());
            $history->setRelancedAt($prospect->getRelanceAt());
            $this->entityManager->persist($history);
            $entityManager->flush();
            $this->addFlash('info', 'Votre Prospect a été traité avec succès!');
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('partials/_relance_modal.html.twig', [
            'prospect' => $prospect,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_prospect_delete', methods: ['POST'])]
    public function delete(Request $request, Prospect $prospect, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $prospect->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prospect);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prospect_index', [], Response::HTTP_SEE_OTHER);
    }
}
