<?php

namespace App\Jobs;

use App\Model\Club;
use App\Services\FirebaseNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;

class RecalculateClubRatingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 3;
    public $maxExceptions = 3;

    /**
     * Create a new job instance. 
     */
    public function __construct(public int $clubId) {}

    /**
     * Execute the job. 
     */
    public function handle(): void
    {
        $club = Club::find($this->clubId);

        if ($club) {
            $club->recalculateRating();
        }
    }
}
