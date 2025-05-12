<?php

namespace App\Search;

use App\Entity\Contrat;
use App\Entity\User;
use DateTime;
use DateTimeInterface;

class SearchDocument
{
    /**
     * @var int
     */
    public $page = 1;


    public ?Contrat $contrat = null;
    public ?User $user = null;
}
