<?php

namespace App\Repository;

use App\Entity\Appel;
use App\Entity\Prospect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appel>
 */
class AppelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appel::class);
    }

    // AppelRepository.php

    public function findByUniqueProperties(string $fromNumber, string $toNumber, \DateTime $startTime): ?Appel
    {
        return $this->findOneBy([
            'fromNumber' => $fromNumber,
            'toNumber' => $toNumber,
            'startTime' => $startTime,
        ]);
    }

    // Méthode pour récupérer tous les appels ordonnés par heure de début
    public function findAllOrderedByStartTime()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.startTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour rechercher par numéro de téléphone exact
    public function findByPhoneNumber(string $phoneNumber)
    {
        return $this->createQueryBuilder('a')
            ->where('a.fromNumber = :phone OR a.toNumber = :phone')
            ->setParameter('phone', $phoneNumber)
            ->orderBy('a.startTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour rechercher par variations du numéro de téléphone
    public function findByPhoneNumberVariations(string $phoneNumber)
    {
        // Nettoyer le numéro (enlever espaces, tirets, etc.)
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phoneNumber);

        // Créer différentes variations du numéro
        $variations = [$phoneNumber, $cleanPhone];

        // Si le numéro commence par +33, ajouter la version avec 0
        if (str_starts_with($cleanPhone, '+33')) {
            $variations[] = '0' . substr($cleanPhone, 3);
        }

        // Si le numéro commence par 0, ajouter la version avec +33
        if (str_starts_with($cleanPhone, '0')) {
            $variations[] = '+33' . substr($cleanPhone, 1);
        }

        // Enlever les doublons
        $variations = array_unique($variations);

        return $this->createQueryBuilder('a')
            ->where('a.fromNumber IN (:variations) OR a.toNumber IN (:variations)')
            ->setParameter('variations', $variations)
            ->orderBy('a.startTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour rechercher par pattern LIKE (plus flexible)
    public function findByPhonePattern(string $phoneNumber)
    {
        // Nettoyer le numéro pour la recherche LIKE
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        $pattern = '%' . $cleanPhone . '%';

        return $this->createQueryBuilder('a')
            ->where('REPLACE(REPLACE(REPLACE(a.fromNumber, " ", ""), "-", ""), "+", "") LIKE :pattern 
                 OR REPLACE(REPLACE(REPLACE(a.toNumber, " ", ""), "-", ""), "+", "") LIKE :pattern')
            ->setParameter('pattern', $pattern)
            ->orderBy('a.startTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour débugger - afficher tous les numéros dans la base
    public function getAllPhoneNumbers()
    {
        return $this->createQueryBuilder('a')
            ->select('DISTINCT a.fromNumber, a.toNumber')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour rechercher les appels d'un prospect par ses numéros phone et gsm
    public function findByProspectNumbers(?string $phone, ?string $gsm)
    {
        $qb = $this->createQueryBuilder('a');

        // Créer un tableau avec tous les numéros possibles
        $phoneNumbers = [];

        if ($phone) {
            $phoneNumbers[] = $phone;
            // Ajouter les variations du numéro phone
            $phoneNumbers = array_merge($phoneNumbers, $this->getPhoneVariations($phone));
        }

        if ($gsm) {
            $phoneNumbers[] = $gsm;
            // Ajouter les variations du numéro gsm
            $phoneNumbers = array_merge($phoneNumbers, $this->getPhoneVariations($gsm));
        }

        // Enlever les doublons
        $phoneNumbers = array_unique($phoneNumbers);

        if (empty($phoneNumbers)) {
            return [];
        }

        return $qb->where('a.fromNumber IN (:phoneNumbers) OR a.toNumber IN (:phoneNumbers)')
            ->setParameter('phoneNumbers', $phoneNumbers)
            ->orderBy('a.startTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Méthode privée pour générer les variations d'un numéro
    private function getPhoneVariations(string $phoneNumber): array
    {
        // Nettoyer le numéro (enlever espaces, tirets, etc.)
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phoneNumber);

        $variations = [$cleanPhone];

        // Si le numéro commence par +33, ajouter la version avec 0
        if (str_starts_with($cleanPhone, '+33')) {
            $variations[] = '0' . substr($cleanPhone, 3);
        }

        // Si le numéro commence par 0, ajouter la version avec +33
        if (str_starts_with($cleanPhone, '0') && strlen($cleanPhone) === 10) {
            $variations[] = '+33' . substr($cleanPhone, 1);
        }

        // Ajouter aussi le numéro avec espaces s'il n'en a pas
        if (strpos($phoneNumber, ' ') === false && strlen($cleanPhone) === 10) {
            $variations[] = substr($cleanPhone, 0, 2) . ' ' . substr($cleanPhone, 2, 2) . ' ' . substr($cleanPhone, 4, 2) . ' ' . substr($cleanPhone, 6, 2) . ' ' . substr($cleanPhone, 8, 2);
        }

        return $variations;
    }
}
