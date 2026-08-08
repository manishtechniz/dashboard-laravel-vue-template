<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ProcessZipArchiveJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

    protected int $clubId;
    protected ?int $adminId;
    protected string $zipRelativePath;
    protected string $originalZipName;

    public $tries = 3;
    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(int $clubId, ?int $adminId, string $zipRelativePath, string $originalZipName)
    {
        $this->clubId = $clubId;
        $this->adminId = $adminId;
        $this->zipRelativePath = $zipRelativePath;
        $this->originalZipName = $originalZipName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        // $fullZipPath = storage_path('app/' . $this->zipRelativePath);
        $fullZipPath = Storage::path($this->zipRelativePath);
        $uniqueDir = 'temp_zip/' . ($this->batch()?->id ?? Str::uuid());
        $extractPath = Storage::path($uniqueDir);

        if (! file_exists($fullZipPath)) {
            throw new \RuntimeException("ZIP file {$this->originalZipName} not found on server.");
        }

        $zip = new ZipArchive();
        $openResult = $zip->open($fullZipPath);

        if ($openResult !== true) {
            @unlink($fullZipPath);
            throw new \RuntimeException("Unable to open ZIP archive '{$this->originalZipName}' (Error code: {$openResult}).");
        }

        // Extract to unique temporary directory
        File::ensureDirectoryExists($extractPath);
        $zip->extractTo($extractPath);
        $zip->close();

        // Delete uploaded zip file
        @unlink($fullZipPath);

        // Scan all extracted files
        $allFiles = File::allFiles($extractPath);
        $candidateJobs = [];

        foreach ($allFiles as $file) {
            $relativePath = str_replace('\\', '/', $file->getRelativePathname());

            // Skip __MACOSX, .DS_Store, Thumbs.db, hidden dotfiles
            if (
                str_starts_with($relativePath, '__MACOSX/')
                || str_contains($relativePath, '/__MACOSX/')
                || str_starts_with(basename($relativePath), '.')
                || basename($relativePath) === 'Thumbs.db'
            ) {
                @unlink($file->getRealPath());
                continue;
            }

            $candidateRelativePath = "{$uniqueDir}/{$relativePath}";
            $candidateJobs[] = new ProcessClubAssetFileJob(
                $this->clubId,
                $this->adminId,
                $candidateRelativePath,
                $file->getFilename(),
                true
            );
        }

        if (empty($candidateJobs)) {
            File::deleteDirectory($extractPath);
            throw new \RuntimeException("The ZIP archive '{$this->originalZipName}' contains no valid files or only empty folders.");
        }

        // Dynamically append individual file processing jobs into Laravel's native Bus batch
        if ($this->batch()) {
            $this->batch()->add($candidateJobs);
        } else {
            foreach ($candidateJobs as $job) {
                dispatch($job);
            }
        }
    }
}
