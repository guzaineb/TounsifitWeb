<?php
// src/EventListener/BanCheckListener.php

namespace App\Controller;
use App\Entity\User;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class BanCheckListener
{
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof User && $user->isIsBanned()==1) {
            throw new CustomUserMessageAuthenticationException('Your account has been blocked. Please contact support for assistance.');
        }
    }

}