<?php

namespace App\Controller;

use \DateTime;
use App\Entity\Appel;
use App\Entity\Prospect;
use App\Repository\AppelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\CacheInterface;

class RingoverController extends AbstractController
{

    public function __construct(
        private  EntityManagerInterface $entityManager,
        private CacheInterface $cache,
        private LoggerInterface $logger
    ) {}


    #[Route('/ringover', name: 'ringover', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY', message: "Tu ne peux pas accéder à cette ressource")]
    public function fetchRingoverData(): Response
    {
        $client = HttpClient::create();

        // Utiliser la classe \DateTime de PHP
        $currentDate = new \DateTime();
        $lastDate = (clone $currentDate)->modify('-15 days');

        $response = $client->request('GET', 'https://public-api.ringover.com/v2/calls', [
            'headers' => [
                'Authorization' => '926b7a524bba92932bb5f324222cb1c9f461908d', // Assurez-vous du format
            ],
            'query' => [
                'start_date' => $lastDate->format('Y-m-d\TH:i:s.u\Z'),
                'end_date' => $currentDate->format('Y-m-d\TH:i:s.u\Z'),
                'limit_count' => 00, // Facultatif : ajuster selon les besoins
            ],
        ]);

        // Vérifiez si la requête a échoué et affichez le message d'erreur
        if ($response->getStatusCode() !== 200) {
            $statusCode = $response->getStatusCode();
            $errorContent = $response->getContent(false); // Ne pas lancer d'exception pour les codes d'erreur HTTP
            $headers = $response->getHeaders(false);

            // Rendre les détails de l'erreur pour le débogage
            return new Response(
                "<h1>Error</h1>" .
                    "<p>Status Code: $statusCode</p>" .
                    "<p>Content: $errorContent</p>" .
                    "<p>Headers: " . json_encode($headers) . "</p>",
                $statusCode
            );
        }

        $data = $response->toArray(); // Convertit la réponse JSON en tableau associatif

        return $this->render('ringover/index.html.twig', [
            'ringoverData' => $data,
        ]);
    }


    function stockerRingoverDb(AppelRepository $appelRepository)
    {
        $currentDate = new \DateTime();
        $lastDate = (clone $currentDate)->modify('-15 days');

        $data = $this->getRingoverCalls($lastDate, $currentDate);

        // Traiter les données reçues de Ringover 
        $this->processRingoverData($data, $appelRepository);
    }


    #[Route('/{id}/getApplShow', name: 'get_appl_show', methods: ['GET'])]
    function getCallsShow(AppelRepository $appelRepository,  Request $request): Response
    {

        $appel = $appelRepository->findAllOrderedByStartTime();


        return $this->render('partials/_show_apl.html.twig', [
            'appel' => $appel,
        ]);
    }


    #[Route('/appels/{id}', name: 'app_prospect_calls', methods: ['GET'])]
    public function showCalls(Prospect $prospect, AppelRepository $appelRepository): Response
    {
        try {
            $currentDate = new \DateTime();
            $lastDate = (clone $currentDate)->modify('-2 days');

            // Debug: Log les informations du prospect
            $this->logger->info('Chargement des appels pour le prospect', [
                'prospect_id' => $prospect->getId(),
                'prospect_phone' => $prospect->getPhone(),
                'prospect_gsm' => $prospect->getGsm(),
            ]);

            // Essayer de récupérer les données depuis le cache ou l'API
            $data = $this->cache->get('ringover_calls_' . $prospect->getId(), function () use ($lastDate, $currentDate) {
                try {
                    return $this->getRingoverCalls($lastDate, $currentDate);
                } catch (\Exception $e) {
                    $this->logger->error('Erreur API Ringover: ' . $e->getMessage());
                    return ['call_list' => []];
                }
            });

            // Traiter les données seulement si elles existent
            if (!empty($data['call_list'])) {
                $this->processRingoverData($data, $appelRepository);
            }

            // Récupérer les numéros du prospect
            $prospectPhone = $prospect->getPhone();
            $prospectGsm = $prospect->getGsm();

            // Chercher les appels pour ce prospect en utilisant ses deux numéros
            $appels = $appelRepository->findByProspectNumbers($prospectPhone, $prospectGsm);

            $this->logger->info('Résultats de recherche d\'appels pour le prospect', [
                'prospect_id' => $prospect->getId(),
                'prospect_phone' => $prospectPhone,
                'prospect_gsm' => $prospectGsm,
                'appels_found' => count($appels),
            ]);

            return $this->render('prospect/calls.html.twig', [
                'prospect' => $prospect,
                'appels' => $appels,
                'debug_info' => [
                    'prospect_phone' => $prospectPhone,
                    'prospect_gsm' => $prospectGsm,
                    'appels_found' => count($appels),
                ]
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur dans showCalls: ' . $e->getMessage());

            return $this->render('prospect/calls.html.twig', [
                'prospect' => $prospect,
                'appels' => [],
                'error' => 'Impossible de récupérer les appels. ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Méthode privée pour récupérer les appels depuis l'API Ringover
     */
    private function getRingoverCalls(\DateTime $startDate, \DateTime $endDate): array
    {
        $client = HttpClient::create([
            'timeout' => 10, // Timeout de 10 secondes
            'max_redirects' => 3,
        ]);

        try {
            $response = $client->request('GET', 'https://public-api.ringover.com/v2/calls', [
                'headers' => [
                    'Authorization' => 'Bearer 926b7a524bba92932bb5f324222cb1c9f461908d', // Ajout de 'Bearer'
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'start_date' => $startDate->format('Y-m-d\TH:i:s\Z'), // Format simplifié
                    'end_date' => $endDate->format('Y-m-d\TH:i:s\Z'),
                    'limit_count' => 100,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException('Erreur API Ringover: HTTP ' . $response->getStatusCode());
            }

            return $response->toArray();
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des données Ringover', [
                'error' => $e->getMessage(),
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
            ]);

            // Relancer l'exception pour qu'elle soit gérée par la méthode appelante
            throw $e;
        }
    }

    /**
     * Méthode pour traiter les données reçues de Ringover
     */
    private function processRingoverData(array $data, AppelRepository $appelRepository): void
    {
        if (!isset($data['call_list']) || !is_array($data['call_list'])) {
            return;
        }

        try {
            foreach ($data['call_list'] as $callData) {
                // Validation des données requises
                if (!isset($callData['from_number']) || !isset($callData['to_number']) || !isset($callData['start_time'])) {
                    continue; // Ignorer les appels avec des données manquantes
                }

                $contactName = $callData['user']['concat_name'] ?? null;
                $startTime = new \DateTime($callData['start_time']);

                $existingCall = $appelRepository->findByUniqueProperties(
                    $callData['from_number'],
                    $callData['to_number'],
                    $startTime
                );

                if (!$existingCall) {
                    $appel = new Appel();
                    $appel->setFromNumber($callData['from_number'])
                        ->setToNumber($callData['to_number'])
                        ->setContactName($contactName)
                        ->setStartTime($startTime)
                        ->setEndTime(isset($callData['end_time']) ? new \DateTime($callData['end_time']) : null)
                        ->setDuration(isset($callData['total_duration']) ? (int)$callData['total_duration'] : null)
                        ->setRecordUrl($callData['record'] ?? null);

                    $this->entityManager->persist($appel);
                }
            }

            $this->entityManager->flush();
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors du traitement des données Ringover: ' . $e->getMessage());
            // Ne pas relancer l'exception pour ne pas interrompre l'affichage
        }
    }
}
