<?php

namespace App\Security; // Namespace corrected to match directory structure

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; // Correct namespace for Response
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        // Retrieve the authenticated user from the token
        $user = $token->getUser();

        // Check if the user is banned
        if ($user->isIsBanned()) {
            // Throw an exception to prevent the login
            throw new CustomUserMessageAuthenticationException('Your account has been blocked. Please contact support for assistance.');
        }

        // If the user is not banned, redirect them to the homepage or any other route
        return new RedirectResponse('/front');    }
}
