<?php

namespace App\Repository;

use App\Entity\UserHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserHistory>
 */
class UserHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserHistory::class);
    }
    // src/Entity/User.php
    public function getPartenairesStats(): array
    {
        $stats = [];

        foreach ($this->contrats as $contrat) {
            $partenaire = $contrat->getPartenaire();
            if ($partenaire) {
                $nom = $partenaire->getNom();
                if (!isset($stats[$nom])) {
                    $stats[$nom] = [
                        'count' => 0,
                        'cotisation' => 0.0,
                        'partenaire' => $partenaire
                    ];
                }
                $stats[$nom]['count']++;
                $stats[$nom]['cotisation'] += (float)$contrat->getCotisation();
            }
        }

        ksort($stats);
        return $stats;
    }

    //    /**
    //     * @return UserHistory[] Returns an array of UserHistory objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserHistory
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
