<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\FCM\FCMMessage;
use NotificationChannels\FCM\Resources\Notification as FcmNotification;

class TestClientNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        $rawData = [
            'order_id' => 1,
            'screen' => 'booking',
        ];

        try {
            $fcmData = collect($rawData)
                ->map(fn($value) => is_array($value) ? json_encode($value) : (string) $value)
                ->toArray();

            // return (new FcmMessage(notification: new FcmNotification(
            //     title: 'Account Activated',
            //     body: 'Your account has been activated.',
            //     image: 'http://example.com/url-to-image-here.png'
            // )))
            //     ->data($fcmData)
            //     ->custom([
            //         'android' => [
            //             'notification' => [
            //                 'color' => '#0A0A0A',
            //                 'sound' => 'default',
            //             ],
            //             'fcm_options' => [
            //                 'analytics_label' => 'analytics',
            //             ],
            //         ],
            //         'apns' => [
            //             'payload' => [
            //                 'aps' => [
            //                     'sound' => 'default'
            //                 ],
            //             ],
            //             'fcm_options' => [
            //                 'analytics_label' => 'analytics',
            //             ],
            //         ],
            //     ]);

            // In your version of the package, you must pass everything inside the constructor!
            return new FcmMessage(
                data: $fcmData,
                notification: new FcmNotification(
                    title: 'Account Activated',
                    body: 'Your account has been activated.'
                )
            );

            // Use the proper create() and setter methods that your version requires
            return FcmMessage::create()
                ->setData($fcmData)
                ->setNotification(
                    FcmNotification::create()
                        ->setTitle('Account Activated')
                        ->setBody('Your account has been activated.')
                );
        } catch (\Throwable $e) {
            Log::error('FCM FROM Client: Error: ' . $e->getMessage());
        }
    }
}
