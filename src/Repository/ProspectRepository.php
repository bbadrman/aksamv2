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
     *  afficher les prospects qui n ont pas du team et cmrcl pour admin
     * @return Prospect[] Returns an array of Prospect objects
     * 
     * @param SearchProspect $search
     * @return PaginationInterface 
     */
    public function findByAdminNewProsp(SearchProspect $search): PaginationInterface
    {

        // get selement les prospects qui n'as pas encors affectter a un user
        $query = $this->createQueryBuilder('p')
            ->select('p', 't', 'f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

            ->where('p.comrcl IS NULL')
            ->andWhere('p.team IS NULL')
            ->andWhere('p.relance IS NULL')

            // ->leftJoin('p.relanceds', 'r')
            // ->andWhere('r.motifRelanced is null')

            ->orderBy('p.id', 'DESC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }
    //-------------Les Relances du Jour-------//
    /**
     * Find list a prospect Relanced jour
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findRelancedJour(SearchProspect $search): PaginationInterface
    {

        $today = new \DateTime();

        $today->setTime(0, 0, 0);
        $endOfDay = clone $today;
        $endOfDay->setTime(23, 59, 59);

        $query = $this->createQueryBuilder('p')
            ->select('p, t, f')

            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            // ->leftJoin('p.clotures', 'c')
            // ->Where('c.motifCloture is NULL')

            ->andWhere('p.relance NOT IN (:motifs)')
            ->andwhere('p.relanceAt BETWEEN :startOfDay AND :endOfDay')
            ->setParameter('startOfDay', $today)
            ->setParameter('endOfDay', $endOfDay)

            ->setParameter('motifs', [6, 7, 8, 9, 10, 11, 12, 13])
            ->orderBy('p.relanceAt', 'ASC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * affichier les prospect avenir pour admin
     * je doit modifie cet fn afin de disparer prospc quand an faire une action
     * dans ce cas il dispare demain
     * Find list a prospect Relanced
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findAvenir(SearchProspect $search): PaginationInterface
    {


        $tomorrow = new \DateTime('tomorrow');
        // dd($tomorrow);  //2025-02-26 00:00:00.0 UTC (+00:00)
        // $tomorrow->setTime(0, 0, 0);
        // dd($tomorrow);   //2025-02-26 00:00:00.0 UTC (+00:00)

        $query = $this->createQueryBuilder('p')

            ->select('p, t, f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->Where('p.relanceAt > :tomorrow')
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('p.relanceAt', 'ASC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
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

            ->andWhere('p.AffectAt <= :yesterday')
            ->setParameter('yesterday', $yesterday);

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * Find list a prospect by a all search form
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findSearch(SearchProspect $search): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('p')
            ->select('p, t, f ')

            ->leftJoin('p.team', 't')

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
     * Find list a prospect Relanced no traités pour admin--modifie-- 
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findRelancesNonTraitees(SearchProspect $search): PaginationInterface
    {

        $yesterday = (new \DateTime('yesterday'))->setTime(23, 59, 59);
        // dd($yesterday); //2025-03-13 23:59:59
        // $dayBeforeYesterday = (clone $yesterday)->modify('-9 year');
        // dd($dayBeforeYesterday); // 2016-03-13 23:59:59.

        $query = $this->createQueryBuilder('p')

            ->select('p, t, f ')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

            // les dates de relance plus que 1 un apartir d hier
            // ->andWhere('p.relanceAt BETWEEN :dayBeforeYesterday AND :yesterday')
            // ->setParameter('dayBeforeYesterday', $dayBeforeYesterday)
            // ->setParameter('yesterday', $yesterday)

            ->andWhere('p.comrcl is NOT NULL')
            ->andWhere('p.team is NOT NULL')
            //les dates de relance ne doit pas plus que date d'hier (les prospers qui ont la date de relaced haujourdhui et plus, n'afficher pas )
            ->andWhere('p.relanceAt <= :endOfYesterday')
            ->setParameter('endOfYesterday', $yesterday)


            ->orderBy('p.relanceAt', 'desc');


        $query = $this->applySearchFilters($query, $search);

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

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    //-----------Les Injoignables--------------//

    /**
     * Find list a prospect Unjoinable
     * @return Prospect[] 
     * @param SearchProspect $search
     * @return PaginationInterface 
     */
    public function findUnjoing(SearchProspect $search): PaginationInterface
    {

        $query = $this->createQueryBuilder('p')
            ->select('p, t, f')
            ->where('p.relance = 11')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->orderBy('p.relanceAt', 'desc');

        $query = $this->applySearchFilters($query, $search);

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
     * @param SearchProspect $search
     * @return QueryBuilder
     */
    private function applySearchFilters(QueryBuilder $query, SearchProspect $search): QueryBuilder
    {
        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->m)) {
            $query = $query
                ->andWhere('p.prenom LIKE :m')
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
        if (!empty($search->dr) && $search->dr instanceof \DateTime) {
            $query = $query
                ->andWhere('p.relanceAt >= :dr')
                ->setParameter('dr', $search->dr->format('Y-m-d'));
        }

        if (!empty($search->ddr) && $search->ddr instanceof \DateTime) {
            $search->ddr->setTime(23, 59, 59); // Fix time to end of the day
            $query = $query
                ->andWhere('p.relanceAt <= :ddr')
                ->setParameter('ddr', $search->ddr->format('Y-m-d H:i:s'));
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
}
