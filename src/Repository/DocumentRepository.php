<?php

namespace App\Repository;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Document;
use App\Search\SearchDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Document>
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private  PaginatorInterface $paginator, private EntityManagerInterface $manager)
    {
        parent::__construct($registry, Document::class);
    }


    /**
     * Find list a prospect by a all search form
     * @param SearchDocument $search
     * @return PaginationInterface
     */
    public function findSearch(SearchDocument $search): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('d')
            ->addSelect('c', 'u')
            ->leftJoin('d.contrat', 'c')
            ->leftJoin('c.user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', 5); // ID d'un utilisateur existant

        dump($query->getQuery()->getResult());

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /***
     * Applique les filtres de recherche à la requête.
     *
     * @param QueryBuilder $query
     * @param SearchDocument $search
     * @return QueryBuilder
     */
    private function applySearchFilters(QueryBuilder $query, SearchDocument $search): QueryBuilder
    {

        if ($search->contrat) {
            $query->andWhere('d.contrat = :contrat')
                ->setParameter('contrat', $search->contrat);
        }

        if ($search->user) {
            $query->andWhere('c.user = :user')
                ->setParameter('user', $search->user);
        }

        return $query;
    }
}
