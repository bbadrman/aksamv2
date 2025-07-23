<?php

namespace App\Repository;


use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Prospect;
use App\Entity\Team;
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

    //-------------Les neuveaux prospects-------//
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
            ->addSelect('t', 'f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

            ->andwhere('p.comrcl IS NULL')
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

    /**
     * return prospect affect aux equipes du chef ou bien au chef meme ttu peut voire aussi panier du admin
     * @return Prospect[] Returns an array of Prospect objects
     * 
     * @param SearchProspect $search
     * @return PaginationInterface 
     */
    public function findByChefAllNewProsp(SearchProspect $search, User $user): PaginationInterface
    {

        $team = $user->getTeams();
        if ($team->isEmpty()) {
            return $this->paginator->paginate([], 1, 10);
        }
        // get selement les prospects qui n'as pas encors affectter a un user
        $query = $this->createQueryBuilder('p')
            ->addSelect('t', 'f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

            ->andWhere('p.team IS NULL')
            ->andWhere('p.comrcl IS NULL')
            ->andWhere('p.relance IS NULL')

            // ->orwhere('p.team IN (:teams) ')
            // ->setParameter('teams', $team)


            ->orderBy('p.id', 'DESC');
        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }


    /**
     * return prospect affect aux equipes du chef ou bien au chef meme
     * @return Prospect[] Returns an array of Prospect objects
     * 
     * @param SearchProspect $search
     * @return PaginationInterface 
     */
    public function findByChefNewProsp(SearchProspect $search, User $user): PaginationInterface
    {


        $team = $user->getTeams();
        if ($team->isEmpty()) {
            return $this->paginator->paginate([], 1, 10);
        }
        // get selement les prospects qui n'as pas encors affectter a un user
        $query = $this->createQueryBuilder('p')
            ->addSelect('t, f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

            ->where('p.team IN (:teams)')
            ->setParameter('teams', $team)

            ->andWhere('p.comrcl IS NULL')
            ->andWhere('p.relance IS NULL')


            ->orderBy('p.id', 'DESC');
        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * return prospect affect aux equipes du chef ou bien au chef meme teet peut voire aussi panier du admin
     * @return Prospect[] Returns an array of Prospect objects
     * 
     * @param SearchProspect $search
     * @return PaginationInterface 
     */
    public function findByCmrclNewProsp(SearchProspect $search, $id): PaginationInterface
    {
        $startOfToday = new \DateTime('today');

        // La fin de la journée d'hier 
        // get selement les prospects qui n'as pas encors affectter a un user
        $query = $this->createQueryBuilder('p')

            ->andwhere('p.comrcl = :val')
            ->setParameter('val', $id)

            ->andWhere('p.relance IS NULL')

            ->andWhere('p.AffectAt >= :endOfYesterday') // les prospects affect h'Aujourduit 
            ->setParameter('endOfYesterday', $startOfToday)

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
            ->addSelect('t, f')

            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

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

    //-------------Les Relances du Jour-------//
    /**
     * Find list a prospect Relanced jour
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findRelancedJourChef(SearchProspect $search, User $user): PaginationInterface
    {
        $teams = $user->getTeams();
        if ($teams->isEmpty()) {
            return $this->paginator->paginate([], 1, 10);
        }

        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $endOfDay = clone $today;
        $endOfDay->setTime(23, 59, 59);

        $query = $this->createQueryBuilder('p')
            ->addSelect('t, f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

            ->where('p.team IN (:teams)')
            ->setParameter('teams', $teams)

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

    //-------------Les Relances du Jour-------//
    /**
     * Find list a prospect Relanced jour
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findRelancedJourCmrcl(SearchProspect $search, $id): PaginationInterface
    {

        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $endOfDay = clone $today;
        $endOfDay->setTime(23, 59, 59);

        $query = $this->createQueryBuilder('p')


            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

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

    //--------------- prospect avenir ---------------------------//
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

            ->addSelect('t, f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

            ->Where('p.relanceAt >= :tomorrow')
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
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findAvenirChef(SearchProspect $search, User $user): PaginationInterface
    {
        $teams = $user->getTeams();
        if ($teams->isEmpty()) {
            return $this->paginator->paginate([], 1, 10);
        }

        $tomorrow = new \DateTime('tomorrow');

        $query = $this->createQueryBuilder('p')

            ->addSelect('t, f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->andWhere('p.relanceAt >= :tomorrow')
            ->andwhere('p.team IN (:teams)')
            ->setParameter('teams', $teams)

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
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findAvenirCmrcl(SearchProspect $search, $id): PaginationInterface
    {


        $tomorrow = new \DateTime('tomorrow');

        $query = $this->createQueryBuilder('p')

            ->andWhere('p.relanceAt >= :tomorrow')
            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('p.relanceAt', 'ASC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    //-----------------------Les prospects no traite--------------------------------//
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
            ->addSelect('f, t ')
            ->andWhere('p.team IS NOT NULL')  // deja Affecté à une équipe  
            ->andWhere('p.relance IS NULL')  // n'est pas encor relancer 
            // il faut changer creatAt par affectAt 
            ->leftJoin('p.comrcl', 'f')
            ->leftJoin('p.team', 't')
            ->andWhere('p.AffectAt <= :yesterday')
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
            return $this->paginator->paginate([], 1, 10);
        }

        $query = $this->createQueryBuilder('p')
            ->addSelect('t, f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->where('p.team IN (:teams)')
            ->setParameter('teams', $teams)

            ->andWhere('p.team IS NOT NULL')  // chef d'equipe affecté 
            ->andWhere('p.relance IS NULL')  // n'est pas encor relancer 

            ->andWhere('p.AffectAt <= :endOfYesterday') // Filtre par date d'action de l'historique si actiondate=23/06  <= 24/06 = yesterday alors aujourduit 25/06
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
     * Find list a prospect no traite (qui sont pas de motirelance et dejat affecter au team et cmrcl)
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findProspectNonTraiterCmrcl(SearchProspect $search, $id): PaginationInterface
    {
        $now = new \DateTime();
        $yesterday = clone $now;

        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59);



        $query = $this->createQueryBuilder('p')

            ->where('p.comrcl = :val')
            ->setParameter('val', $id)

            ->andWhere('p.relance IS NULL')  // n'est pas encor relancer 

            ->andWhere('p.AffectAt <= :endOfYesterday') // Filtre par date d'action de l'historique si actiondate=23/06  <= 24/06 = yesterday alors aujourduit 25/06
            ->setParameter('endOfYesterday', $yesterday)


            ->orderBy('p.id', 'DESC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    //-----------------------Search All prospects--------------------------------//

    /**
     * Find list a prospect by a all search form
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findSearch(SearchProspect $search): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('p')
            ->addSelect('t, f ')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->indexBy('p', 'p.id', 'DESC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * afecher seulement les prospects qui partient de panier du chef findByUserChefEquipe
     * deplicated voire emplacement
     * @return Prospect[] Returns an array of Prospect objects 
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findAllChefSearch(SearchProspect $search, User $user): PaginationInterface
    {
        // get selement les prospects qui n'as pas encors affectter a un user
        $teams = $user->getTeams();
        if ($teams->isEmpty()) {
            return $this->paginator->paginate([], 1, 10);
        }

        $query = $this
            ->createQueryBuilder('p')
            ->addSelect('t, f')
            ->where('p.team IN (:teams)')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

            ->setParameter('teams', $teams)
            ->orderBy('p.id', 'DESC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    /**
     * afecher seulement les prospects qui partient de panier du chef findByUserChefEquipe
     * deplicated voire emplacement
     * @return Prospect[] Returns an array of Prospect objects 
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function findByUserAffecterCmrcl(SearchProspect $search, $id): PaginationInterface
    {
        // get selement les prospects qui n'as pas encors affectter a un user


        $query = $this
            ->createQueryBuilder('p')

            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

            ->orderBy('p.id', 'DESC');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    ///--------------Relanced no traités-------------------///
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

            ->addSelect('t, f ')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')

            // ->andWhere('p.comrcl is NOT NULL')
            // ->andWhere('p.team is NOT NULL')
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
     * Find list a prospect Relanced no traités pour admin--modifie-- 
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function RelancesNonTraiteesChef(SearchProspect $search, User $user): PaginationInterface
    {
        $teams = $user->getTeams();
        if ($teams->isEmpty()) {
            return $this->paginator->paginate([], 1, 10);
        }

        $yesterday = (new \DateTime('yesterday'))->setTime(23, 59, 59);
        $query = $this->createQueryBuilder('p')

            ->addSelect('t, f')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->andwhere('p.team IN (:teams)')
            ->setParameter('teams', $teams)

            // ->andWhere('p.comrcl is NOT NULL')
            // ->andWhere('p.team is NOT NULL')


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
     * Find list a prospect Relanced no traités pour admin--modifie-- 
     * @param SearchProspect $search
     * @return PaginationInterface
     */
    public function RelancesNonTraiteesCmrcl(SearchProspect $search, $id): PaginationInterface
    {


        $yesterday = (new \DateTime('yesterday'))->setTime(23, 59, 59);
        $query = $this->createQueryBuilder('p')

            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

            // ->andWhere('p.comrcl is NOT NULL')
            // ->andWhere('p.team is NOT NULL') 

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
            ->addSelect('t, f')
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
     * Find list a prospect Unjoinable
     * @return Prospect[] 
     * @param SearchProspect $search
     * @return PaginationInterface 
     */
    public function findUnjoingChef(SearchProspect $search, User $user): PaginationInterface
    {
        $teams = $user->getTeams();
        if ($teams->isEmpty()) {
            return $this->paginator->paginate([], 1, 10);
        }
        $query = $this->createQueryBuilder('p')
            ->addSelect('t, f')
            ->leftJoin('p.team', 't')
            ->where('p.relance = 11')

            ->leftJoin('p.comrcl', 'f')
            ->andwhere('p.team IN (:teams)')
            ->setParameter('teams', $teams)
            ->orderBy('p.relanceAt', 'desc');

        $query = $this->applySearchFilters($query, $search);

        return $this->paginator->paginate(
            $query,
            $search->page,
            10
        );
    }
    /**
     * Find list a prospect Unjoinable
     * @return Prospect[] 
     * @param SearchProspect $search
     * @return PaginationInterface 
     */
    public function findUnjoingCmrcl(SearchProspect $search, $id): PaginationInterface
    {

        $query = $this->createQueryBuilder('p')

            ->where('p.relance = 11')

            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)
            ->orderBy('p.relanceAt', 'desc');

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

        if (!empty($search->u)) {
            $query = $query
                ->andWhere('p.url LIKE :u')
                ->setParameter('u', "%{$search->u}%");
        }
        if (!empty($search->relance)) {
            $query = $query

                ->andWhere('p.relance = :relance')
                ->setParameter('relance', $search->relance);
        }

        if (!empty($search->source)) {
            $query = $query
                ->andWhere('p.source = :source')
                ->setParameter('source', $search->source);
        }

        return $query;
    }

    public function findByDateInterval(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $endDateAdjusted = \DateTime::createFromInterface($endDate);
        $endDateAdjusted->setTime(23, 59, 59);
        return $this->createQueryBuilder('p')


            ->where('p.creatAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDateAdjusted)
            ->getQuery()
            ->getResult();
    }

    public function findByDateIntervalClaud(\DateTimeInterface $startDate, \DateTimeInterface $endDate)
    {
        // Ajuster la date de fin pour inclure toute la journée
        $endDateAdjusted = \DateTime::createFromInterface($endDate);
        $endDateAdjusted->setTime(23, 59, 59);

        return $this->createQueryBuilder('p')
            ->andWhere('p.creatAt >= :startDate')
            ->andWhere('p.creatAt <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDateAdjusted)
            ->orderBy('p.creatAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    /**
     * getnumber prospects for notifiy
     * return with int pour admin
     */

    public function findAllNewProspectsApi(): int
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(DISTINCT p.id)')
            ->andWhere("p.comrcl is NULL")
            ->andWhere("p.team is NULL")
            ->andWhere('p.relance IS NULL');

        return (int) $query->getQuery()->getSingleScalarResult();
    }
    //return with int pour chef
    public function findAllNewProspectsChefAllApi(User $user): int
    {
        $team = $user->getTeams();
        if ($team->isEmpty()) {
            return 0;
        }
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(DISTINCT p.id)')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->orwhere('p.team IN (:teams) ')
            ->setParameter('teams', $team)
            ->andWhere('p.comrcl IS NULL')
            ->andWhere('p.relance IS NULL');
        //->andWhere('p.comrcl IS NULL OR p.comrcl = :user') // Filtrer les prospects no affectés et affect au chef aussi
        //->setParameter('user', $user);

        return (int) $query->getQuery()->getSingleScalarResult();
    }
    //return with int pour chef
    public function findAllNewProspectsChefApi(User $user): int
    {
        $team = $user->getTeams();
        if ($team->isEmpty()) {
            return 0;
        }
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(DISTINCT p.id)')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->where('p.team IN (:teams)')
            ->setParameter('teams', $team)
            ->andWhere('p.comrcl IS NULL')
            ->andWhere('p.relance IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    //return with int pour comercl
    public function findAllNewProspectsComercialApi($id): int
    {

        $yesterday = new \DateTime('yesterday');
        $yesterday->setTime(23, 59, 59);
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(DISTINCT p.id)')
            ->where('p.comrcl = :val')
            ->setParameter('val', $id)

            ->andWhere('p.relance IS NULL')

            ->andWhere('p.AffectAt >= :endOfYesterday')
            ->setParameter('endOfYesterday', $yesterday);




        return (int) $query->getQuery()->getSingleScalarResult();
    }
    // public function findByFilters(
    //     \DateTimeInterface $startDate,
    //     \DateTimeInterface $endDate,
    //     ?int $teamId,
    //     ?int $comrclId,
    //     ?int $productId,
    //     ?string $url,
    //     ?string $activites,
    //     ?string $typeProspect
    // ) {
    //     $endDateAdjusted = \DateTime::createFromInterface($endDate);
    //     $endDateAdjusted->setTime(23, 59, 59);

    //     $qb = $this->createQueryBuilder('p')
    //         ->andWhere('p.creatAt >= :startDate')
    //         ->andWhere('p.creatAt <= :endDate')
    //         ->setParameter('startDate', $startDate)
    //         ->setParameter('endDate', $endDateAdjusted);

    //     if ($teamId) {
    //         $qb->andWhere('p.team = :teamId')->setParameter('teamId', $teamId);
    //     }

    //     if ($comrclId) {
    //         $qb->andWhere('p.comrcl = :comrclId')->setParameter('comrclId', $comrclId);
    //     }

    //     if ($productId) {
    //         $qb->andWhere('p.product = :productId')->setParameter('productId', $productId);
    //     }

    //     if ($url) {
    //         $qb->andWhere('p.url LIKE :url')->setParameter('url', '%' . $url . '%');
    //     }

    //     if ($activites) {
    //         $qb->andWhere('p.activites LIKE :activites')->setParameter('activites', '%' . $activites . '%');
    //     }

    //     if ($typeProspect) {
    //         $qb->andWhere('p.typeProspect LIKE :typeProspect')->setParameter('typeProspect', '%' . $typeProspect . '%');
    //     }

    //     return $qb->orderBy('p.creatAt', 'DESC')->getQuery()->getResult();
    // }

    public function findByFiltersdeepp(
        ?\DateTimeInterface $startDate,
        ?\DateTimeInterface $endDate,
        $teamId,
        $comrclId,
        $productId,
        $url,
        $activites,
        $typeProspect
    ) {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.creatAt', 'DESC');

        // Filtre par dates
        if ($startDate) {
            $qb->andWhere('p.creatAt >= :startDate')
                ->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $endDateAdjusted = \DateTime::createFromInterface($endDate);
            $endDateAdjusted->setTime(23, 59, 59);
            $qb->andWhere('p.creatAt <= :endDate')
                ->setParameter('endDate', $endDateAdjusted);
        }

        // Filtres relationnels
        if ($teamId) {
            $qb->andWhere('p.team = :teamId')
                ->setParameter('teamId', $teamId);
        }
        if ($comrclId) {
            $qb->andWhere('p.comrcl = :comrclId')
                ->setParameter('comrclId', $comrclId);
        }
        if ($productId) {
            $qb->andWhere('p.product = :productId')
                ->setParameter('productId', $productId);
        }

        // Filtres textuels (LIKE pour recherche partielle)
        if ($url) {
            $qb->andWhere('p.url LIKE :url')
                ->setParameter('url', '%' . $url . '%');
        }
        if ($activites) {
            $qb->andWhere('p.activites LIKE :activites')
                ->setParameter('activites', '%' . $activites . '%');
        }
        if ($typeProspect) {
            $qb->andWhere('p.typeProspect = :typeProspect')
                ->setParameter('typeProspect', $typeProspect);
        }

        return $qb->getQuery()->getResult();
    }

    public function createFilteredQuerydeep(
        ?\DateTimeInterface $startDate,
        ?\DateTimeInterface $endDate,
        $teamId,
        $comrclId,
        $productId,
        $url,
        $activites,
        $typeProspect,
        $source,
        $relance
    ) {
        $qb = $this->createQueryBuilder('p')

            // ->andWhere('p.relance NOT IN (:motifs)')
            // ->setParameter('motifs', [13])
            ->orderBy('p.creatAt', 'DESC');

        // Filtre par dates
        if ($startDate) {
            $qb->andWhere('p.creatAt >= :startDate')
                ->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $endDateAdjusted = \DateTime::createFromInterface($endDate);
            $endDateAdjusted->setTime(23, 59, 59);
            $qb->andWhere('p.creatAt <= :endDate')
                ->setParameter('endDate', $endDateAdjusted);
        }

        // Filtres relationnels
        if ($teamId) {
            $qb->andWhere('p.team = :teamId')
                ->setParameter('teamId', $teamId);
        }
        if ($comrclId) {
            $qb->andWhere('p.comrcl = :comrclId')
                ->setParameter('comrclId', $comrclId);
        }
        if ($productId) {
            $qb->andWhere('p.product = :productId')
                ->setParameter('productId', $productId);
        }

        // Filtres textuels
        if ($url) {
            $qb->andWhere('p.url = :url')
                ->setParameter('url', $url);
        }
        if ($activites) {
            $qb->andWhere('p.activites = :activites')
                ->setParameter('activites', $activites);
        }
        if ($typeProspect) {
            $qb->andWhere('p.typeProspect = :typeProspect')
                ->setParameter('typeProspect', $typeProspect);
        }
        if (!empty($relance)) {
            $qb->andWhere('p.relance NOT IN (:relance)')
                ->setParameter('relance', $relance);
        }

        // Filtre source (obligatoire)
        $qb->andWhere('p.source = :source')
            ->setParameter('source', $source);

        return $qb->getQuery();
    }


    /**
     * Récupération des résultats avec filtres (méthode originale)
     */
    public function findByFiltersdeep(
        ?\DateTimeInterface $startDate,
        ?\DateTimeInterface $endDate,
        $teamId,
        $comrclId,
        $productId,
        $url,
        $activites,
        $typeProspect,
        $source,
        $relance
    ) {
        return $this->createFilteredQuerydeep(
            $startDate,
            $endDate,
            $teamId,
            $comrclId,
            $productId,
            $url,
            $activites,
            $typeProspect,
            $source,
            $relance
        )->getResult();
    }


    /**
     * Compte le nombre total de prospects pour la recherche générale (ADMIN/DEV)
     */
    public function countSearch(SearchProspect $search): int
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)');

        // Ajouter les jointures seulement si nécessaire pour les filtres
        $needsTeamJoin = !empty($search->team);
        $needsUserJoin = !empty($search->r);

        if ($needsTeamJoin) {
            $query->leftJoin('p.team', 't');
        }
        if ($needsUserJoin) {
            $query->leftJoin('p.comrcl', 'f');
        }

        $query = $this->applySearchFilters($query, $search);

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Compte le nombre total de prospects pour un chef d'équipe
     */
    public function countAllChefSearch(SearchProspect $search, User $user): int
    {
        $teams = $user->getTeams();
        if ($teams->isEmpty()) {
            return 0;
        }

        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.team IN (:teams)')
            ->setParameter('teams', $teams);

        // Jointures conditionnelles
        $needsTeamJoin = !empty($search->team);
        $needsUserJoin = !empty($search->r);

        if ($needsTeamJoin) {
            $query->leftJoin('p.team', 't');
        }
        if ($needsUserJoin) {
            $query->leftJoin('p.comrcl', 'f');
        }

        $query = $this->applySearchFilters($query, $search);

        return (int) $query->getQuery()->getSingleScalarResult();
    }


    /**
     * Compte le nombre total de prospects pour un commercial
     */
    // public function countByUserAffecterCmrcl(SearchProspect $search, $user): int
    // {
    //     $query = $this->createQueryBuilder('p')
    //         ->select('COUNT(p.id)')
    //         ->andWhere('p.comrcl = :val')
    //         ->setParameter('val', $user);

    //     // Jointures conditionnelles (probablement pas nécessaires pour un commercial)
    //     $needsTeamJoin = !empty($search->team);
    //     $needsUserJoin = !empty($search->r);

    //     if ($needsTeamJoin) {
    //         $query->leftJoin('p.team', 't');
    //     }
    //     if ($needsUserJoin) {
    //         $query->leftJoin('p.comrcl', 'f');
    //     }

    //     $query = $this->applySearchFilters($query, $search);

    //     return (int) $query->getQuery()->getSingleScalarResult();
    // }
}
