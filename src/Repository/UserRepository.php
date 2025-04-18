<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findComrclByteamOrderedByAscName(User $user): array
    {
        $teams = $user->getTeams();
        if ($teams->isEmpty()) {
            return [];
        }

        return $this->createQueryBuilder('u')
            ->innerJoin('u.teams', 't') // Jointure entre les utilisateurs et les équipes
            ->andWhere('t IN (:teams)') // Condition pour filtrer les utilisateurs par les équipes
            ->setParameter('teams', $teams->toArray()) // Convertir la collection en tableau pour le paramètre
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findByContratValid(): array
    {
        $currentMonth = new \DateTime('first day of this month');

        return $this->createQueryBuilder('u')
            ->addSelect('c')
            ->leftJoin('u.contrats', 'c')
            ->Where('c.status = 1')
            ->andwhere('c.dateSouscrpt >= :startOfMonth')
            ->andWhere('c.dateSouscrpt < :endOfMonth')
            ->setParameter('startOfMonth', $currentMonth->format('Y-m-01'))
            ->setParameter('endOfMonth', $currentMonth->modify('first day of next month')->format('Y-m-01'))
            ->getQuery()
            ->getResult();
    }

    public function findByClientValid(): array
    {
        $currentMonth = new \DateTime('first day of this month');

        return $this->createQueryBuilder('u')
            ->addSelect('c', 'p') // Ajout de 'p' pour les paiements
            ->leftJoin('u.contrats', 'c')
            ->leftJoin('c.payments', 'p') // Jointure avec les paiements
            ->where('c.status = 1')
            ->andWhere('p.creatAt >= :startOfMonth')
            ->andWhere('p.creatAt < :endOfMonth')
            ->setParameter('startOfMonth', $currentMonth->format('Y-m-01'))
            ->setParameter('endOfMonth', $currentMonth->modify('first day of next month')->format('Y-m-01'))
            ->getQuery()
            ->getResult();
    }



    public function findByContratValidBetweenDates(string $start, string $end): array
    {
        return $this->createQueryBuilder('u')
            ->addSelect('c', 'p')
            ->leftJoin('u.contrats', 'c')
            ->leftJoin('c.payments', 'p')
            ->where('c.status = 1')
            ->andWhere('p.creatAt >= :start')
            ->andWhere('p.creatAt < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return User[] Returns an array of User objects
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

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
