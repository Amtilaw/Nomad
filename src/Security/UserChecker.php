<?php


namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserChecker implements UserCheckerInterface
{

 
    public function checkPreAuth(UserInterface $user): void
    {
        
        if (!$user instanceof AppUser) {
            return;
        }

        $isEnabled = $user->getIsEnabled();
        if ($isEnabled == '0') {
            throw new AuthenticationException();
        }

    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

    }
}