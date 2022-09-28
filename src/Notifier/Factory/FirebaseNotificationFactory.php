<?php

namespace App\Notifier\Factory;

use App\Notifier\Notification\FirebaseNotification;
use Symfony\Component\Notifier\Notification\Notification;

class FirebaseNotificationFactory implements NotificationFactoryInterface, IterableFactoryInterface
{

    public function createNotification(string $subject): Notification
    {
        return new FirebaseNotification($subject);
    }

    public static function getDefaultIndexName(): string
    {
        return 'firebase';
    }
}