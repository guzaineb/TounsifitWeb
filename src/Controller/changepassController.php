<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class changepassController extends AbstractController
{
    /**
     * @Route("/changepass", name="change_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $oldPassword = $request->request->get('old_password');
            $newPassword = $request->request->get('new_password');

            if (empty($oldPassword) || empty($newPassword)) {
                // Handle empty password fields
                $this->addFlash('error', 'Old password and new password cannot be empty.');
                return $this->redirectToRoute('user_profile');
            }

            if (!$passwordEncoder->isPasswordValid($user, $oldPassword)) {
                // Handle invalid old password
                $this->addFlash('error', 'Invalid old password.');
                return $this->redirectToRoute('change_password');
            }

            $user->setPassword($passwordEncoder->encodePassword($user, $newPassword));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Password changed successfully.');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('profile/changepass.html.twig');
    }
}
