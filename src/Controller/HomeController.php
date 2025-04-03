<?php

namespace App\Controller;

use App\Repository\ProspectRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private ProspectRepository $prospectRepository,
        private UserRepository $userRepository,
        private  TeamRepository $teamRepository,
        private StatsService $statsService,
        private Security $security,
    ) {}

    #[Route('/', name: 'app_home', methods: ["GET"])]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/test', name: 'app_test', methods: ["GET"])]
    public function indextest(): Response
    {
        return $this->render('home/index.html.twig');
    }



    #[Route('/aide', name: 'aide_show', methods: ["GET"])]
    public function readPdfFile(): BinaryFileResponse
    {
        // Chemin relatif à partir du répertoire `public`
        $user = $this->security->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {

            $filePath = $this->getParameter('kernel.project_dir') . '/public/documents/ModOP.pdf';
        } else if (in_array('ROLE_CHEF', $user->getRoles(), true)) {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/documents/ModOPcommercial.pdf';
        } else if (in_array('ROLE_COMMERC', $user->getRoles(), true)) {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/documents/ModOPcommercial.pdf';
        } else {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/documents/ModOPcommercial.pdf';
        }
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist');
        }
        // return $this->render('pdf_view.html.twig', ['filePath' => $filePath]);
        // Return the BinaryFileResponse for displaying the PDF inline
        return new BinaryFileResponse($filePath, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }

    #[Route('/bibliotique', name: 'red_bebltq', methods: ["GET"])]
    public function readbebliotique(): BinaryFileResponse
    {


        $filePath = $this->getParameter('kernel.project_dir') . '/public/documents/regles_soucriptionTPM.pdf';
        chmod($filePath, 0444); // Lecture seule, pas d'écriture
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist');
        }
        return new BinaryFileResponse($filePath, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }

    #[Route('/bibliotique-garage', name: 'bebltq_garage', methods: ["GET"])]
    public function readbebliotiqueGarage(): Response
    {

        $filePath = $this->getParameter('kernel.project_dir') . '/public/images/screngarage.png';
        chmod($filePath, 0444); // Lecture seule, pas d'écriture
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist');
        }
        // Charger l'image
        $image = imagecreatefrompng($filePath);
        $newWidth = 2000; // Largeur agrandie
        $newHeight = 1400; // Hauteur agrandie

        $resizedImage = imagescale($image, $newWidth, $newHeight);

        ob_start();
        imagepng($resizedImage);
        $imageData = ob_get_clean();

        return new Response($imageData, 200, [
            'Content-Type' => 'image/png',
        ]);
    }
}
