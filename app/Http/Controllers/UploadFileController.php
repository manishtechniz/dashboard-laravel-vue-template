<?php

namespace App\Http\Controllers;

use App\Traits\ResolveDiskTmpFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadFileController
{

    /**
     * Step 1: Generate a secure Presigned URL for the frontend to upload to 'tmp/'
     */
    public function getUploadUrl(Request $request)
    {
        $request->validate([
            'extension' => 'required|string|in:jpg,jpeg,png,webp',
        ]);

        // Create a random filename in the private tmp folder
        $fileName = Str::uuid() . '.' . $request->extension;

        $tmpPath = 'tmp/' . $fileName;

        // Generate a Presigned URL valid for 30 minutes
        $url = Storage::disk(getResolveTmpDisk())->temporaryUrl(
            $tmpPath,
            now()->addMinutes(30)
        );

        return response()->json([
            'upload_url' => $url,
            'tmp_path'   => $tmpPath, // Send this back to frontend so they can submit it later
        ]);
    }
}
