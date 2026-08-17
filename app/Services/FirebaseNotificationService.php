<?php

namespace App\Services;

use App\Model\Notification as ModelNotification;
use Carbon\Carbon;
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
            // dd($data);
            $clientIds = $data['additional']['client_ids'] ?? [];

            if (empty($clientIds)) {
                return [];
            }

            $insertData = [];
            $now = Carbon::now();
            foreach ($clientIds as $clientId) {
                $insertData[] = [
                    'client_id'  => $clientId,
                    'title'      => $title,
                    'body'       => $body,
                    'type'       => $data['type'] ?? null,
                    'created_by' => $data['created_by'] ?? null,
                    'remark'     => $data['remark'] ?? null,
                    'additional' => empty($data['additional']) ? json_encode([]) : json_encode($data['additional']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // dd($insertData);
            }

            $chunks = array_chunk($insertData, 500);

            foreach ($chunks as $chunk) {
                // dd($chunk);
                ModelNotification::insert($chunk);
            }

            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($this->formatData($data));

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

    protected function formatData(array $data): array
    {
        $fcmPayload = [];

        foreach ($data as $key => $value) {
            // 1. If it's an array or object, convert it to a JSON string.
            // (FCM cannot accept nested arrays, so we must stringify the whole block)
            if (is_array($value) || is_object($value)) {
                // Optional: recursively stringify the inside of the array before JSON encoding
                $value = $this->recursivelyStringify($value);
                $fcmPayload[$key] = json_encode($value);
            }
            // 2. FCM crashes on null values. Convert null to an empty string.
            elseif (is_null($value)) {
                $fcmPayload[$key] = '';
            }
            // 3. FCM can crash on booleans. Convert to 'true' or 'false'.
            elseif (is_bool($value)) {
                $fcmPayload[$key] = $value ? 'true' : 'false';
            }
            // 4. Standard strings, integers, or floats are safely cast to string.
            else {
                $fcmPayload[$key] = (string) $value;
            }
        }

        return $fcmPayload;
    }

    /**
     * Recursively loops through nested arrays (3 levels or more) 
     * and converts all values to strings.
     */
    private function recursivelyStringify($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = [];
            foreach ($data as $key => $item) {
                $result[$key] = $this->recursivelyStringify($item);
            }
            return $result;
        }

        if (is_null($data)) return '';
        if (is_bool($data)) return $data ? 'true' : 'false';

        return (string) $data;
    }
}
