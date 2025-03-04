<?php

namespace App\Repository;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Prospect;
use App\Entity\User;
use App\Search\SearchProspect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Prospect>
 */
class ProspectTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator, private EntityManagerInterface $manager)
    {
        parent::__construct($registry, Prospect::class);
    }




    /**
     * Applique les filtres de recherche à la requête.
     *
     * @param QueryBuilder $query
     * @param SearchProspect $search
     * @return QueryBuilder
     */
    private function applySearchFilters(QueryBuilder $query, SearchProspect $search): QueryBuilder
    {
        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->m)) {
            $query = $query
                ->andWhere('p.lastname LIKE :m')
                ->setParameter('m', "%{$search->m}%");
        }

        if (!empty($search->r)) {
            $query = $query
                ->andWhere('f.username LIKE :r')
                ->setParameter('r', "%{$search->r}%");
        }

        if (!empty($search->g)) {
            $query = $query
                ->andWhere('p.email LIKE :g')
                ->setParameter('g', "%{$search->g}%");
        }

        if (!empty($search->team)) {
            $query = $query
                ->andWhere('t.name LIKE :team')
                ->setParameter('team', "%{$search->team}%");
        }

        if (!empty($search->l)) {
            $query = $query
                ->andWhere('p.phone LIKE :l')
                ->orWhere('p.gsm LIKE :l')
                ->setParameter('l', "%{$search->l}%");
        }

        if (!empty($search->c)) {
            $query = $query
                ->andWhere('p.city LIKE :c')
                ->setParameter('c', "%{$search->c}%");
        }

        if (!empty($search->d) && $search->d instanceof \DateTime) {
            $query = $query
                ->andWhere('p.creatAt >= :d')
                ->setParameter('d', $search->d);
        }

        if (!empty($search->dd) && $search->dd instanceof \DateTime) {
            $search->dd->setTime(23, 59, 59);
            $query = $query
                ->andWhere('p.creatAt <= :dd')
                ->setParameter('dd', $search->dd);
        }

        if (!empty($search->s)) {
            $query = $query
                ->andWhere('p.raisonSociale LIKE :s')
                ->setParameter('s', "%{$search->s}%");
        }

        if (!empty($search->source)) {
            $query = $query
                ->andWhere('p.source = :source')
                ->setParameter('source', $search->source);
        }

        return $query;
    }

    /**
     * Find list a prospect non traité (qui ne sont pas de motif relance et déjà affectés au team et cmrcl)
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findProspectNonTraiter(SearchProspect $search): PaginationInterface
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59);

        $query = $this->createQueryBuilder('p')
            ->select('p, t, f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->where('p.team IS NOT NULL')
            ->andWhere('p.relance IS NULL')
            ->andWhere('p.creatAt <= :yesterday')
            ->setParameter('yesterday', $yesterday)
            ->orderBy('p.id', 'DESC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * Find list a prospect non traité (qui ne sont pas de motif relance et déjà affectés au team et cmrcl)
     * @param SearchProspect $search
     * @param User $user
     * @return PaginationInterface
     */
    public function findProspectNonTraiterChef(SearchProspect $search, User $user): PaginationInterface
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59);

        $teams = $user->getTeams();
        if ($teams->isEmpty()) {
            return [];
        }

        $query = $this->createQueryBuilder('p')
            ->select('p, f, r')
            ->where('p.team IN (:teams)')
            ->setParameter('teams', $teams)
            ->andWhere('p.team IS NOT NULL')
            ->andWhere('p.relance IS NULL')
            ->leftJoin('p.histories', 'h')
            ->andWhere('h.actionDate <= :endOfYesterday')
            ->setParameter('endOfYesterday', $yesterday)
            ->leftJoin('p.comrcl', 'f')
            ->orderBy('p.id', 'DESC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * Find list a prospect non traité (qui ne sont pas de motif relance et déjà affectés au team et cmrcl)
     * @param SearchProspect $search
     * @param int $id
     * @return PaginationInterface
     */
    public function findProspectNonTraiterCmrcl(SearchProspect $search, $id): PaginationInterface
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59);

        $query = $this->createQueryBuilder('p')
            ->select('p, r')
            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)
            ->andWhere('p.relance IS NULL')
            ->leftJoin('p.histories', 'h')
            ->andWhere('h.actionDate <= :endOfYesterday')
            ->setParameter('endOfYesterday', $yesterday)
            ->orderBy('p.id', 'DESC');

        $this->applyFilters($query, $search);

        return $this->paginate($query, $search->page);
    }

    /**
     * Applique les filtres de recherche sur la requête
     */
    private function applyFilters(QueryBuilder $query, SearchProspect $search): void
    {
        $filters = [
            'q' => ['p.nom', 'LIKE'],
            'm' => ['p.prenom', 'LIKE'],
            'r' => ['f.username', 'LIKE'],
            'g' => ['p.email', 'LIKE'],
            'team' => ['t.name', 'LIKE'],
            'l' => [['p.phone', 'LIKE'], ['p.gsm', 'LIKE']],
            'c' => ['p.city', 'LIKE'],
            's' => ['p.raisonSociale', 'LIKE'],
            'source' => ['p.source', '='],
        ];

        foreach ($filters as $key => $conditions) {
            $value = $search->{$key};
            if (!empty($value)) {
                if (is_array($conditions[0])) {
                    $query->andWhere(
                        $query->expr()->orX(...array_map(fn($c) => $c[0] . ' ' . $c[1] . ' :' . $key, $conditions))
                    );
                } else {
                    [$field, $operator] = $conditions;
                    $query->andWhere("$field $operator :$key");
                }
                $query->setParameter($key, $operator === 'LIKE' ? "%$value%" : $value);
            }
        }

        if (!empty($search->d) && $search->d instanceof \DateTime) {
            $query->andWhere('p.creatAt >= :d')->setParameter('d', $search->d);
        }

        if (!empty($search->dd) && $search->dd instanceof \DateTime) {
            $search->dd->setTime(23, 59, 59);
            $query->andWhere('p.creatAt <= :dd')->setParameter('dd', $search->dd);
        }
    }


    /**
     * Récupère la date d'hier à 23:59:59
     */
    private function getYesterday(): \DateTime
    {
        return (new \DateTime())->modify('-1 day')->setTime(23, 59, 59);
    }

    /**
     * Gère la pagination
     */
    private function paginate(QueryBuilder $query, int $page): PaginationInterface
    {
        return $this->paginator->paginate($query, $page, 10);
    }
}
