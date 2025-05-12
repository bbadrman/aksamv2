<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/team')]
final class TeamController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    #[Route(name: 'app_team_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('team/index.html.twig', [
            'teams' => $teamRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($team->getUniteTravails() as $unite) {
                $unite->addTeam($team);
            }

            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team/new.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }


    #[Route('/teams-api', name: 'teams_api', methods: ['GET'])]
    public function testuserTesApi(TeamRepository $teamRepository): Response
    {
        // Récupérer toutes les équipes avec leurs commerciaux
        $teams = $teamRepository->findAll();

        // Préparer les données à retourner
        $jsonData = [];
        foreach ($teams as $team) {
            $commercials = [];
            foreach ($team->getUsers() as $commercial) {
                if ($commercial->getStatus() === 1) {
                    $commercials[] = [
                        'id' => $commercial->getId(),
                        'username' => $commercial->getUsername(),
                        'status' => $commercial->getStatus(),
                        // Ajoutez d'autres propriétés de l'utilisateur que vous souhaitez inclure
                    ];
                }
            }
            $jsonData[] = [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'commercials' => $commercials,
            ];
        }

        // Retourner les données au format JSON
        return $this->json($jsonData, Response::HTTP_OK);
    }


    #[Route('/new-team', name: 'app_team_new_user', methods: ['GET', 'POST'])]
    public function add(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $team = new Team();

        $team->setName($request->get('name'));

        $team->setDescription($request->get('description'));

        $errors = $validator->validate($team);

        $errorMessages = array();

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json([
                'status' => 400,
                'errors' => $errorMessages,
            ]);
        } else {
            $this->entityManager->persist($team);
            $this->entityManager->flush();

            return $this->json([
                'status' => 200,
                'message' => 'Equipe a bien été ajouté',
            ]);
        }
    }

    #[Route('/{id}', name: 'app_team_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // foreach ($team->getUniteTravails() as $unite) {
            //     $unite->addTeam($team);
            // }
            $entityManager->flush();

            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_team_delete', methods: ['POST'])]
    public function delete(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $team->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($team);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
