<?php

namespace App\Search;

use DateTimeInterface;

class SearchUser
{
    /**
     * @var string
     */
    public $q = '';

    
    /**
     * @var int
     */
    public $status;

    public ?\DateTimeInterface $dateMin = null;
    public ?\DateTimeInterface $dateMax = null;
    public ?string $team = null;
    public ?string $username = null;
    public ?string $contrat = null;
    public int $page = 1;
}
