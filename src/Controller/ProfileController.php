<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile(): Response
    {
        // Fetching user data from the database
        $user = $this->getUser();

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile/change-password", name="change_password")
     */

}
