<?php

namespace App\Controller;

use App\Entity\Compartenaire;
use App\Entity\Contrat;
use App\Form\ContratEditType;
use App\Form\ContratType;
use App\Form\SearchContratType;
use App\Repository\ClientRepository;
use App\Repository\ContratRepository;
use App\Search\SearchContrat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

#[Route('/contrat')]
final class ContratController extends AbstractController
{

    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private Security $security,
        private ClientRepository $clientRepository,
        private ContratRepository $contratRepository,
    ) {}

    #[Route(name: 'app_contrat_index', methods: ['GET'])]
    public function index(ContratRepository $contratRepository): Response
    {
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contratRepository->findAll(),
        ]);
    }

    #[Route('/valider', name: 'app_contrat_valid_index', methods: ['GET'])]
    public function valider(Request $request, ContratRepository $contratRepository, LoggerInterface $logger): Response
    {


        $data = new SearchContrat();
        $data->page = $request->query->get('page', 1);
        $form = $this->createForm(SearchContratType::class, $data);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        $contrats = [];


        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {
            $user = $this->security->getUser();
            $logger->info('User roles: ' . json_encode($user->getRoles()));
            if (in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true)  || in_array('ROLE_ADMIN', $user->getRoles(), true)  || in_array('ROLE_VALIDE', $user->getRoles(), true)) {
                // admi peut voire toutes les nouveaux client
                $contrats =  $contratRepository->findByContartValid($data,  null);
            } elseif (in_array('ROLE_CHEF', $user->getRoles(), true)) {
                // Rôle spécifique pour ROLE_VALIDE
                $contrats = $contratRepository->findByContartValid($data,  $user, null);
            } elseif (in_array('ROLE_TEAM', $user->getRoles(), true)) {
                // chef peut voire toutes les nouveaux client atacher a leur equipe
                $contrats =  $contratRepository->findByContartValid($data, $user,  null);
            } else {
                // cmrcl peut voire seulement les nouveaux client atacher a lui
                $contrats =  $contratRepository->findByContartValidComrcl($data, $user, null);
            }



            return $this->render('contrat/index.html.twig', [
                'contrats' => $contrats,
                'search_form' => $form->createView()
            ]);
        }
        return $this->render('contrat/search.html.twig', [
            'contrats' => $contrats,
            'search_form' => $form->createView()
        ]);
    }


    #[Route('/new/{id}', name: 'app_contrat_new', methods: ['GET', 'POST'])]
    public function new(int $id, Request $request, EntityManagerInterface $entityManager, ClientRepository $clientRepository): Response
    {

        $client = $clientRepository->find($id);
        if (!$client) {
            throw $this->createNotFoundException('Client not found');
        }

        $contrat = new Contrat();
        $contrat->setNom($client->getNom());
        $contrat->setPrenom($client->getPrenom());
        $contrat->setRaisonSociale($client->getRaisonSociale());
        $contrat->setClient($client);



        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($contrat->getAntcdAssure() as $antcdAssure) {
                $entityManager->persist($antcdAssure);
            }

            foreach ($contrat->getRegelement() as $regelement) {
                $entityManager->persist($regelement);
            }
            foreach ($contrat->getPartenaire() as $partenaire) {
                $entityManager->persist($partenaire);
            }
            $compagnie = $contrat->getCompagnie();
            if ($compagnie instanceof Compartenaire) {
                $entityManager->persist($compagnie);
            }
            $contrat->setUser($this->getUser());



            $entityManager->persist($contrat);
            $entityManager->flush();
            $this->addFlash('info', 'la Contrat a été créer avec succès!');
            return $this->redirectToRoute('app_contrat_valid_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contrat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContratEditType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contrat->setModif(1);

            $entityManager->flush();
            $this->addFlash('info', 'la Contrat a été modifié avec succès!');

            return $this->redirectToRoute('app_contrat_valid_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contrat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contrat_valid_index', [], Response::HTTP_SEE_OTHER);
    }
}
