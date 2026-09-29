<?php

use App\Model\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Vite;

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
    return Vite::asset('resources/images/preview-image.webp');
    return asset('storage/preview-image.webp');
}
function previewProfileURL()
{
    return Vite::asset('resources/images/avatar-preview.png');
    return asset('storage/avatar-preview.png');
}

function logo()
{
    return Vite::asset('resources/images/logo.png');
    return asset('storage/logo.png');
}

function hasPermission($permission)
{
    $user = Auth::guard('admin')->user();

    if (empty($user)) {
        return false;
    }

    return $user->hasPermission($permission);
}

function notificationAdditionalArrayFormat($data = [])
{
    return array_merge([
        'screen' => null,
        'booking_id' => null,
        'client_id' => null
    ], $data);
}

function sendSMSOtp(string $identifier, string|int $otp): JsonResponse
{
    $smsUrl = env('SMS_URL');
    $smsKey = env('SMS_KEY');

    try {
        $response = Http::timeout(10)->get($smsUrl, [
            'authkey' => $smsKey,
            'mobile'  => $identifier,
            'otp'     => $otp,
        ]);

        if ($response->successful()) {
            Cache::put('otp_' . $identifier, $otp, now()->addMinutes(3));

            return response()->json([
                'status'  => true,
                'message' => 'OTP sent successfully.',
            ], 200);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Failed to send OTP.',
            'error'   => $response->body(),
        ], 502);
    } catch (\Throwable $e) {
        return response()->json([
            'status'  => false,
            'message' => 'An error occurred while sending OTP.',
            // 'error'   => $e->getMessage(),
        ], 500);
    }
}


function sitemapLastModified(string $slug): ?string
{
    $path = resource_path(
        "views/frontend/{$slug}.blade.php"
    );

    if (! file_exists($path)) {
        return null;
    }

    return date(DATE_ATOM, filemtime($path));
}

function getSystemConfig($key, $default = null)
{
    $config = Setting::where('key', $key)->first();

    if (empty($config)) {
        return $default;
    }

    return $config->value;
}

function getSystemConfigArray($key)
{
    $configs = Setting::select('key', 'value', 'type')->where('key', 'LIKE', $key . '%')
        ->get()->toArray();

    $newConfigs = [];
    foreach ($configs as $config) {
        $newConfigs[$config['key']] = $config;
    }

    return $newConfigs;
}
