<?php

namespace App\Repository;

use App\Entity\User;
use App\Search\SearchUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, private  PaginatorInterface $paginator, private EntityManagerInterface $manager)
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

    /**
     * Find list a user by a search form
     * @param SearchUser $search
     * @return PaginationInterface
     */
    public function findSearchUser(SearchUser $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('u')
            ->addSelect('u, t ')
            // joiner les tables en relation ManyToOne avec team
            ->leftJoin('u.teams', 't')
            //relation manytomany avec product apartir team
            ->leftJoin('u.products', 'p')
            ->orderBy('u.id', 'asc');

        if (!empty($search->q)) {
            $query = $query
                ->Where('u.firstname LIKE :q')
                ->orWhere('u.username LIKE :q')
                ->orWhere('u.lastname LIKE :q')
                ->orWhere('u.remuneration LIKE :q')
                ->orWhere('u.embuchAt LIKE :q')
                ->orWhere('u.fonction LIKE :q')
                // join les tables              
                ->orWhere('t.name LIKE :q')
                ->orWhere('p.nom LIKE :q')

                ->orWhere('u.status LIKE :q')
                ->orderBy('u.id', 'desc')
                ->setParameter('q', "%{$search->q}%");
        }

        if (isset($search->status)) {
            $query = $query
                ->andWhere('u.status = :status')
                ->setParameter('status', $search->status);
        }
        return $this->paginator->paginate(
            $query,
            $search->page,
            8

        );
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


    public function findWithFilters(SearchUser $search): QueryBuilder
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.teams', 't')
            ->leftJoin('u.contrats', 'c')
            ->addSelect('t')
            ->addSelect('c');

        if ($search->team) {
            $qb->andWhere('t.name LIKE :team')
                ->setParameter('team', '%' . $search->team . '%');
        }

        if ($search->username) {
            $qb->andWhere('u.username LIKE :username')
                ->setParameter('username', '%' . $search->username . '%');
        }

        if ($search->contrat) {
            $qb->andWhere('c.nom LIKE :contrat')
                ->setParameter('contrat', '%' . $search->contrat . '%');
        }

        if ($search->dateMin) {
            $qb->andWhere('c.dateSouscrpt >= :dateMin')
                ->setParameter('dateMin', $search->dateMin->format('Y-m-d 00:00:00'));
        }

        if ($search->dateMax) {
            $qb->andWhere('c.dateSouscrpt <= :dateMax')
                ->setParameter('dateMax', $search->dateMax->format('Y-m-d 23:59:59'));
        }

        return $qb->orderBy('u.id', 'ASC');
    }

    // src/Repository/UserRepository.php

    public function findAllWithProspects(): array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.prospects', 'p')
            ->leftJoin('u.teams', 't')
            ->addSelect('p', 't')
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
