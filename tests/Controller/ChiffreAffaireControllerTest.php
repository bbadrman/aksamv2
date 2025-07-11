<?php

namespace App\Tests\Controller;

use App\Controller\ChiffreAffaireController;
use App\Entity\User;
use App\Entity\Contrat;
use App\Entity\Payment;
use App\Entity\Transaction;
use App\Repository\UserRepository;
use App\Repository\PaymentRepository;
use App\Repository\TransactionRepository;
use App\Repository\ClientRepository;
use App\Repository\ContratRepository;
use App\Repository\UserRepository as AppUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

class ChiffreAffaireControllerTest extends TestCase
{
    public function testAfficherByCalendrieAvecDates()
    {
        // Mocks
        $request = new Request([
            'date_from' => '2024-01-01',
            'date_to' => '2024-01-31'
        ]);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getCurrentRequest')->willReturn($request);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $security = $this->createMock(Security::class);
        $authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $clientRepo = $this->createMock(ClientRepository::class);
        $contratRepo = $this->createMock(ContratRepository::class);
        $userRepo = $this->createMock(AppUserRepository::class);
        $transactionRepo = $this->createMock(TransactionRepository::class);
        $paymentRepo = $this->createMock(PaymentRepository::class);

        // Mock utilisateur
        $user = $this->createMock(User::class);
        $contrat = $this->createMock(Contrat::class);
        $payment = $this->createMock(Payment::class);
        $transaction = new Transaction();
        $transaction->setTransaction('TX123');
        $transaction->setCredit(200);

        $payment->method('getDatePayment')->willReturn(new \DateTime('2024-01-10'));
        $payment->method('getTransaction')->willReturn('TX123');
        $payment->method('getMontant')->willReturn("150");

        $contrat->method('getPayments')->willReturn($payment);
        $user->method('getContrats')->willReturn(new ArrayCollection([$contrat]));

        $userRepo->method('findByClientValidWithCreatAtRange')->willReturn([$user]);

        $transactionRepo->method('createQueryBuilder')->willReturnCallback(function () {
            $qb = $this->getMockBuilder(\Doctrine\ORM\QueryBuilder::class)
                ->disableOriginalConstructor()
                ->getMock();

            $qb->method('select')->willReturnSelf();
            $qb->method('where')->willReturnSelf();
            $qb->method('getQuery')->willReturnSelf();
            $qb->method('getResult')->willReturn([
                ['transaction' => 'TX123']
            ]);

            return $qb;
        });

        $transactionRepo->method('findOneBy')->willReturn($transaction);

        // Contrôleur
        $controller = new ChiffreAffaireController(
            $requestStack,
            $entityManager,
            $security,
            $clientRepo,
            $contratRepo,
            $userRepo,
            $authorizationChecker
        );

        // Appel de la méthode
        $response = $controller->afficherbycalendrie(
            $request,
            $userRepo,
            $transactionRepo,
            $paymentRepo,
            $entityManager
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('text/html', $response->headers->get('Content-Type') ?? '');
    }
}
