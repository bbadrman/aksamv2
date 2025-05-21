<?php

namespace App\Repository;

use App\Entity\Compartenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Compartenaire>
 */
class CompartenaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compartenaire::class);
    }


    // public function countContratsByPartenaire(): array
    // {
    //     return $this->createQueryBuilder('p')
    //         ->select(
    //             'p as partenaire',
    //             'COUNT(c.id) as contratCount',
    //             'SUM(pay.frais) as totalFrais'
    //         )
    //         ->leftJoin('p.contrats', 'c')
    //         ->leftJoin('c.payments', 'pay') // Jointure avec la table Payment
    //         ->groupBy('p.id')
    //         ->getQuery()
    //         ->getResult();
    // }
    //on peut return comme ca:
    // {% for result in partenaires %}
    // 			<tr>
    // 				<td>{{ result.partenaire.nom }}</td>

    // 				{# Accès à la propriété nom via l'objet partenaire #}
    // 				<td>{{ result.contratCount }}</td>
    // 				<td>{{ result.totalFrais|default(0)|number_format(2, ',', ' ') }}
    // 					€</td>
    // 			</tr>
    // 		{% endfor %}
    //    /**
    //     * @return Compartenaire[] Returns an array of Compartenaire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Compartenaire
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
