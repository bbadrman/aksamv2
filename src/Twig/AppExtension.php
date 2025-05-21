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
    private $mappings = [
        'url' => [

            '1' => 'des-vtc',
            '2' => 'garage-pro',
            '3' => 'pour-taxi',
            '4' => 'pour-vtc',
            '5' => 'des-resilies',
            '6' => 'decennale',
            '7' => 'comparez',
            '8' => 'camion',
            '9' => 'flotte',
            '10' => 'vehicule-pro',
            '11' => 'transporteurs',
            '12' => 'vehicules-prof',
            '13' => 'engins',
            '14' => 'prof-auto',
            '15' => 'auto-ecole',
            '16' => 'negociants-auto',
            '17' => 'garage-auto',
        ],
        'activites' => [

            1 => 'TPM',
            2 => 'VTC',
            3 => 'Sociéte',
            4 => 'Décenale',
            5 => 'Dommage',
            6 => 'Marchandise',
            7 => 'Négociant',
            8 => 'Prof auto',
            9 => 'Garage'
        ],
        'typeProspect' => [
            '1' => 'Particulier',
            '2' => 'Professionnel'
        ],
        'source' => [
            '1' => 'Saisie manuelle',
            '2' => 'Revendeur',
            '3' => 'Propre site',

        ],
        'relance' => [
            '1' => 'Rendez-vous',
            '2' => 'Injoignable',
            '3' => 'Attente DOC',
            '4' => 'Tarification',
            '5' => 'Prise de Décision',
            '6' => 'Faux Fiche',
            '7' => 'Doublon',
            '8' => 'Passage Concurrent',
            '9' => 'Passage Client',
            '10' => 'Déjà Souscrit',
            '11' => 'Toujour Injoignable',
            '12' => 'Tarification',
            '13' => 'Test',
        ]
    ];

    public function getFilters(): array
    {
        return [
            new TwigFilter('credit_by_transaction', [$this, 'getCreditByTransaction']),
            new TwigFilter('frais_par_contrat', [$this, 'getFraisParContratThisMonth']),
            new TwigFilter('is_transaction_declared', [$this, 'isTransactionDeclaredInPayments']),
            //filtre du affichage du usl et activite stat/prospets.htl.twg
            new TwigFilter('map_choice', [$this, 'mapChoice']),

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
    public function isTransactionDeclaredInPayments(Contrat $contrat, string $transactionNumber): bool
    {
        foreach ($contrat->getPayments() as $payment) {
            if (
                $payment->getTransaction() === $transactionNumber ||
                $payment->getTransaction1() === $transactionNumber ||
                $payment->getTransaction2() === $transactionNumber ||
                $payment->getTransaction3() === $transactionNumber
            ) {
                return true;
            }
        }

        return false;
    }
    public function mapChoice($value, string $mappingKey)
    {
        return $this->mappings[$mappingKey][$value] ?? $value;
    }
}
