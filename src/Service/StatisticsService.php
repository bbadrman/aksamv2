<?php

namespace App\Service;

/**
 * Service pour calculer les statistiques des prospects et contrats
 */
class StatisticsService
{
    /**
     * Calcule toutes les statistiques pour prospects et contrats
     */
    public function calculateAllStatistics(array $prospects, array $contrats, array $teams): array
    {
        return [
            'total' => $this->calculateTotalStatistics($prospects, $contrats),
            'teams' => $this->calculateTeamsStatistics($teams, $prospects, $contrats),
            'users' => $this->calculateUsersStatistics($teams, $prospects, $contrats)
        ];
    }

    /**
     * Calcule les statistiques totales
     */
    public function calculateTotalStatistics(array $prospects, array $contrats): array
    {
        $prospectsWithTeam = array_filter($prospects, fn($p) => $p->getTeam() !== null);
        $validContrats = array_filter($contrats, fn($c) => $c->getStatus() == 1);

        return [
            'prospects_count' => count($prospectsWithTeam),
            'contrats_count' => count($validContrats),
            'cotisation_total' => $this->calculateCotisationTotal($validContrats),
            'source' => $this->calculateSourceStatistics($prospectsWithTeam),
            'type_prospect' => $this->calculateTypeProspectStatistics($prospectsWithTeam),
            'motif_saise' => $this->calculateMotifSaiseStatistics($prospectsWithTeam),
            'activites' => $this->calculateActivitesStatistics($prospectsWithTeam)
        ];
    }

    /**
     * Calcule les statistiques par équipe
     */
    public function calculateTeamsStatistics(array $teams, array $prospects, array $contrats): array
    {
        $teamsStats = [];

        foreach ($teams as $team) {
            $teamsStats[$team->getId()] = $this->calculateTeamStatistics($team, $prospects, $contrats);
        }

        return $teamsStats;
    }

    /**
     * Calcule les statistiques d'une équipe
     */
    public function calculateTeamStatistics($team, array $prospects, array $contrats): array
    {
        $teamProspects = array_filter($prospects, fn($p) => $p->getTeam() === $team);
        $teamContrats = array_filter($contrats, function ($c) use ($team) {
            return $c->getStatus() == 1 &&
                $c->getUser() !== null &&
                $team->getUsers()->contains($c->getUser());
        });

        return [
            'prospects_count' => count($teamProspects),
            'contrats_count' => count($teamContrats),
            'cotisation_total' => $this->calculateCotisationTotal($teamContrats),
            'source' => $this->calculateSourceStatistics($teamProspects),
            'type_prospect' => $this->calculateTypeProspectStatistics($teamProspects),
            'motif_saise' => $this->calculateMotifSaiseStatistics($teamProspects),
            'activites' => $this->calculateActivitesStatistics($teamProspects)
        ];
    }

    /**
     * Calcule les statistiques par utilisateur
     */
    public function calculateUsersStatistics(array $teams, array $prospects, array $contrats): array
    {
        $usersStats = [];

        foreach ($teams as $team) {
            foreach ($team->getUsers() as $user) {
                $usersStats[$user->getId()] = $this->calculateUserStatistics($user, $prospects, $contrats);
            }
        }

        return $usersStats;
    }

    /**
     * Calcule les statistiques d'un utilisateur
     */
    public function calculateUserStatistics($user, array $prospects, array $contrats): array
    {
        $userProspects = array_filter($prospects, fn($p) => $p->getComrcl() === $user);
        $userContrats = array_filter($contrats, fn($c) => $c->getUser() === $user && $c->getStatus() == 1);

        return [
            'prospects_count' => count($userProspects),
            'contrats_count' => count($userContrats),
            'cotisation_total' => $this->calculateCotisationTotal($userContrats),
            'source' => $this->calculateSourceStatistics($userProspects),
            'type_prospect' => $this->calculateTypeProspectStatistics($userProspects),
            'motif_saise' => $this->calculateMotifSaiseStatistics($userProspects),
            'activites' => $this->calculateActivitesStatistics($userProspects)
        ];
    }

    /**
     * Calcule les statistiques de source
     */
    private function calculateSourceStatistics(array $prospects): array
    {
        return [
            'saisir' => count(array_filter($prospects, fn($p) => $p->getSource() == 1)),
            'site_pub' => count(array_filter($prospects, fn($p) => $p->getSource() === null)),
            'revendeur' => count(array_filter($prospects, fn($p) => $p->getSource() == 2)),
        ];
    }

    /**
     * Calcule les statistiques de type de prospect
     */
    private function calculateTypeProspectStatistics(array $prospects): array
    {
        return [
            'particulier' => count(array_filter($prospects, fn($p) => $p->getTypeProspect() == 1)),
            'professionnel' => count(array_filter($prospects, fn($p) => $p->getTypeProspect() == 2)),
        ];
    }

    /**
     * Calcule les statistiques de motif de saisie
     */
    private function calculateMotifSaiseStatistics(array $prospects): array
    {
        $motifStats = [];
        for ($i = 1; $i <= 6; $i++) {
            $motifStats[$i] = count(array_filter($prospects, fn($p) => $p->getMotifSaise() == $i));
        }
        return $motifStats;
    }

    /**
     * Calcule les statistiques d'activités
     */
    private function calculateActivitesStatistics(array $prospects): array
    {
        $activitesStats = [];
        for ($i = 1; $i <= 8; $i++) {
            $activitesStats[$i] = count(array_filter($prospects, fn($p) => $p->getActivites() == $i));
        }
        return $activitesStats;
    }

    /**
     * Calcule le total des cotisations
     */
    private function calculateCotisationTotal(array $contrats): float
    {
        return array_reduce($contrats, function ($carry, $contrat) {
            return $carry + ($contrat->getCotisation() ?? 0);
        }, 0);
    }
}
