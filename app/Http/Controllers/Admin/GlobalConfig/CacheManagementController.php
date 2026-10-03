<?php

namespace App\Http\Controllers\Admin\GlobalConfig;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\CacheManagerService;
use App\Http\Controllers\Admin\Controller;

class CacheManagementController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CacheManagerService $cacheManager) {}

    /**
     * Execute a cache action and return JSON response.
     */
    public function execute(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|string',
        ]);

        $result = $this->cacheManager->execute($request->input('action'));

        return new JsonResponse([
            'success' => $result['success'],
            'message' => $result['message'],
            'output' => $result['output'],
            'command' => $result['command'],
        ], $result['success'] ? 200 : 422);
    }
}
