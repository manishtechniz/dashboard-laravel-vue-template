<?php

namespace Tests\Unit;

use App\Jobs\ProcessBulkAssetUploadJob;
use App\Jobs\ProcessZipAssetUploadJob;
use App\Model\Club;
use App\Model\ClubAsset;
use App\Model\ClubAssetBatch;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;
use ZipArchive;

class ClubAssetTest extends TestCase
{
    public function test_bulk_asset_job_and_zip_processing(): void
    {
        $club = Club::first();
        if (! $club) {
            $club = Club::create([
                'name' => 'Imperial VIP Club',
                'description' => 'Test Club',
                'is_active' => true,
            ]);
        }

        // 1. Test Multiple Files Upload Job
        $batchUuid1 = (string) Str::uuid();
        $batch1 = ClubAssetBatch::create([
            'batch_uuid'        => $batchUuid1,
            'club_id'           => $club->id,
            'source_type'       => 'multiple_files',
            'original_filename' => '2 files',
            'total_files'       => 2,
            'status'            => 'pending',
            'progress_percent'  => 0,
        ]);

        // Create 2 dummy files in storage/app/temp_uploads
        $tempDir = storage_path('app/temp_uploads/' . $batchUuid1);
        File::ensureDirectoryExists($tempDir);

        $imgFile = $tempDir . '/img1.png';
        // Create 1x1 transparent PNG binary
        file_put_contents($imgFile, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));

        $invalidFile = $tempDir . '/test.txt';
        file_put_contents($invalidFile, 'some text');

        $filesData = [
            [
                'temp_path'     => 'temp_uploads/' . $batchUuid1 . '/img1.png',
                'original_name' => 'banner_photo.png',
                'size'          => filesize($imgFile),
            ],
            [
                'temp_path'     => 'temp_uploads/' . $batchUuid1 . '/test.txt',
                'original_name' => 'notes.txt',
                'size'          => filesize($invalidFile),
            ],
        ];

        // Process synchronously
        $job1 = new ProcessBulkAssetUploadJob($batchUuid1, $club->id, 1, $filesData);
        $job1->handle();

        $batch1->refresh();
        $this->assertEquals(2, $batch1->processed_files);
        $this->assertEquals(1, $batch1->success_count);
        $this->assertEquals(1, $batch1->failed_count);
        $this->assertEquals(100.0, (float) $batch1->progress_percent);
        $this->assertEquals('partial_failure', $batch1->status);
        $this->assertNotEmpty($batch1->error_logs);

        // 2. Test ZIP File Processing Job
        $batchUuid2 = (string) Str::uuid();
        $batch2 = ClubAssetBatch::create([
            'batch_uuid'        => $batchUuid2,
            'club_id'           => $club->id,
            'source_type'       => 'zip',
            'original_filename' => 'media_pack.zip',
            'total_files'       => 0,
            'status'            => 'pending',
            'progress_percent'  => 0,
        ]);

        $zipPath = storage_path('app/temp_uploads/' . $batchUuid2 . '.zip');
        File::ensureDirectoryExists(dirname($zipPath));

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('photo1.jpg', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));
        $zip->addFromString('video1.mp4', 'dummy video content mp4 header');
        $zip->addFromString('unsupported.pdf', 'dummy pdf');
        $zip->close();

        $job2 = new ProcessZipAssetUploadJob($batchUuid2, $club->id, 1, 'temp_uploads/' . $batchUuid2 . '.zip', 'media_pack.zip');
        $job2->handle();

        $batch2->refresh();
        $this->assertEquals(3, $batch2->total_files);
        $this->assertEquals(3, $batch2->processed_files);
        $this->assertEquals(2, $batch2->success_count);
        $this->assertEquals(1, $batch2->failed_count);
        $this->assertEquals(100.0, (float) $batch2->progress_percent);
        $this->assertEquals('partial_failure', $batch2->status);

        // Verify created club_assets
        $createdAssets = ClubAsset::where('batch_id', $batchUuid2)->get();
        $this->assertCount(2, $createdAssets);

        $imageAsset = $createdAssets->firstWhere('file_type', 'image');
        $this->assertNotNull($imageAsset);
        $this->assertNotEmpty($imageAsset->url);

        $videoAsset = $createdAssets->firstWhere('file_type', 'video');
        $this->assertNotNull($videoAsset);
        $this->assertEquals('video', $videoAsset->file_type);

        echo "\n[PASS] All unit tests for multiple upload, zip extraction, validation, error logging, and ClubAsset creation passed successfully!\n";
    }
}
