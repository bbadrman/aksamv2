<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Contrat;
use App\Entity\Prospect;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[IsGranted('IS_AUTHENTICATED')]
class StatsService
{


    public function __construct(private EntityManagerInterface $manager, private  Security $security, TagAwareCacheInterface $cache) {}

    public function getStats(): array

    {

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return []; // ou return 0, ou [] selon le besoin de ton appelant
        }


        $users    = $this->getUsersCount();
        $teams      = $this->getTeamsCount();
        $products = $this->getProductsCount();
        $clients = $this->getClientsCount();
        $contrats = $this->getContratsCount();
        // $prospectsAffect = $this->getProspectCount();



        $prospectsChefNv = $this->getProspectChefNv($user);

        // AFFECTATIONS
        $prospectsChefNvAll = $this->getProspectChefNvAll($user);
        $prospectspasaffect = $this->getProspectPasCount();
        // NOUVEAUX 
        $prospectsCmrclNv = $this->getProspectCmrclNv($user);

        //// RELANCES DU JOUR
        $prospectsDay = $this->getProspectCountRelance();
        $prospectsDayChef = $this->getProspectCountRelanceChef($user);
        $prospectsDayCmrcl = $this->getProspectCountRelanceCmrcl($user);

        // RELANCES A VENIR
        $prospectsAvenir = $this->getfindAvenir();
        $prosAvenirChef = $this->getfindAvenirChef($user);
        $prosAvenirCmrcl = $this->getAvenirCmrcl($user);


        // PROSPECTS NON TRAITÉS
        $prospectsNoTraite = $this->getfindProspectNonTraiter();
        $prospectsNoTrChef = $this->getfindProspectNonTraiterChef($user);
        $prospectsNoTrCmrcl = $this->getfindProspectNonTraiterCmrcl($user);

        // RELANCES NON TRAITÉES 
        $relanceNoTraite = $this->getfindRelancesNonTraitees();
        $relancesNoTrChef = $this->getRelancesNonTraiteesChef($user);
        $relancesNoTrCmrcl = $this->getRelancesNonTraiteesCmrcl($user);

        // caclcule le total du prospect en panier
        // $prospectsPanier = $this->getProspectCountPanier();
        // $prospectsPanierJour = $this->getProspectCountPanierJour();

        // $prospectsnow = $this->getProspectCountNow();
        $prospects = $this->getProspectTotlCount();

        // TOUJOUR INJOIGNABLES
        $unjoiniable = $this->getfindUnjoing();
        $unjoiniableChef = $this->getfindUnjoingChef($user);
        $unjoiniableCmrl = $this->getfindUnjoingCmrcl($user);

        // $prospectsnow = $this->getProspectCountNow();

        // compter client
        $preclientAdmin = $this->getfindClientAdmin();
        $preclientChef = $this->getfindClientChef($user);
        $preclientComrcl = $this->getfindClientCmrcl($user);
        $preclientValide = $this->getpreclientValide($user);
        // compter contrat
        $preContratAdmin = $this->getpreContratAdmin();
        $preContratComrcl = $this->getpreContratComrcl($user);


        return compact('preContratComrcl', 'preContratAdmin', 'contrats', 'preclientValide',  'preclientAdmin', 'preclientChef', 'preclientComrcl',  'relancesNoTrCmrcl', 'relancesNoTrChef', 'relanceNoTraite', 'prosAvenirCmrcl', 'prosAvenirChef', 'prospectsAvenir', 'unjoiniableCmrl', 'unjoiniableChef', 'prospectsNoTrCmrcl', 'prospectsNoTrChef', 'prospectsDayCmrcl', 'prospectsDayChef', 'prospectsCmrclNv', 'prospectsChefNv', 'prospectsChefNvAll', 'prospectsNoTraite', 'unjoiniable', 'prospects', 'prospectspasaffect', 'prospectsDay', 'users', 'teams', 'products', 'clients');
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

            ->andWhere('p.relance IS NULL');


        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }


    // les nouveaux prospects cree ce jour affecter a mon equipe
    public function getProspectChefNv(User $user): int
    {

        if (!$user) {
            return 0;
        }
        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->where('p.team IN (:teams)')
            ->andWhere('p.team IS NOT NULL')
            ->andWhere('p.comrcl IS NULL')

            ->andWhere('p.relance IS NULL')

            ->setParameter('teams', $teams);


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

            ->andwhere('p.team IN (:teams)')
            ->setParameter('teams', $teams)
            ->andWhere('p.comrcl IS NULL')
            ->andWhere('p.relance IS NULL')


        ;

        // dd($qb->getQuery()->getSQL());
        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();
        // Log the result for debugging

        return (int) $result;
    }
    // afficher les neveaux prospects au cmerciel
    public function getProspectCmrclNv($id)
    {
        $startOfToday = new \DateTime('today');

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from('App\Entity\Prospect', 'p')
            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

            ->andWhere('p.relance IS NULL')

            ->andWhere('p.AffectAt >= :endOfYesterday') // les prospects affect h'Aujourduit 
            ->setParameter('endOfYesterday', $startOfToday);


        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }



    // caclcule le total du prospect relancer à venir pour admin
    public function getfindAvenir()
    {
        $tomorrow = new \DateTime('tomorrow');


        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')

            ->Where('p.relanceAt >= :tomorrow')
            ->setParameter('tomorrow', $tomorrow);



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect relancer à venir
    public function getfindAvenirChef(User $user): int
    {
        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        $tomorrow = new \DateTime('tomorrow');
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')


            ->andWhere('p.relanceAt >= :tomorrow')
            ->andwhere('p.team IN (:teams)')
            ->setParameter('teams', $teams)
            ->setParameter('tomorrow', $tomorrow);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect relancer à venir du cmrcl
    public function getAvenirCmrcl($id)
    {

        $tomorrow = new \DateTime('tomorrow');
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')

            ->andWhere('p.relanceAt >= :tomorrow')
            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('p.relanceAt', 'ASC');


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



            ->andWhere('p.relance NOT IN (:motifs)')
            ->andwhere('p.relanceAt BETWEEN :startOfDay AND :endOfDay')
            ->setParameter('startOfDay', $today)
            ->setParameter('endOfDay', $endOfDay)

            ->setParameter('motifs', [6, 7, 8, 9, 10, 11, 12, 13])
            ->orderBy('p.relanceAt', 'ASC');


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
            ->setParameter('teams', $teams)

            ->andWhere('p.relance NOT IN (:motifs)')
            ->andwhere('p.relanceAt BETWEEN :startOfDay AND :endOfDay')
            ->setParameter('startOfDay', $today)
            ->setParameter('endOfDay', $endOfDay)

            ->setParameter('motifs', [6, 7, 8, 9, 10, 11, 12, 13]);

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

            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)

            ->andWhere('p.relance NOT IN (:motifs)')
            ->andwhere('p.relanceAt BETWEEN :startOfDay AND :endOfDay')
            ->setParameter('startOfDay', $today)
            ->setParameter('endOfDay', $endOfDay)

            ->setParameter('motifs', [6, 7, 8, 9, 10, 11, 12, 13])
            ->orderBy('p.relanceAt', 'ASC');


        //->andWhere('r.motifRelanced = 1');
        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }




    // caclcule le total du relance Non traite pour admin
    public function getfindRelancesNonTraitees()
    {
        $yesterday = (new \DateTime('yesterday'))->setTime(23, 59, 59);

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')  //  En utilisant COUNT(DISTINCT p.id), vous comptez les prospects uniques, en ignorant les doublons dans les relances pour chaque prospect
            ->from(Prospect::class, 'p')
            ->andWhere('p.relanceAt <= :endOfYesterday')
            ->setParameter('endOfYesterday', $yesterday);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du relance Non traite pour chef
    public function getRelancesNonTraiteesChef(User $user): int
    {
        $yesterday = (new \DateTime('yesterday'))->setTime(23, 59, 59);


        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')


            ->andwhere('p.team IN (:teams)')
            ->setParameter('teams', $teams)



            ->andWhere('p.relanceAt <= :endOfYesterday')
            ->setParameter('endOfYesterday', $yesterday);


        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du relance Non traite pour comerciale
    public function getRelancesNonTraiteesCmrcl($id)
    {

        $yesterday = (new \DateTime('yesterday'))->setTime(23, 59, 59);

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')


            ->andWhere('p.comrcl = :val')
            ->setParameter('val', $id)
            ->andWhere('p.relanceAt <= :endOfYesterday')

            ->setParameter('endOfYesterday', $yesterday);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect Non traite pour admin
    public function getfindProspectNonTraiter()
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59);  // il depacer un jour a partir de minuit

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')


            ->andWhere('p.team IS NOT NULL')  // deja Affecté à une équipe  
            ->andWhere('p.relance IS NULL')  // n'est pas encor relancer 
            // il faut changer creatAt par affectAt 
            ->leftJoin('p.comrcl', 'f')
            ->leftJoin('p.team', 't')
            ->andWhere('p.AffectAt <= :yesterday')
            ->setParameter('yesterday', $yesterday);


        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }
    // caclcule le total du prospect Non traite pour chef
    public function getfindProspectNonTraiterChef(User $user): int
    {
        $now = new \DateTime();
        $yesterday = clone $now;
        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59);

        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')

            ->where('p.team IN (:teams)')
            ->setParameter('teams', $teams)

            ->andWhere('p.team IS NOT NULL')  // chef d'equipe affecté 
            ->andWhere('p.relance IS NULL')  // n'est pas encor relancer 

            ->andWhere('p.AffectAt <= :endOfYesterday')
            ->setParameter('endOfYesterday', $yesterday)

        ;
        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect Non traite pour comerciale
    public function getfindProspectNonTraiterCmrcl($id)
    {
        $now = new \DateTime();
        $yesterday = clone $now;

        $yesterday->modify('-24 hours');
        $yesterday->setTime(23, 59, 59);

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')


            ->where('p.comrcl = :val')
            ->setParameter('val', $id)

            ->andWhere('p.relance IS NULL')  // n'est pas encor relancer 

            ->andWhere('p.AffectAt <= :endOfYesterday')
            ->setParameter('endOfYesterday', $yesterday);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }




    // caclcule le total du prospect  Unjoiniable 
    public function getfindUnjoing()
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')

            ->where('p.relance = 11');

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }
    // caclcule le total du prospect  Unjoiniable pour chef
    public function getfindUnjoingChef(User $user): int
    {
        $teams = $user->getTeams();

        if ($teams->isEmpty()) {
            return 0;
        }
        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->where('p.team IN (:teams)')

            ->andwhere('p.relance = 11')
            ->setParameter('teams', $teams);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    // caclcule le total du prospect  Unjoiniable pour chef
    public function getfindUnjoingCmrcl($id)
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT p.id)')
            ->from(Prospect::class, 'p')
            ->Where('p.comrcl = :val')
            ->setParameter('val', $id)
            ->andwhere('p.relance = 11');

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }

    //compter le nombre pre client du admin 
    public function getfindClientAdmin()
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
    public function getfindClientChef(User $user): int
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
    public function getfindClientCmrcl($id)
    {

        $qb = $this->manager->createQueryBuilder();
        $qb->select('COUNT(DISTINCT c.id)')
            ->from(Client::class, 'c')

            ->where('c.status = 2 OR c.status IS NULL')
            ->andWhere('c.cmrcl = :val')
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
            ->andWhere('c.user = :val')
            ->setParameter('val', $id)
        ;



        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }
}
