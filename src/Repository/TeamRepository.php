<?php

namespace App\Repository;

use App\Entity\Compartenaire;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }
    public function findFiltered(
        ?Team $team,
        ?User $user,
        ?Compartenaire $partenaire,
        ?\DateTime $startDate,
        ?\DateTime $endDate
    ) {
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.users', 'u')
            ->leftJoin('u.contrats', 'c')
            ->leftJoin('c.partenaire', 'p')
            ->addSelect('u', 'c', 'p'); // Chargement explicite des relations

        // Ajouter les conditions de filtrage
        $conditions = [];

        if ($team) {
            $conditions[] = 't = :team';
            $qb->setParameter('team', $team);
        }

        if ($user) {
            $conditions[] = 'u = :user';
            $qb->setParameter('user', $user);
        }

        if ($partenaire) {
            $conditions[] = 'p = :partenaire';
            $qb->setParameter('partenaire', $partenaire);
        }

        if ($startDate) {
            $conditions[] = 'c.dateSouscrpt >= :startDate';
            $qb->setParameter('startDate', $startDate);
        }

        if ($endDate) {
            $conditions[] = 'c.dateSouscrpt <= :endDate';
            $qb->setParameter('endDate', $endDate);
        }

        if (!empty($conditions)) {
            $qb->andWhere(implode(' AND ', $conditions));
        }

        return $qb->getQuery()->getResult();
    }
    //    /**
    //     * @return Team[] Returns an array of Team objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Team
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
