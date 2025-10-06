<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PhpinfoController extends AbstractController
{
    public function __construct( 
        private EntityManagerInterface $entityManager, 
    ) {}



    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/moatayatconfigaksam', name: 'app_motayatconfg')]

    public function phpinfo(): Response
    {
        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();

        return new Response($phpinfo);
    }

    // src/Controller/TestController.php
#[Route('/test-perf', name: 'test_perf')]
public function testPerf(): Response
{
    $start = microtime(true);
    
    // Test 1: Simple response
    $simple = microtime(true) - $start;
    
    // Test 2: Database query
    $dbStart = microtime(true);
    $this->entityManager->getConnection()->executeQuery('SELECT 1');
    $db = microtime(true) - $dbStart;
    
    return new JsonResponse([
        'simple_response' => $simple,
        'database_query' => $db,
        'total' => microtime(true) - $start
    ]);
}
}
