<?php

namespace App\Controller;

use App\Entity\Sav;
use App\Form\SavType;
use App\Entity\RelanceSav;
use App\Form\RelanceSavType;
use App\Form\SavTraiterType;
use App\Repository\SavRepository;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sav')]
class SavController extends AbstractController
{
    public function __construct(

        private  EntityManagerInterface $entityManager,

    ) {}
    #[Route('/', name: 'app_sav_index', methods: ['GET'])]
    public function index(SavRepository $savRepository): Response
    {
        return $this->render('sav/index.html.twig', [
            'savs' => $savRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sav_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sav = new Sav();
        $form = $this->createForm(SavType::class, $sav);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sav);
            $entityManager->flush();

            return $this->redirectToRoute('app_sav_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sav/new.html.twig', [
            'sav' => $sav,
            'form' => $form,
        ]);
    }

    #[Route('/new/{id}', name: 'contrat_sav_new', methods: ['GET', 'POST'])]
    public function addcontrat(int $id, Request $request, EntityManagerInterface $entityManager, ContratRepository $contratRepository): Response
    {
        $contrat = $contratRepository->find($id);
        if (!$contrat) {
            throw $this->createNotFoundException('contrat not found');
        }

        $sav = new Sav();
        $sav->setContrat($contrat);
        $form = $this->createForm(SavType::class, $sav);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sav->setCreatAt(new \DateTime());
            $entityManager->persist($sav);
            $entityManager->flush();

            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sav/new.html.twig', [
            'sav' => $sav,
            'form' => $form,

        ]);
    }

    #[Route('/{id}', name: 'app_sav_show', methods: ['GET'])]
    public function show(Request $request, Sav $sav): Response
    {


        $form = $this->createForm(SavTraiterType::class, $sav);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_sav_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('sav/show.html.twig', [
            'sav' => $sav,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_afficher_show', methods: ['GET', 'POST'])]
    public function afficher(Request $request, Sav $sav): Response
    {


        // Gerer les relance 
        $relance = new RelanceSav();
        $relance->setSav($sav);

        $form = $this->createForm(RelanceSavType::class, $relance);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Add the new relance to the prospect
            $sav->addRelanceSav($relance);
            $sav->setMotifRelance($relance->getMotifRelance());
            $sav->setRelanceAt($relance->getCreatAt());
            $this->entityManager->persist($relance);

            $this->entityManager->flush();

            $this->addFlash('success', 'Relance ajoutée avec succès.');
            //pour vider la form et rest au meme page 
            return $this->redirect($request->getRequestUri());
        }


        return $this->render('sav/show.html.twig', [
            'sav' => $sav,
            'contrat' => $sav->getContrat(),
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}/edit', name: 'app_sav_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sav $sav, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SavType::class, $sav);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sav_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sav/edit.html.twig', [
            'sav' => $sav,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}/traitement', name: 'app_sav_traiter', methods: ['GET', 'POST'])]
    // public function traiter(Request $request, Sav $sav, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(SavTraiterType::class, $sav);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('sav/edit.html.twig', [
    //         'sav' => $sav,
    //         'form' => $form,
    //     ]);
    // }




    #[Route('/{id}', name: 'app_sav_delete', methods: ['POST'])]
    public function delete(Request $request, Sav $sav, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sav->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sav);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sav_index', [], Response::HTTP_SEE_OTHER);
    }
}
