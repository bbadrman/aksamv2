<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Form\ClientValideType;
use App\Form\SearchClientType;
use App\Repository\ClientRepository;
use App\Search\SearchClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function index(Request $request,): Response
    {
        $data = new SearchClient();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchClientType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        $client = [];

        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {
            // admi peut voire toutes les nouveaux client
            $client =  $this->clientRepository->findClientAll($data,  null);

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
            if (in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true) || in_array('ROLE_ADMIN', $user->getRoles(), true) || in_array('ROLE_VALIDE', $user->getRoles(), true)) {
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
            $this->clientRepository->add($client, true);
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
