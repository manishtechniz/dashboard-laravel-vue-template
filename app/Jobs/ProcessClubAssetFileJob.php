<?php

namespace App\Jobs;

use App\Model\ClubAsset;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessClubAssetFileJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;

    protected int $clubId;
    protected ?int $adminId;
    protected string $tempRelativePath;
    protected string $originalName;
    protected bool $deleteTempFile;

    public $tries = 3;
    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(int $clubId, ?int $adminId, string $tempRelativePath, string $originalName, bool $deleteTempFile = true)
    {
        $this->clubId = $clubId;
        $this->adminId = $adminId;
        $this->tempRelativePath = $tempRelativePath;
        $this->originalName = $originalName;
        $this->deleteTempFile = $deleteTempFile;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // If the batch was cancelled by the user, stop execution
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        $fullPath = Storage::path($this->tempRelativePath);

        // Log::info('job data', [
        //     'fullPath' => $fullPath,
        //     'is_exists' => file_exists($fullPath),
        // ]);

        if (! file_exists($fullPath)) {
            throw new \RuntimeException("Temporary file not found: {$this->originalName}");
        }

        $extension = strtolower(pathinfo($this->originalName, PATHINFO_EXTENSION));
        $allowedImages = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedVideos = ['mp4'];
        $allAllowed = array_merge($allowedImages, $allowedVideos);

        if (! in_array($extension, $allAllowed)) {
            if (true) {
                @unlink($fullPath);
            }
            throw new \InvalidArgumentException("Invalid file format '.{$extension}' for '{$this->originalName}'. Allowed: jpg, jpeg, png, webp, mp4");
        }

        $isImage = in_array($extension, $allowedImages);
        $fileType = $isImage ? 'image' : 'video';
        $mimeType = mime_content_type($fullPath) ?: ($isImage ? "image/{$extension}" : 'video/mp4');
        $fileSize = filesize($fullPath);

        $width = null;
        $height = null;

        if ($isImage) {
            $imageInfo = @getimagesize($fullPath);
            if ($imageInfo) {
                $width = $imageInfo[0] ?? null;
                $height = $imageInfo[1] ?? null;
            }
        }

        // Stored filename and relative destination in public storage disk
        $storedFileName = Str::random(30) . '_' . time() . '.' . $extension;
        $folder = "club_assets/{$this->clubId}/{$fileType}s";
        $targetRelativePath = "{$folder}/{$storedFileName}";

        Storage::disk('public')->makeDirectory($folder);

        $stream = fopen($fullPath, 'r');
        Storage::disk('public')->put($targetRelativePath, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }

        // Delete temporary file after moving to storage
        if ($this->deleteTempFile) {
            @unlink($fullPath);
        }

        // Title formatted from original file name
        $rawTitle = pathinfo($this->originalName, PATHINFO_FILENAME);
        $title = ucwords(str_replace(['_', '-'], ' ', $rawTitle));

        // Create ClubAsset entry with batch_id referencing Laravel's job_batches ID
        ClubAsset::create([
            'club_id'       => $this->clubId,
            'batch_id'      => $this->batch()?->id,
            'title'         => $title,
            'file_name'     => $storedFileName,
            'original_name' => $this->originalName,
            'file_path'     => $targetRelativePath,
            'file_type'     => $fileType,
            'mime_type'     => $mimeType,
            'file_size'     => $fileSize,
            'width'         => $width,
            'height'        => $height,
            'is_active'     => true,
            'created_by'    => $this->adminId,
        ]);
    }
}
