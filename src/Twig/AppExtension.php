<?php

namespace App\Twig;

use App\Entity\Contrat;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Repository\TransactionRepository;

class AppExtension extends AbstractExtension
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('credit_by_transaction', [$this, 'getCreditByTransaction']),
            new TwigFilter('frais_par_contrat', [$this, 'getFraisParContratThisMonth']),
        ];
    }

    public function getCreditByTransaction(string $transaction): ?string
    {
        $transactionEntity = $this->transactionRepository->findOneBy(['transaction' => $transaction]);

        return $transactionEntity ? $transactionEntity->getCredit() : null;
    }


    public function getFraisParContratThisMonth(Contrat $contrat): float
    {
        $now = new \DateTime();
        $start = (clone $now)->modify('first day of this month')->setTime(0, 0);
        $end = (clone $start)->modify('+1 month');

        $total = 0;
        foreach ($contrat->getSavs() as $frais) {
            if ($frais->getDateAjout() >= $start && $frais->getDateAjout() < $end) {
                $total += $frais->getMontant();
            }
        }

        return $total;
    }
}
