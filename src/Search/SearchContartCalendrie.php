<?php

namespace App\Search;

use DateTimeInterface;

class SearchContartCalendrie
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var DateTimeInterface|null
     */
    private $startDate;

    /**
     * @var DateTimeInterface|null
     */
    private $endDate;

    /**
     * Récupère la date de début
     *
     * @return DateTimeInterface|null
     */
    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * Définit la date de début
     *
     * @param DateTimeInterface|null $startDate
     */
    public function setStartDate(?DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * Récupère la date de fin
     *
     * @return DateTimeInterface|null
     */
    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * Définit la date de fin
     *
     * @param DateTimeInterface|null $endDate
     */
    public function setEndDate(?DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }
}
