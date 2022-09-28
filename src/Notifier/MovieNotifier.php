<?php

namespace App\Notifier;

use App\Notifier\Factory\ChainNotificationFactory;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    private NotifierInterface $notifier;
    private ChainNotificationFactory $factory;

    public function __construct(NotifierInterface $notifier, ChainNotificationFactory $factory)
    {
        $this->notifier = $notifier;
        $this->factory = $factory;
    }

    public function sendNotification(string $subject)
    {
        $channel = 'slack';
        $notification = $this->factory->createNotification($subject, $channel);

        $this->notifier->send($notification, new Recipient('email@email.com'));
    }
}