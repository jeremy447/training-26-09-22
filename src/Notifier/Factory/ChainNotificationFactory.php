<?php

namespace App\Notifier\Factory;

use Symfony\Component\Notifier\Notification\Notification;

class ChainNotificationFactory implements NotificationFactoryInterface
{
    /** @var NotificationFactoryInterface[]  */
    private iterable $factories;

    public function __construct(iterable $factories)
    {
        $this->factories = $factories instanceof \Traversable ? iterator_to_array($factories) : $factories;
    }

    public function createNotification(string $subject, string $channel = ''): Notification
    {
        return $this->factories[$channel]->createNotification($subject);
    }
}