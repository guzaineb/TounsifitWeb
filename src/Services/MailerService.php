<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService {



    private $mailer;


    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function send($mail, $subject, $context) {
        $email = new TemplatedEmail();
        $email->from('no-reply@tounisfit.tn')->to($mail)->subject($subject)->html("<p>". $context ."</p>");
        $this->mailer->send($email);
    }

}