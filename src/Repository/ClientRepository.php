<?php

namespace App\Repository;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Client;
use App\Entity\User;
use App\Search\SearchClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private  PaginatorInterface $paginator, private EntityManagerInterface $manager)
    {
        parent::__construct($registry, Client::class);
    }


    /**
     * Find a list of clients using a search form
     * @param SearchClient $search
     * @return PaginationInterface
     */
    public function findClientAll(SearchClient $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('c')
            ->addSelect('c, h, b, r')
            ->where('c.status = 1 ')
            ->leftJoin('c.team', 'b')
            ->leftJoin('c.cmrcl', 'h')
            ->leftJoin('c.contrats', 'r')
            ->andWhere('r.status = 1')
            ->orderBy('c.id', 'DESC');


        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * Find a list of clients using a search form
     * @param SearchClient $search
     * @return PaginationInterface
     */
    public function findClientAdmin(SearchClient $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('c')
            ->addSelect('h, b')
            ->where('c.status = 2 OR c.status IS NULL')
            ->leftJoin('c.team', 'b')
            ->leftJoin('c.cmrcl', 'h')
            ->orderBy('c.id', 'DESC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }
    /**
     * Find list a client by a all search form
     * @param SearchClient $search
     * @return PaginationInterface
     */
    public function findClientChef(SearchClient $search, User $user): PaginationInterface
    {

        $teams = $user->getTeams();
        if ($teams->isEmpty()) {
            return $this->paginator->paginate([], 1, 10);
        }

        $query = $this->createQueryBuilder('c')
            ->select('c, f, t ')
            ->where('c.status = 2 OR c.status IS NULL')
            ->leftJoin('c.cmrcl', 'f')
            ->leftJoin('c.team', 't')
            ->andwhere('c.team IN (:teams)')
            ->setParameter('teams', $teams)

            ->orderBy('c.id', 'DESC');


        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * Find list a client by a all search form
     * @param SearchClient $search
     * @return PaginationInterface
     */
    public function findClientCmrcl(SearchClient $search, $id): PaginationInterface
    {

        $query = $this->createQueryBuilder('c')
            ->select('c,  b')
            ->where('c.status = 2 OR c.status IS NULL')
            ->leftJoin('c.team', 'b')

            ->andWhere('c.cmrcl = :val')
            ->setParameter('val', $id)

            ->orderBy('c.id', 'DESC');
        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }


    /**
     * Applique les filtres de recherche à la requête.
     *
     * @param QueryBuilder $query
     * @param SearchClient $search
     * @return QueryBuilder
     */
    private function applySearchFilters(QueryBuilder $query, SearchClient $search): QueryBuilder
    {
        if (!empty($search->f)) {
            $query = $query
                ->andWhere('c.nom LIKE :f')
                ->setParameter('f', "%{$search->f}%");
        }
        if (!empty($search->l)) {
            $query = $query
                ->andWhere('c.prenom LIKE :l')
                ->setParameter('l', "%{$search->l}%");
        }
        if (!empty($search->team)) {
            $query = $query
                ->andWhere('b.name LIKE :team')
                ->setParameter('team', "%{$search->team}%");
        }
        if (!empty($search->r)) {
            $query = $query
                ->andWhere('c.raisonSociale LIKE :r')
                ->setParameter('r', "%{$search->r}%");
        }
        if (!empty($search->d) && $search->d instanceof \DateTime) {
            $query = $query
                ->andWhere('c.creatAt >= :d')
                ->setParameter('d', $search->d);
        }
        if (!empty($search->dd) && $search->dd instanceof \DateTime) {
            $search->dd->setTime(23, 59, 59);
            $query = $query
                ->andWhere('c.creatAt <= :dd')
                ->setParameter('dd', $search->dd);
        }
        if (!empty($search->k)) {
            $query = $query
                ->andWhere('h.username LIKE :k')
                ->setParameter('k', "%{$search->k}%");
        }
        if (!empty($search->g)) {
            $query = $query
                ->andWhere('c.email LIKE :g')
                ->setParameter('g', "%{$search->g}%");
        }
        if (!empty($search->dr) && $search->dr instanceof \DateTime) {
            $query = $query
                ->andWhere('r.dateSouscrpt >= :dr')
                ->setParameter('dr', $search->dr->format('Y-m-d'));
        }
        if (!empty($search->ddr) && $search->ddr instanceof \DateTime) {
            $search->ddr->setTime(23, 59, 59);
            $query = $query
                ->andWhere('r.dateSouscrpt <= :ddr')
                ->setParameter('ddr', $search->ddr->format('Y-m-d H:i:s'));
        }
        if (!empty($search->t)) {
            $query = $query
                ->orWhere('c.phone LIKE :t')
                ->setParameter('t', "%{$search->t}%");
        }

        return $query;
    }
}
