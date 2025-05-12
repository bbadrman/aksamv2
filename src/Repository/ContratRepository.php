<?php

namespace App\Repository;

use App\Entity\Contrat;
use App\Search\SearchContrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Contrat>
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private  PaginatorInterface $paginator, private EntityManagerInterface $manager)
    {
        parent::__construct($registry, Contrat::class);
    }


    /**
     * Find a list of contrat using a search form
     * @param SearchContrat $search
     * @return PaginationInterface
     * 
     */
    public function findByContartValid(SearchContrat $search): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->where('c.status = 2 OR c.status IS NULL')
            ->orderBy('c.id', 'DESC');

        if (!empty($search->f)) {
            $queryBuilder
                ->andWhere('c.nom LIKE :f')
                ->setParameter('f', "%{$search->f}%");
        }
        if (!empty($search->l)) {
            $queryBuilder
                ->andWhere('c.prenom LIKE :l')
                ->setParameter('l', "%{$search->l}%");
        }

        if (!empty($search->r)) {
            $queryBuilder
                ->andWhere('c.raisonSociale LIKE :r')
                ->setParameter('r', "%{$search->r}%");
        }
        if (!empty($search->e)) {
            $queryBuilder
                ->andWhere('c.etat LIKE :e')
                ->setParameter('e', "%{$search->e}%");
        }

        $query = $queryBuilder->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * Find a list of contrat using a search form
     * @param SearchContrat $search
     * @return PaginationInterface
     */
    public function findByContartValidComrcl(SearchContrat $search, $id): PaginationInterface
    {


        $queryBuilder = $this->createQueryBuilder('c')
            ->select('c, f ')
            ->where('c.status = 2 OR c.status IS NULL')
            ->leftJoin('c.user', 'f')

            ->andWhere('c.user = :val')
            ->setParameter('val', $id)
            ->orderBy('c.id', 'DESC');

        if (!empty($search->f)) {
            $queryBuilder
                ->andWhere('c.nom LIKE :f')
                ->setParameter('f', "%{$search->f}%");
        }
        if (!empty($search->l)) {
            $queryBuilder
                ->andWhere('c.prenom LIKE :l')
                ->setParameter('l', "%{$search->l}%");
        }

        if (!empty($search->r)) {
            $queryBuilder
                ->andWhere('c.raisonSociale LIKE :r')
                ->setParameter('r', "%{$search->r}%");
        }
        if (!empty($search->e)) {
            $queryBuilder
                ->andWhere('c.etat LIKE :e')
                ->setParameter('e', "%{$search->e}%");
        }

        $query = $queryBuilder->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * Calcule la somme totale des frais
     */
    public function getTotalFrais(): float
    {
        return (float) $this->createQueryBuilder('c')
            ->select('SUM(c.frais)')
            ->Where('c.status = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function countContratsAndTotalFraisByComrclForThisMonth(): array
    {
        $currentMonth = new \DateTime('first day of this month');

        return $this->createQueryBuilder('c')
            ->select('u.id AS userId, u.username AS username, COUNT(c.id) AS contratCount, SUM(c.frais) AS totalFrais, SUM(r.montantReglement) AS totalReglements')
            ->join('c.user', 'u')
            ->leftJoin('c.regelement', 'r') // on récupère les règlements
            ->where('c.dateSouscrpt >= :startOfMonth')
            ->andWhere('c.dateSouscrpt < :endOfMonth')
            ->andWhere('c.status = 1')
            ->setParameter('startOfMonth', $currentMonth->format('Y-m-01'))
            ->setParameter('endOfMonth', $currentMonth->modify('first day of next month')->format('Y-m-01'))
            ->groupBy('u.id')
            ->getQuery()
            ->getResult();
    }


    public function getTotalContratsAndFraisForThisMonth(): array
    {
        $currentMonth = new \DateTime('first day of this month');

        return $this->createQueryBuilder('c')
            ->select('COUNT(DISTINCT c.id) AS totalContrats, SUM(c.frais) AS totalFrais, SUM(r.montantReglement) AS totalReglements')
            ->leftJoin('c.regelement', 'r')
            ->where('c.dateSouscrpt >= :startOfMonth')
            ->andWhere('c.dateSouscrpt < :endOfMonth')
            ->andWhere('c.status = 1')
            ->setParameter('startOfMonth', $currentMonth->format('Y-m-01'))
            ->setParameter('endOfMonth', $currentMonth->modify('first day of next month')->format('Y-m-01'))
            ->getQuery()
            ->getSingleResult();
    }
    public function findByDateInterval(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.dateSouscrpt BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getResult();
    }
}
