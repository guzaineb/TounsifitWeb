<?php

namespace App\security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if ($user->isIsbanned()) {
            throw new CustomUserMessageAuthenticationException("You're banned Contact us for more infos.");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {

    }
}