<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\Document;
use App\Form\DocumentType;
use App\Form\SearchDocumentType;
use App\Repository\DocumentRepository;
use App\Search\SearchDocument;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/document')]
final class DocumentController extends AbstractController
{

    public function __construct(

        private RequestStack $requestStack,
        private Security $security,
        private DocumentRepository $documentRepository,
    ) {}




    #[Route('/filter', name: 'app_document_filter', methods: ['GET'])]
    public function filter(Request $request): Response
    {

        $data = new SearchDocument();
        $data->page = $request->get('page', 1);

        $form = $this->createForm(SearchDocumentType::class, $data);
        $form->handleRequest($request);

        $user = $this->security->getUser();
        $roles = $user->getRoles();
        $document = [];
        if ($form->isSubmitted() && $form->isValid() && !$form->isEmpty()) {

            if (in_array('ROLE_DEV',  $roles, true) || in_array('ROLE_ADMIN',  $roles, true)) {
                // admi peut chercher toutes les prospects
                $prospect = $this->documentRepository->findSearch($data);
            } else if (in_array('ROLE_CHEF',  $roles, true)) {
                // chef peut chercher toutes les prospects atacher a leur equipe
                $prospect = $this->documentRepository->findAllChefSearch($data, $user);
            } elseif (in_array('ROLE_USER',  $roles, true)) {
                // cmrcl peut chercher seulement les prospects atacher a lui
                $prospect = $this->documentRepository->findByUserAffecterCmrcl($data, $user);
            }

            return $this->render('document/index.html.twig', [
                'documents' => $document,
                'search_form' => $form->createView()

            ]);
        }
        return $this->render('document/search.html.twig', [
            'documents' => $document,
            'search_form' => $form->createView()
        ]);
    }




    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    // pour ajouter un document à un contrat a partir d 'un button dans la page contrat
    #[Route('/{id}/new', name: 'app_document_newdoc', methods: ['GET', 'POST'])]
    public function newdoc(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $document = new Document();

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document->setContrat($contrat);
            $contrat->setDocument($document);
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $document->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }
}
