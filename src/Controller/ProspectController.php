<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\History;
use App\Entity\Prospect;
use App\Entity\RelanceHistory;
use App\Form\ProspectType;
use App\Form\AffectProspectType;
use App\Form\ClientRelanceType;
use App\Form\GsmType;
use App\Form\RelanceProspectType;
use App\Form\ScdEmailType;
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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

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
            $prospect->setSource(1);
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

    //Afficher les nouveaux prospects via API return Int  
    #[Route('/newprospectApi', name: 'app_apiprospect_new', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function newprospectApi(
        ProspectRepository $prospectRepository,
        Security $security,
        SerializerInterface $serializer
    ): JsonResponse {

        // $this->denyAccessUnlessGrantedAuthorizedRoles();

        $prospect = [];
        $user = $security->getUser();
        $roles = $user->getRoles();
        if (in_array('ROLE_SUPER_ADMIN', $roles, true) || in_array('ROLE_ADMIN', $user->getRoles(), true) || in_array('ROLE_AFFECT', $user->getRoles(), true)) {
            $prospect =  $prospectRepository->findAllNewProspectsApi(null);
        } elseif (in_array('ROLE_CHEF', $roles, true)) {
            $prospect =  $prospectRepository->findAllNewProspectsChefAllApi($user, null);
        } elseif (in_array('ROLE_TEAM', $roles, true)) {
            // chef peut voire toutes les nouveaux prospects atacher a leur equipe
            $prospect =  $prospectRepository->findAllNewProspectsChefApi($user, null);
        } else {
            // cmrcl peut voire seulement les nouveaux prospects atacher a lui
            $prospect =  $prospectRepository->findAllNewProspectsComercialApi($user, null);
        }


        // Sérialiser les prospects
        $jsonData = $serializer->serialize($prospect, 'json');

        return new JsonResponse($jsonData, 200, [], true);
    }


    #[Route('/{id}', name: 'app_prospect_show', methods: ['GET'])]
    public function show(Request $request, Prospect $prospect, HistoryRepository $historyRepository, RelanceHistoryRepository $relanceHistory): Response
    {
        $emailForm = $this->createForm(ScdEmailType::class, $prospect, [
            'action' => $this->generateUrl('app_prospect_update_email', ['id' => $prospect->getId()])
        ]);

        $gsmForm = $this->createForm(GsmType::class, $prospect, [
            'action' => $this->generateUrl('app_prospect_update_gsm', ['id' => $prospect->getId()])
        ]);

        $teamHistory = $historyRepository->findBy(['prospect' => $prospect]);
        $relanceHistory = $relanceHistory->findBy(['prospect' => $prospect]);
        return $this->render('prospect/show.html.twig', [
            'prospect' => $prospect,
            'teamHistory' => $teamHistory,
            'relanceHistory' => $relanceHistory,
            'gsmForm' => $gsmForm->createView(),
            'emailForm' => $emailForm->createView(),
        ]);
    }
    #[Route('/{id}/update-email', name: 'app_prospect_update_email', methods: ['POST'])]
    public function updateEmail(Request $request, Prospect $prospect): Response
    {
        $emailForm = $this->createForm(ScdEmailType::class, $prospect);
        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $this->entityManager->persist($prospect);
            $this->entityManager->flush();
            $this->addFlash('info', 'Email mis à jour avec succès');
        }

        return $this->redirectToRoute('app_prospect_show', ['id' => $prospect->getId()]);
    }

    #[Route('/{id}/update-gsm', name: 'app_prospect_update_gsm', methods: ['POST'])]
    public function updateGsm(Request $request, Prospect $prospect): Response
    {
        $gsmForm = $this->createForm(GsmType::class, $prospect);
        $gsmForm->handleRequest($request);

        if ($gsmForm->isSubmitted() && $gsmForm->isValid()) {
            $this->entityManager->persist($prospect);
            $this->entityManager->flush();
            $this->addFlash('info', 'Téléphone mis à jour avec succès');
        }

        return $this->redirectToRoute('app_prospect_show', ['id' => $prospect->getId()]);
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

    //modale pour affectation 
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


    //modale pour relance un prosect
    #[Route('/{id}/relance', name: 'app_relance_edit', methods: ['GET', 'POST'])]
    public function relance(Request $request, Prospect $prospect, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelanceProspectType::class, $prospect);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // pour persister la date now en relance 6,7,8,10,11,12,13
            if ($prospect->getRelance() == '6 ' || $prospect->getRelance() == '7' || $prospect->getRelance() == '8' || $prospect->getRelance() == '10' || $prospect->getRelance() == '11' || $prospect->getRelance() == '12' || $prospect->getRelance() == '13') {
                $prospect->setRelanceAt(new \DateTimeImmutable());
            }


            $history = new RelanceHistory();
            $history->setProspect($prospect);
            $history->setMotifRelance($prospect->getRelance());
            $history->setRelancedAt($prospect->getRelanceAt());
            $history->setComment($prospect->getComment());
            $this->entityManager->persist($history);
            $entityManager->flush();
            return $this->redirect($request->headers->get('referer'));
            $this->addFlash('info', 'Votre Prospect a été traité avec succès!');
        }

        //ajouter client apartir de crée client
        $clientEntity = new Client();
        $clientEntity->setNom($prospect->getNom());
        $clientEntity->setPrenom($prospect->getPrenom());
        $clientEntity->setPhone($prospect->getPhone());
        $clientEntity->setEmail($prospect->getEmail());
        $clientEntity->setRaisonSociale($prospect->getRaisonSociale());
        $clientEntity->setTeam($prospect->getTeam());
        $clientEntity->setCmrcl($prospect->getComrcl());
        $clientEntity->setCreatAt(new \DateTime());
        // Associer le prospect au client
        $clientEntity->setProspect($prospect);


        // Handle the Client form submission
        $clientForm = $this->createForm(ClientRelanceType::class, $clientEntity);
        $clientForm->handleRequest($request);

        // Traitement du formulaire de client
        if ($clientForm->isSubmitted()) {
            if ($clientForm->isValid()) {
                // Vérifier si le client existe déjà dans la base de données
                $existingClient = $this->entityManager->getRepository(Client::class)->findOneBy([
                    'nom' => $prospect->getNom(),
                    'prenom' => $prospect->getPrenom(),
                    'phone' => $prospect->getPhone(),
                    'email' => $prospect->getEmail(),

                ]);

                if ($existingClient) {
                    $this->addFlash('info', 'Un client avec les mêmes informations existe déjà.');
                    return $this->redirect($request->headers->get('referer'));
                }
                // Récupérer les valeurs du formulaire
                $commentValue = $clientForm->get('comment')->getData();
                $relanceAtValue = new \DateTimeImmutable();

                // Mettre à jour le prospect
                $prospect->setRelance('9');
                $prospect->setComment($commentValue);
                $prospect->setRelanceAt($relanceAtValue);

                // Créer un historique de relance
                $history = new RelanceHistory();
                $history->setProspect($prospect);
                $history->setMotifRelance('9');
                $history->setRelancedAt($relanceAtValue);
                $history->setComment($commentValue);

                // Persister les entités
                $entityManager->persist($history);
                $entityManager->persist($clientEntity);
                $entityManager->flush();

                // Rediriger avec un message de succès
                $this->addFlash('success', 'Client ajouté avec succès!');
                return $this->redirect($request->headers->get('referer'));
            }
        }


        return $this->render('partials/_relance_modal.html.twig', [
            'prospect' => $prospect,
            'form' => $form->createView(),
            'clientForm' => $clientForm->createView(),
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
