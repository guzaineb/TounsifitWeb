<?php

namespace App\Controller;

use LogicException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository, Request $request, LoggerInterface $logger, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Check if the username exists in the request (submitted login form)
        if ($request->isMethod('POST')) {
            // Find the user by username
            $user = $userRepository->findOneBy(['email' => $lastUsername]);

            // Log the user retrieval attempt
            $logger->info('User retrieval attempt: ' . ($user ? 'User found' : 'User not found'));

            // If the user exists and is banned, prevent login and show a message
            if ($user && $user->isIsBanned()) {
                throw new CustomUserMessageAuthenticationException('Your account has been blocked. Please contact support for assistance.');
            }

            // Log the user's ban status
            if ($user) {
                $logger->info('User ban status: ' . ($user->isIsBanned() ? 'Banned' : 'Not banned'));
            }

            // If the user exists and the password is correct
            if ($user && $passwordHasher->isPasswordValid($user, $request->request->get('password'))) {
                // Set isConnected to 1
                $user->setIsConnected(true);

                // Persist changes to the database
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(EntityManagerInterface $entityManager, Security $security, UserRepository $userRepository):Response
    {
        // Get the current user
        $email = $security->getUser()->getUsername();
        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user) {
            // Change the isconnected property
            $user->setIsConnected(false); // Assuming setIsConnected is the setter method

            // Persist and flush the changes
            $entityManager->persist($user);
            $entityManager->flush();

        }
        return $this->redirectToRoute('app_front');
        // Do whatever other logout tasks you need to do
        // This method can still be blank if it's handled by the firewall
    }
}