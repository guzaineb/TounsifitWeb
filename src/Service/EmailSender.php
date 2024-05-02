<?php
// src/Service/EmailSender.php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Environment;

class EmailSender
{
    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendEducationalInformationEmail($recipientEmail, $information)
    {
        $htmlContent = $this->twig->render('email/email.html.twig', ['information' => $information]);
        
        $email = (new Email())
            ->from('zayneb.guasmi@gmail.com')
            ->to($recipientEmail)
            ->subject('Information Éducative')
            ->html($htmlContent);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Unable to send email: '.$e->getMessage());
        }
    }
}
