<?php

namespace App\Search;

use DateTime;
use DateTimeInterface;

class SearchProspect
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var string
     */
    public $m = '';

    /**
     * @var string
     */
    public $g = '';

    /**
     * @var string
     */
    public $l = '';

    /**
     * @var string
     */
    public  $team = '';

    /**
     * @var string
     */
    public $c = '';

    /**
     * @var string
     */
    public $r = '';
    /**
     * @var string
     */
    public $s = '';

    /**
     * @var DateTimeInterface|null
     */
    public $d;

    /**
     * @var DateTimeInterface|null
     */
    public $dd;
    /**
     * @var int
     */
    /**
     * @var string
     */
    public $dr;

    /**
     * @var string
     */
    public $ddr;
    /**
     * @var int
     */
    public $source;
    /**
     * @var int
     */
    public $motifRelanced;
    /**
     * @var int|null
     */
    public $y;
    /**
     * @var int|null
     */
    public $u;
    /**
     * Récupère l'année de la recherche
     * 
     * @return int|null L'année de la recherche ou null si non définie
     */
    public function getYear(): ?int
    {
        // Récupérer l'année depuis la propriété de la classe
        return $this->y;
    }

    private $month;

    public function setMonth($month)
    {
        $this->month = $month;
    }

    public function getMonth()
    {
        return $this->month;
    }
    // Dans la classe SearchProspect
    public function getStartDate(): ?DateTimeInterface
    {
        return $this->d; // Supposons que 'd' représente la date de début dans votre formulaire
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->dd; // Supposons que 'dd' représente la date de fin dans votre formulaire
    }
}
