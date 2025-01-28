<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{


    public function __construct(private Security $security) {}

    public function checkPreAuth(UserInterface $user)
    {

        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->getStatus()) {

            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas actif, voir avec l\'administrateur');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {

        if (!$user instanceof AppUser) {
            return;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return false;
        }
    }
}
