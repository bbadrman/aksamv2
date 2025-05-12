<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\userHistory;
use App\Form\SearchUserType;
use App\Form\UserSearchType;
use App\Repository\UserRepository;
use App\Search\SearchUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/user')]
final class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $encoder) {}

    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $data = new SearchUser();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchUserType::class, $data);
        $form->handleRequest($request);
        $users = $userRepository->findSearchUser($data);

        return $this->render('user/index.html.twig', [
            'users' => $users,

            'search_form' => $form->createView()
        ]);
    }
    #[Route('/document', name: 'app_user_document', methods: ['GET'])]
    public function doc(
        Request $request,
        UserRepository $userRepository,
        PaginatorInterface $paginator
    ): Response {
        $search = new SearchUser();
        $search->page = $request->query->getInt('page', 1);

        $form = $this->createForm(UserSearchType::class, $search);
        $form->handleRequest($request);

        $users = null;

        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {
            $queryBuilder = $userRepository->findWithFilters($search);

            $users = $paginator->paginate($queryBuilder, $search->page, 10);
        }

        return $this->render('user/userdocument.html.twig', [
            'search_form' => $form->createView(),
            'users' => $users,
        ]);
    }

    #[Route('/contrat', name: 'app_user_contrat', methods: ['GET'])]
    public function contrat(UserRepository $userRepository): Response
    {
        $user = $userRepository->findByContratValid();
        return $this->render('user/contrat.html.twig', [
            'users' => $user,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);


            foreach ($user->getTeams() as $team) {
                $history = new userHistory();
                $history->setUsers($user);
                $history->setTeam($team);
                $history->setAffectAt(new \DateTime());

                $entityManager->persist($history);
            }

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('success', 'Votre User a été ajouté avec succès!');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            foreach ($user->getTeams() as $team) {
                $history = new userHistory();
                $history->setUsers($user);
                $history->setTeam($team);
                $history->setAffectAt(new \DateTime());

                $entityManager->persist($history);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/activer/{id}', name: 'activer')]
    public function activer(User $user)
    {

        $user->setStatus(($user->getStatus()) ? false : true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response("true");
    }
}
