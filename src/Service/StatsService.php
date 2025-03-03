<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Contrat;
use App\Entity\Prospect;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;


class StatsService
{


    public function __construct(private EntityManagerInterface $manager, private  Security $security) {}
    public function getStats()
    {

        $user = $this->security->getUser();


        $users    = $this->getUsersCount();
        $teams      = $this->getTeamsCount();
        $products = $this->getProductsCount();
        $clients = $this->getClientsCount();
        $contrats = $this->getContratsCount();
        // $prospectsAffect = $this->getProspectCount();

        $prospectspasaffect = $this->getProspectPasCount();
        $prospectsChefNv = $this->getProspectChefNv($user);
        $prospectsChefNvAll = $this->getProspectChefNvAll($user);
        $prospectsCmrclNv = $this->getProspectCmrclNv($user);
        $prospectsDay = $this->getProspectCountRelance();
        $prospectsDayChef = $this->getProspectCountRelanceChef($user);
        $prospectsDayCmrcl = $this->getProspectCountRelanceCmrcl($user);

        //table a venir
        $prospectsAvenir = $this->getProspectRelanceAvenir();
        $prosAvenirChef = $this->getProspectRelanceAvenirChef($user);
        $prosAvenirCmrcl = $this->getProspectRelanceAvenirCmrcl($user);


        // table prospect no traite
        $prospectsNoTraite = $this->getProspectNonTraite();
        $prospectsNoTrChef = $this->getProspectNonTraiteChef($user);
        $prospectsNoTrCmrcl = $this->getProspectNonTraiteCmrcl($user);

        // table relance no traite
        $relanceNoTraite = $this->getRelanceNonTraite();
        $relancesNoTrChef = $this->getRelanceNonTraiteChef($user);
        $relancesNoTrCmrcl = $this->getRelanceNonTraiteCmrcl($user);

        // caclcule le total du prospect en panier
        // $prospectsPanier = $this->getProspectCountPanier();
        // $prospectsPanierJour = $this->getProspectCountPanierJour();

        // $prospectsnow = $this->getProspectCountNow();
        $prospects = $this->getProspectTotlCount();
        $unjoiniable = $this->getProspectCountUnjoiniable();
        $unjoiniableChef = $this->getProspectCountUnjoiniableChef($user);
        $unjoiniableCmrl = $this->getProspectCountUnjoiniableCmrcl($user);

        // $prospectsnow = $this->getProspectCountNow();

        // compter client
        $preclientAdmin = $this->getpreclientAdmin();
        $preclientChef = $this->getpreclientChef($user);
        $preclientComrcl = $this->getpreclientCmrcl($user);
        $preclientValide = $this->getpreclientValide($user);
        // compter contrat
        $preContratAdmin = $this->getpreContratAdmin();
        $preContratComrcl = $this->getpreContratComrcl($user);



        return compact('contrats', 'preclientValide', 'preContratAdmin', 'preContratComrcl', 'preclientAdmin', 'preclientChef', 'preclientComrcl',  'relancesNoTrCmrcl', 'relancesNoTrChef', 'relanceNoTraite', 'prosAvenirCmrcl', 'prosAvenirChef', 'prospectsAvenir', 'unjoiniableCmrl', 'unjoiniableChef', 'prospectsNoTrCmrcl', 'prospectsNoTrChef', 'prospectsDayCmrcl', 'prospectsDayChef', 'prospectsCmrclNv', 'prospectsChefNv', 'prospectsChefNvAll', 'prospectsNoTraite', 'unjoiniable', 'prospects', 'prospectspasaffect', 'prospectsDay', 'users', 'teams', 'products', 'clients');
    }



    // stat du chartjs
    public function getUsersCount()
    {
        return  $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }
    public function getTeamsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Team a')->getSingleScalarResult();
    }
    public function getProductsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Product b')->getSingleScalarResult();
    }
    public function getClientsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Client c')->getSingleScalarResult();
    }
    public function getProspectTotlCount()
    {
        return $this->manager->createQuery('SELECT COUNT(p) FROM App\Entity\Prospect p')->getSingleScalarResult();
    }

    public function getContratsCount()
    {
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(c)')
            ->from('App\Entity\Contrat', 'c'); // Spécifiez la source des données

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }



    // les prospect cree ce jour et pas affc
    public function getProspectPasCount()
    {


        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(p)')
            ->from('App\Entity\Prospect', 'p')

            ->Where("p.comrcl is NULL")
            ->andWhere("p.team is NULL")

            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.motifRelanced is null');


        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }


    // les nouveaux prospects cree ce jour affecter a mon equipe
    public function getProspectChefNv(User $user): int
    {

        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        // $today = new \DateTime();
        // $today->setTime(0, 0, 0);

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from('App\Entity\Prospect', 'p')
            ->where('p.team IN (:teams)')
            ->andWhere('p.team IS NOT NULL')
            ->andWhere('p.comrcl IS NULL')

            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.prospect IS NULL')

            // ->leftJoin('p.clotures', 'c')
            // ->andWhere('c.motifCloture is NULL')
            //->andWhere('p.comrcl IS NULL OR p.comrcl = :val')
            ->setParameter('teams', $teams);
        //->setParameter('val', $user);
        // ->andWhere('p.creatAt >= :startOfDay')

        // ->setParameter('startOfDay', $today);








        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return (int) $result;
    }

    // les nouveaux prospects cree ce jour affecter a mon equipe
    public function getProspectChefNvAll(User $user): int
    {

        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        // $today = new \DateTime();
        // $today->setTime(0, 0, 0);

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from('App\Entity\Prospect', 'p')
            ->Where('p.team IS NULL')
            ->orwhere('p.team IN (:teams)')
            ->andWhere('p.comrcl IS NULL')
            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.prospect IS NULL')
            ->setParameter('teams', $teams);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return (int) $result;
    }
    // afficher les neveaux prospects au cmerciel
    public function getProspectCmrclNv($id)
    {
        $yesterday = new \DateTime('yesterday');
        $yesterday->setTime(23, 59, 59); // La fin de la journée d'hier

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from('App\Entity\Prospect', 'p')
            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)
            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.prospect IS NULL')
            ->leftJoin('p.histories', 'h')
            // ->leftJoin('p.clotures', 'c')
            // ->andWhere('c.motifCloture is NULL')
            ->andWhere('h.actionDate >= :endOfYesterday')
            ->setParameter('endOfYesterday', $yesterday);

        // Ajoutez une condition spécifique à la jointure avec l'entité History pour filtrer les données
        // $qb->leftJoin('p.histories', 'h')


        // Supprimez la condition inutile sur la même table
        // $qb->andWhere('p.creatAt >= :startOfDay')
        //    ->setParameter('startOfDay', $today);

        // Supprimez la condition inutile sur la sous-requête
        $qb->andWhere('p.id NOT IN ( 
           SELECT pr.id FROM App\Entity\Prospect pr
           JOIN pr.relanceds rel
           WHERE rel.relacedAt > :endOfYesterday
        )')->setParameter('endOfYesterday', $yesterday);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }



    // caclcule le total du prospect relancer à venir pour admin
    public function getProspectRelanceAvenir()
    {
        $today = new \DateTime('tomorrow');
        $today->setTime(0, 0, 0);


        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')

            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.relacedAt >= :tomorrow')

            // ->leftJoin('p.clotures', 'c')
            // ->andWhere('c.motifCloture is NULL')
            ->setParameter('tomorrow', $today)
            ->andWhere('r.motifRelanced NOT IN (:motifs)')
            ->setParameter('motifs', [2, 3, 7, 8, 9, 10, 11]);
        //pour count seuelement qui ont motifrlc 1 pas avec les coumun
        // ->andWhere('NOT EXISTS (
        //     SELECT 1 FROM App\Entity\Relanced otherR
        //     WHERE otherR.prospect = p AND otherR.motifRelanced = 2
        // )');
        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect relancer à venir
    public function getProspectRelanceAvenirChef(User $user): int
    {
        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        $today = new \DateTime('tomorrow');
        $today->setTime(0, 0, 0);

        $endOfDay = clone $today;
        $endOfDay->setTime(23, 59, 59);
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->where('p.team IN (:teams)')
            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.relacedAt >= :tomorrow')

            // ->leftJoin('p.clotures', 'c')
            // ->andWhere('c.motifCloture is NULL')
            // ->andWhere('NOT EXISTS (
            //     SELECT 1 FROM App\Entity\Relanced otherR
            //     WHERE otherR.prospect = p AND otherR.motifRelanced = 2
            // )')
            ->setParameter('teams', $teams)
            ->setParameter('tomorrow', $today)
            ->andWhere('r.motifRelanced NOT IN (:motifs)')
            ->setParameter('motifs', [2, 3, 7, 8, 9, 10, 11]);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect relancer à venir du cmrcl
    public function getProspectRelanceAvenirCmrcl($id)
    {

        $today = new \DateTime('tomorrow');
        $today->setTime(0, 0, 0);

        $endOfDay = clone $today;
        $endOfDay->setTime(23, 59, 59);
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.relacedAt >= :tomorrow')
            ->setParameter('tomorrow', $today)

            // ->leftJoin('p.clotures', 'c')
            // ->andWhere('c.motifCloture is NULL')

            ->andWhere('r.motifRelanced NOT IN (:motifs)')
            ->setParameter('motifs', [2, 3, 7, 8, 9, 10, 11]);
        // ->andWhere('NOT EXISTS (
        //     SELECT 1 FROM App\Entity\Relanced otherR
        //     WHERE otherR.prospect = p AND otherR.motifRelanced = 2
        // )');



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return (int) $result;
    }

    // caclcule le total du prospect relancer ce jour 
    public function getProspectCountRelance()
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $endOfDay = clone $today;
        $endOfDay->setTime(23, 59, 59);
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')

            ->leftJoin('p.relanceds', 'r')
            // ->leftJoin('p.clotures', 'c')
            // ->Where('c.motifCloture is NULL')
            ->andWhere('r.relacedAt BETWEEN :startOfDay AND :endOfDay')
            ->setParameter('startOfDay', $today)
            ->setParameter('endOfDay', $endOfDay)
            ->andWhere('r.motifRelanced NOT IN (:motifs)')
            ->setParameter('motifs', [2, 3, 7, 8, 9, 10, 11]);
        // ->andWhere('r.motifRelanced = 1');


        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    //calculer nombre de relance pour chef

    public function getProspectCountRelanceChef(User $user): int
    {
        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }

        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $endOfDay = clone $today;
        $endOfDay->setTime(23, 59, 59);
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->where('p.team IN (:teams)')
            ->leftJoin('p.relanceds', 'r')
            // ->leftJoin('p.clotures', 'c')
            // ->andWhere('c.motifCloture is NULL')
            ->andWhere('r.relacedAt BETWEEN :startOfDay AND :endOfDay')
            //->andWhere('r.motifRelanced = 1')
            ->setParameter('teams', $teams)
            ->setParameter('startOfDay', $today)
            ->setParameter('endOfDay', $endOfDay)
            ->andWhere('r.motifRelanced NOT IN (:motifs)')
            ->setParameter('motifs', [2, 3, 7, 8, 9, 10, 11]);
        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    //calculer nombre de relance pour comerciel

    public function getProspectCountRelanceCmrcl($id)
    {

        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $endOfDay = clone $today;
        $endOfDay->setTime(23, 59, 59);
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->Where('p.comrcl = :val')
            ->setParameter('val', $id)

            ->leftJoin('p.relanceds', 'r')
            // ->leftJoin('p.clotures', 'c')
            // ->andWhere('c.motifCloture is NULL')
            ->andWhere('r.relacedAt BETWEEN :startOfDay AND :endOfDay')
            ->setParameter('startOfDay', $today)
            ->setParameter('endOfDay', $endOfDay)
            ->andWhere('r.motifRelanced NOT IN (:motifs)')
            ->setParameter('motifs', [2, 3, 7, 8, 9, 10, 11]);
        //->andWhere('r.motifRelanced = 1');
        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }




    // caclcule le total du relance Non traite pour admin
    public function getRelanceNonTraite()
    {
        $yesterday = new \DateTime('yesterday');
        $yesterday->setTime(23, 59, 59);
        // $dayBeforeYesterday = (clone $yesterday)->modify('-1 year')->setTime(0, 0, 0); // Le début d'avant-hier
        $dayBeforeYesterday = (clone $yesterday)->modify('-9 year');

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')  //  En utilisant COUNT(DISTINCT p.id), vous comptez les prospects uniques, en ignorant les doublons dans les relances pour chaque prospect
            ->from(Prospect::class, 'p')

            ->leftJoin('p.relanceds', 'r')

            //->Where('(r.motifRelanced = 1)') // r.motifRelanced selement = 1
            ->andWhere('r.relacedAt BETWEEN :dayBeforeYesterday AND :yesterday')
            ->setParameter('dayBeforeYesterday', $dayBeforeYesterday)
            ->setParameter('yesterday', $yesterday)


            ->andWhere('p.comrcl is NOT NULL')
            ->andWhere('p.team is NOT NULL')
            ->andWhere('r.motifRelanced NOT IN (:motifs)')
            ->setParameter('motifs', [2, 3, 7, 8, 9, 10, 11]);

        $qb->andWhere('p.id NOT IN (
            SELECT pr.id FROM App\Entity\Prospect pr
            JOIN pr.relanceds rel
            WHERE rel.relacedAt > :endOfYesterday
        )')->setParameter('endOfYesterday', $yesterday);



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du relance Non traite pour chef
    public function getRelanceNonTraiteChef(User $user): int
    {
        $yesterday = new \DateTime('yesterday');
        $yesterday->setTime(23, 59, 59);
        $dayBeforeYesterday = (clone $yesterday)->modify('-9 year')->setTime(0, 0, 0); // Le début d'avant-hier


        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->leftJoin('p.relanceds', 'r')
            ->where('p.team IN (:teams)')

            //->andWhere('(r.motifRelanced IS NULL OR r.motifRelanced = 1)')
            ->andWhere('r.relacedAt BETWEEN :dayBeforeYesterday AND :yesterday')
            //->andWhere('p.comrcl IS NOT NULL')
            ->andWhere('p.id NOT IN (
                SELECT pr.id FROM App\Entity\Prospect pr
                JOIN pr.relanceds rel
                WHERE rel.relacedAt > :endOfYesterday
            )')
            ->setParameter('teams', $teams)
            ->setParameter('dayBeforeYesterday', $dayBeforeYesterday)
            ->setParameter('yesterday', $yesterday)
            ->setParameter('endOfYesterday', $yesterday)
            ->andWhere('r.motifRelanced NOT IN (:motifs)')
            ->setParameter('motifs', [2, 3, 7, 8, 9, 10, 11]);


        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du relance Non traite pour comerciale
    public function getRelanceNonTraiteCmrcl($id)
    {

        $yesterday = new \DateTime('yesterday');
        $yesterday->setTime(23, 59, 59);
        $dayBeforeYesterday = (clone $yesterday)->modify('-9 year')->setTime(0, 0, 0); // Le début d'avant-hier

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->Where('p.comrcl = :val')
            ->setParameter('val', $id)
            ->leftJoin('p.relanceds', 'r')
            //->andWhere('(r.motifRelanced IS NULL OR r.motifRelanced = 1)')
            ->andWhere('r.motifRelanced NOT IN (:motifs)')
            ->setParameter('motifs', [2, 3, 7, 8, 9, 10, 11])
            ->andWhere('r.relacedAt >= :dayBeforeYesterday AND r.relacedAt <= :yesterday')
            ->setParameter('dayBeforeYesterday', $dayBeforeYesterday)
            ->setParameter('yesterday', $yesterday);
        $qb->andWhere('p.id NOT IN (
                SELECT pr.id FROM App\Entity\Prospect pr
                JOIN pr.relanceds rel
                WHERE rel.relacedAt > :endOfYesterday
            )')->setParameter('endOfYesterday', $yesterday);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect Non traite pour admin
    public function getProspectNonTraite()
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');
        $excludedEmail = 'service.technique@aksam-assurances.fr';
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->leftJoin('p.team', 't')
            ->leftJoin('p.comrcl', 'f')
            ->Where('p.team IS NOT NULL')
            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.prospect IS NULL')



            ->andwhere('p.email != :excludedEmail')
            ->setParameter('excludedEmail', $excludedEmail)

            ->andWhere('p.creatAt <= :yesterday')
            ->setParameter('yesterday', $yesterday);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }
    // caclcule le total du prospect Non traite pour chef
    public function getProspectNonTraiteChef(User $user): int
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');

        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->where('p.team IN (:teams)')

            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.prospect IS NULL')



            ->andWhere('p.team IS NOT NULL')  // chef d'equipe affecté 
            ->andWhere('p.comrcl IS NOT NULL')
            //->andWhere('p.comrcl IS NULL OR p.comrcl = :val') // Filtrer les prospects no affectés et affect au chef aussi
            //->setParameter('val', $user)
            ->andWhere('p.creatAt <= :yesterday')
            ->setParameter('teams', $teams)
            ->setParameter('yesterday', $yesterday)
            ->leftJoin('p.comrcl', 'f')   // Aucune relation avec relanced
        ;
        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect Non traite pour comerciale
    public function getProspectNonTraiteCmrcl($id)
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59); // pour fixer hier a  minuit

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

            ->leftJoin('p.relanceds', 'r')
            ->andWhere('r.prospect IS NULL') // Aucune relation avec relanced




            // ->andWhere('p.creatAt <= :yesterday')
            // ->setParameter('yesterday', $yesterday)
            // pas encour passe un jeur de la date de history actionDate
            ->leftJoin('p.histories', 'h') // Jointure avec l'entité History
            ->andWhere('h.actionDate <= :endOfYesterday') // Filtre par date d'action de l'historique
            ->setParameter('endOfYesterday', $yesterday);
        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }




    // caclcule le total du prospect  Unjoiniable 
    public function getProspectCountUnjoiniable()
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')



            ->leftJoin('p.relanceds', 'r')
            ->andWhere("r.motifRelanced = '2'");

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }
    // caclcule le total du prospect  Unjoiniable pour chef
    public function getProspectCountUnjoiniableChef(User $user): int
    {
        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->where('p.team IN (:teams)')
            ->leftJoin('p.relanceds', 'r')
            ->andWhere("r.motifRelanced = '2'")
            // ->leftJoin('p.clotures', 'c')
            // ->andWhere('c.motifCloture is NULL')
            ->setParameter('teams', $teams);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect  Unjoiniable pour chef
    public function getProspectCountUnjoiniableCmrcl($id)
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)
            ->leftJoin('p.relanceds', 'r')
            ->andWhere("r.motifRelanced = '2'");

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    //compter le nombre pre client du admin 
    public function getpreclientAdmin()
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT c.id)')
            ->from(Client::class, 'c')

            ->where('c.status = 2 OR c.status IS NULL')
        ;



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    //compter le nombre pre client du validateur
    public function getpreclientValide()
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT c.id)')
            ->from(Client::class, 'c')

            ->where('c.status = 2 OR c.status IS NULL')
        ;



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }
    //compter le nombre pre client du admin 
    public function getpreclientChef(User $user): int
    {
        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT c.id)')
            ->from(Client::class, 'c')

            ->where('c.status = 2 OR c.status IS NULL')
            ->andwhere('c.team IN (:teams)')

            ->setParameter('teams', $teams);



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }
    //compter le nombre pre client du admin 
    public function getpreclientCmrcl($id)
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT c.id)')
            ->from(Client::class, 'c')

            ->where('c.status = 2 OR c.status IS NULL')
            ->andWhere('c.cmrl = :val')
            ->setParameter('val', $id);



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    //compter le nombre pre contrat du admin 
    public function getpreContratAdmin()
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT c.id)')
            ->from(Contrat::class, 'c')

            ->where('c.status = 2 OR c.status IS NULL')
        ;



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    public function getpreContratComrcl($id)
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT c.id)')
            ->from(Contrat::class, 'c')

            ->where('c.status = 2 OR c.status IS NULL')
            ->andWhere('c.comrcl = :val')
            ->setParameter('val', $id)
        ;



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }
}
