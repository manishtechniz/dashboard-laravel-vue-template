<?php

use Illuminate\Support\Facades\Storage;

function resolveApi($relativePath): string
{
    return env('BACKEND_URL') . '/' . ltrim($relativePath, '/');
}

function create422ErrorFormat(string $column, string $message, $preArray = [], $postArray = [])
{
    return array_merge(
        $preArray,
        [
            'message' => $message,
            'errors' => [
                $column => [$message],
            ],
        ],
        $postArray
    );
}


function getResolveTmpDisk()
{
    $disk = 'local';

    if (config('filesystems.default') !== 'public') {
        $disk = config('filesystems.default');
    }

    return $disk;
}

function getFileLoadUrl($tmpPath, $basePath)
{

    $disk = getResolveTmpDisk();

    // Check if the file actually exists in S3's tmp folder
    if (Storage::disk()->exists($tmpPath)) {

        // Define the new public destination
        $finalPath = rtrim($basePath, '/') . '/' . basename($tmpPath);

        // Storage::move automatically copies the file and DELETES it from tmp/
        Storage::disk($disk)->move($tmpPath, $finalPath);

        // Make the moved file publicly accessible (so the image loads in browsers)
        Storage::disk($disk)->setVisibility($finalPath, 'public');

        return [
            'final_path' => $finalPath
        ];
    }

    return null;
}

function previewImageURL()
{
    return asset('storage/preview-image.webp');
}
