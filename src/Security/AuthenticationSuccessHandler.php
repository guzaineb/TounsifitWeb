<?php

namespace App\Security; // Namespace corrected to match directory structure

use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; // Correct namespace for Response
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository,LoggerInterface $logger )
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        // Retrieve the authenticated user from the token
        $email = $token->getUser()->getUsername();
        $user = $this->userRepository->findOneBy(['email' => $email]);



        // Check if the user is banned
        if ($user && $user->isIsBanned()) {
            $this->logger->info("11111 :" .$user->getEmail());
            // Redirect to the login page with a flash message indicating the account is blocked
            $request->getSession()->getFlashBag()->add('error', 'Your account has been blocked. Please contact support for assistance.');

            return new RedirectResponse('/login'); // Adjust the URL if necessary
        }

        // If the user is not banned, redirect them to the homepage or any other route
        return new RedirectResponse('/front');    }
}
