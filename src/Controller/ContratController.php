<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\Payment;
use App\Entity\Document;
use App\Form\ContratType;
use App\Form\PaymentType;
use App\Form\AddPaymentType;
use Psr\Log\LoggerInterface;
use App\Entity\Compartenaire;
use App\Form\ContratEditType;
use App\Form\ContratEtatType;
use App\Search\SearchContrat;
use App\Form\SearchContratType;
use App\Form\ContratPaiementType;
use App\Repository\ClientRepository;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
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
            if (in_array('ROLE_DEV', $user->getRoles(), true)  || in_array('ROLE_ADMIN', $user->getRoles(), true)  || in_array('ROLE_VALID', $user->getRoles(), true)) {
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

        $payment = new Payment();
        $payment->setContrat($contrat); // si nécessaire
        $contrat->setPayments($payment); // ⚠️ important

        $ducument = new Document();
        $ducument->setContrat($contrat); // si nécessaire
        $contrat->setDocument($ducument); // ⚠️ important


        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'action souhaitée (valider ou dépliquer)
            $action = $request->request->get('action');

            foreach ($contrat->getAntcdAssure() as $antcdAssure) {
                $entityManager->persist($antcdAssure);
            }

            foreach ($contrat->getRegelement() as $regelement) {
                $entityManager->persist($regelement);
            }

            foreach ($contrat->getPayments() as $payment) {
                $entityManager->persist($payment);
            }
            foreach ($contrat->getDocument() as $ducument) {
                $entityManager->persist($ducument);
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
            $action = $request->request->get('action');

            if ($action === 'duplicate') {
                // 3. Dupliquer les données
                $newContrat = new Contrat();
                $newContrat->setNom($contrat->getNom());
                $newContrat->setPrenom($contrat->getPrenom());
                $newContrat->setRaisonSociale($contrat->getRaisonSociale());
                $newContrat->setClient($contrat->getClient());
                $newContrat->setUser($this->getUser());
                $newContrat->setTypeContrat($contrat->getTypeContrat());
                $newContrat->setTypeConducteur($contrat->getTypeConducteur());
                $newContrat->setProduct($contrat->getProduct());
                $newContrat->setTypeProduct($contrat->getTypeProduct());
                $newContrat->setDatePreleveAcompte($contrat->getDatePreleveAcompte());
                $newContrat->setactivite($contrat->getactivite());
                $newContrat->setraisonSociale($contrat->getraisonSociale());
                $newContrat->setdateNaissance($contrat->getdateNaissance());
                $newContrat->setdateSouscrpt($contrat->getdateSouscrpt());
                $newContrat->setdateEffet($contrat->getdateEffet());
                $newContrat->setimatriclt($contrat->getimatriclt());
                $newContrat->setconducteur($contrat->getconducteur());
                $newContrat->settypePermis($contrat->gettypePermis());
                $newContrat->setdatePermis($contrat->getdatePermis());
                $newContrat->setdateMec($contrat->getdateMec());
                $newContrat->setrisqueUsage($contrat->getrisqueUsage());
                $newContrat->setdateSuspension($contrat->getdateSuspension());
                $newContrat->setmotifAnnulation($contrat->getmotifAnnulation());
                $newContrat->setcrmActuel($contrat->getcrmActuel());
                $newContrat->setSuspensionPermis($contrat->isSuspensionPermis());
                $newContrat->setAnnulationPermis($contrat->isAnnulationPermis());
                $newContrat->setNmbrAssure($contrat->getNmbrAssure());
                $newContrat->setcompagnie($contrat->getcompagnie());
                $newContrat->setpartenaire($contrat->getpartenaire());
                $newContrat->setformule($contrat->getformule());
                $newContrat->setcotisation($contrat->getcotisation());
                $newContrat->setfraction($contrat->getfraction());
                $newContrat->setcrmRetenu($contrat->getcrmRetenu());
                $newContrat->setgaranties($contrat->getgaranties());
                $newContrat->setacompte($contrat->getacompte());
                $newContrat->setDateAchat($contrat->getDateAchat());
                $newContrat->setdatePreleveAcompte($contrat->getdatePreleveAcompte());
                $newContrat->setjourPrelvm($contrat->getjourPrelvm());
                $newContrat->setpayments($contrat->getpayments());
                foreach ($contrat->getAntcdAssure() as $antcdAssure) {
                    $newContrat->addAntcdAssure($antcdAssure);
                }

                // ➡️ ajouter ici TOUS les autres champs que tu veux dupliquer
                // exemple:
                // $newContrat->setAdresse($contrat->getAdresse());

                // Puis ouvrir un nouveau formulaire
                $form = $this->createForm(ContratType::class, $newContrat);
                $duplicatedCount = $entityManager->getRepository(Contrat::class)->createQueryBuilder('c')
                    ->where('c.nom = :nom')
                    ->andWhere('c.prenom = :prenom')
                    ->andWhere('c.client = :client')
                    ->andWhere('c.typeContrat = :typeContrat')
                    ->andWhere('c.typeConducteur = :typeConducteur')
                    ->andWhere('c.product = :product')
                    ->setParameter('nom', $contrat->getNom())
                    ->setParameter('prenom', $contrat->getPrenom())
                    ->setParameter('client', $contrat->getClient())
                    ->setParameter('typeContrat', $contrat->getTypeContrat())
                    ->setParameter('typeConducteur', $contrat->getTypeConducteur())
                    ->setParameter('product', $contrat->getProduct())
                    ->select('count(c.id)')
                    ->getQuery()
                    ->getSingleScalarResult();

                return $this->render('contrat/new.html.twig', [
                    'contrat' => $newContrat,
                    'form' => $form,
                    'duplicate_number' => $duplicatedCount + 1,
                ]);
            }


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

            foreach ($contrat->getAntcdAssure() as $antcdAssure) {
                $entityManager->persist($antcdAssure);
            }


            foreach ($contrat->getRegelement() as $regelement) {
                $entityManager->persist($regelement);
            }

            if ($contrat->getPayments()) {
                $entityManager->persist($contrat->getPayments());
            }

            if ($contrat->getDocument()) {
                $entityManager->persist($contrat->getDocument());
            }



            $entityManager->flush();
            $this->addFlash('info', 'la Contrat a été modifié avec succès!');

            return $this->redirectToRoute('app_contrat_valid_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    // modale pour change l'etat du contrat
    #[Route('/{id}/etat', name: 'app_contrat_etat', methods: ['GET', 'POST'])]
    public function etat(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContratEtatType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contrat->setModif(1);

            $entityManager->flush();
            $this->addFlash('info', 'Etat du Contrat a été modifié avec succès!');

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('partials/_etat_modal.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    // ajouter une payment a contrat
    #[Route('/{id}/paiement', name: 'app_contrat_paiement', methods: ['GET', 'POST'])]
    public function paiement(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContratPaiementType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($contrat->getPayments()) {
                $entityManager->persist($contrat->getPayments());
            }
            $entityManager->flush();
            $this->addFlash('info', 'Une paiement a été ajouter avec succès!');

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('partials/_paiement_modal.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/addpayment', name: 'app_contrat_addpayment', methods: ['GET', 'POST'])]
    public function addpayment(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddPaymentType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->flush();
            $this->addFlash('info', 'Une paiement a été ajouter avec succès!');

            return $this->redirect($request->headers->get('referer'));
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
