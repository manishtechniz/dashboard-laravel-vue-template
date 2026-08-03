<?php

namespace App\Jobs;

use App\Services\FirebaseNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param string $type The type of notification: 'token', 'tokens', 'topic', or 'data_only'
     * @param string|array $target The FCM token(s) or topic name
     * @param string|null $title The notification title
     * @param string|null $body The notification body
     * @param array $data Additional custom data
     */
    public function __construct(
        public readonly string $type,
        public readonly string|array $target,
        public readonly ?string $title = null,
        public readonly ?string $body = null,
        public readonly array $data = []
    ) {}

    /**
     * Execute the job.
     * Laravel's service container will automatically inject the FirebaseNotificationService here.
     */
    public function handle(FirebaseNotificationService $firebaseService): void
    {
        if (empty($this->target)) {
            return;
        }

        // dd($this->target, $this->title, $this->body, $this->data);

        match ($this->type) {
            'token' => $firebaseService->sendToToken($this->target, $this->title, $this->body, $this->data),
            'tokens' => $firebaseService->sendToTokens($this->target, $this->title, $this->body, $this->data),
            'topic' => $firebaseService->sendToTopic($this->target, $this->title, $this->body, $this->data),
            'data_only' => $firebaseService->sendDataOnly($this->target, $this->data),
            default => throw new InvalidArgumentException("Invalid notification type: {$this->type}"),
        };
    }
}
