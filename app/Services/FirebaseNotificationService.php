<?php

namespace App\Services;

use App\Model\Notification as ModelNotification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\MessageTarget;

use Kreait\Firebase\Messaging;
use Throwable;

class FirebaseNotificationService
{
    // PHP 8.1+ readonly property
    protected readonly Messaging $messaging;

    public function __construct()
    {
        // Ensure the path is absolute using storage_path() or base_path()
        $credentialPath = config('firebase.credentials');
        $absolutePath =  $credentialPath;

        $this->messaging = (new Factory)
            ->withServiceAccount($absolutePath)
            ->createMessaging();
    }

    /**
     * Send notification to a single device.
     */
    public function sendToToken(
        string $token,
        string $title,
        string $body,
        array $data = []
    ): bool {
        try {
            ModelNotification::create([
                'client_id' => $data['additional']['client_id'] ?? null,
                'title' => $title,
                'body' => $body,
                'type' => $data['type'] ?? null,
                'created_by' => $data['created_by'] ?? null,
                'remark' => $data['remark'] ?? null,
                'additional' => $data['additional'] ?? [],
            ]);

            // unset($data['additional']);

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create($title, $body))
                ->withData($this->formatData($data));

            $this->messaging->send($message);

            return true;
        } catch (Throwable $e) {
            Log::error('FCM SendToToken Error: ' . $e->getMessage(), ['token' => $token]);
            return false;
        }
    }

    /**
     * Send notification to multiple devices (Multicast).
     */
    public function sendToTokens(
        array $tokens,
        string $title,
        string $body,
        array $data = []
    ): array {
        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            // sendMulticast processes in chunks of 500 automatically in newer SDKs
            $report = $this->messaging->sendMulticast($message, $tokens);

            $errors = [];

            foreach ($report->failures() as $failure) {
                $errors[] = $failure->error()->getMessage();
            }

            return [
                'success' => $report->successes()->count(),
                'failed'  => $report->failures()->count(),
                'errors'  => $errors
            ];
        } catch (Throwable $e) {
            Log::error('FCM SendToTokens Error: ' . $e->getMessage());
            return [
                'success' => 0,
                'failed'  => count($tokens),
                'errors'  => [$e->getMessage()]
            ];
        }
    }

    /**
     * Send notification to a topic.
     */
    public function sendToTopic(
        string $topic,
        string $title,
        string $body,
        array $data = []
    ): bool {
        try {
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $this->messaging->send($message);

            return true;
        } catch (Throwable $e) {
            Log::error("FCM SendToTopic Error [Topic: {$topic}]: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a data-only notification (silent push notification).
     */
    public function sendDataOnly(
        string $token,
        array $data
    ): bool {
        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withData($data);

            $this->messaging->send($message);

            return true;
        } catch (Throwable $e) {
            Log::error('FCM DataOnly Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Firebase only accepts string values for data payloads.
     */
    protected function formatData(array $data): array
    {
        return collect($data)
            ->map(fn($value) => is_array($value) ? json_encode($value) : $value)
            ->toArray();
    }
}
