<?php
// src/Service/NotificationManager.php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class NotificationManager
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function addNotification(string $message)
    {
        $notifications = $this->session->get('notifications', []);
        $notifications[] = $message;
        $this->session->set('notifications', $notifications);
    }

    public function getNotifications()
    {
        return $this->session->get('notifications', []);
    }
}