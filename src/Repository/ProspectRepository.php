<?php

namespace App\Repository;


use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Prospect;
use App\Entity\User;
use App\Search\SearchProspect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Prospect>
 */
class ProspectRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, private  PaginatorInterface $paginator, private EntityManagerInterface $manager)
    {
        parent::__construct($registry, Prospect::class);
    }
    /**
     * Find list a prospect no traite (qui sont pas de motifrelance et dejat affecter au team et cmrcl)
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findProspectNonTraiter(SearchProspect $search): PaginationInterface
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59);  // il depacer un jour a partir de minuit

        $query = $this->createQueryBuilder('p')

            ->andWhere('p.team IS NOT NULL')  // deja Affecté à une équipe  
            ->andWhere('p.relance IS NULL')  // n'est pas encor relancer 
            // il faut changer creatAt par affectAt 
            ->andWhere('p.creatAt <= :yesterday')
            ->setParameter('yesterday', $yesterday);

        $this->applyFilters($query, $search);

        return $this->paginate($query, $search->page);
    }

    /**
     * Find list a prospect no traite (qui sont pas de motirelance et dejat affecter au team et cmrcl)
     * @param SearchProspect $search
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
            ->andWhere('p.team IS NOT NULL')  // chef d'equipe affecté 
            ->andWhere('p.relance IS NULL')  // n'est pas encor relancer 

            //->andWhere('p.comrcl IS NULL OR p.comrcl = :val') // Filtrer les prospects no affectés et affect au chef aussi
            //->setParameter('val', $user)

            ->leftJoin('p.histories', 'h')
            ->andWhere('h.actionDate <= :endOfYesterday') // Filtre par date d'action de l'historique si actiondate=23/06  <= 24/06 = yesterday alors aujourduit 25/06
            ->setParameter('endOfYesterday', $yesterday)

            ->leftJoin('p.comrcl', 'f')
            ->orderBy('p.id', 'DESC');

        if ((!empty($search->q))) {
            $query = $query
                ->andWhere('p.name LIKE :q')

                ->orderBy('p.id', 'desc')
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

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * Find list a prospect no traite (qui sont pas de motirelance et dejat affecter au team et cmrcl)
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findProspectNonTraiterCmrcl(SearchProspect $search, $id): PaginationInterface
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59); // pour fixer hier a  minuit
        // dd($yesterday); = date: 2024-09-24 23:59:59.0 UTC (+00:00)

        $query = $this->createQueryBuilder('p')
            ->select('p,   r')

            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

            //pas de relance
            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.prospect IS NULL')

            // ->andWhere('p.creatAt <= :yesterday')
            // ->setParameter('yesterday', $yesterday)

            // pas encour passe un jour de la date de history actionDate
            ->leftJoin('p.histories', 'h') // Jointure avec l'entité History
            ->andWhere('h.actionDate <= :endOfYesterday') // Filtre par date d'action de l'historique si actiondate=23/06  <= 24/06 = yesterday alors aujourduit 25/06
            ->setParameter('endOfYesterday', $yesterday)



            ->orderBy('p.id', 'DESC');
        if ((!empty($search->q))) {
            $query = $query
                ->andWhere('p.name LIKE :q')

                ->orderBy('p.id', 'desc')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->m)) {
            $query = $query
                ->andWhere('p.lastname LIKE :m')
                ->setParameter('m', "%{$search->m}%");
        }

        if (!empty($search->g)) {
            $query = $query
                ->andWhere('p.email LIKE :g')
                ->setParameter('g', "%{$search->g}%");
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

        // Vos autres conditions de recherche restent inchangées.

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
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
