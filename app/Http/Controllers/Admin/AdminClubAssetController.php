<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\ProcessClubAssetFileJob;
use App\Jobs\ProcessZipArchiveJob;
use App\Model\Club;
use App\Model\ClubAsset;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminClubAssetController extends Controller
{
    /**
     * Display a listing of club assets or return JSON for ajax grid.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ClubAsset::with('club:id,name');

            // Club Filter
            if ($request->filled('club_id') && $request->club_id !== 'all') {
                $query->where('club_id', $request->club_id);
            }

            // Media Type Filter (image or video)
            if ($request->filled('file_type') && in_array($request->file_type, ['image', 'video'])) {
                $query->where('file_type', $request->file_type);
            }

            // Active Status Filter
            if ($request->filled('is_active') && $request->is_active !== 'all') {
                $query->where('is_active', (bool) $request->is_active);
            }

            // Search Filter
            if ($request->filled('search')) {
                $term = '%' . trim($request->search) . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', $term)
                        ->orWhere('original_name', 'like', $term)
                        ->orWhere('file_name', 'like', $term);
                });
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            if (in_array($sortBy, ['created_at', 'file_size', 'title', 'id'])) {
                $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
            } else {
                $query->orderBy('id', 'desc');
            }

            $perPage = (int) $request->get('per_page', 24);
            $assets = $query->paginate($perPage);

            return response()->json([
                'data'           => $assets->items(),
                'current_page'   => $assets->currentPage(),
                'last_page'      => $assets->lastPage(),
                'per_page'       => $assets->perPage(),
                'total'          => $assets->total(),
                'has_more_pages' => $assets->hasMorePages(),
            ]);
        }

        $clubs = Club::select('id', 'name')->orderBy('name')->get();
        return view('admin::club-assets.index', compact('clubs'));
    }

    /**
     * Handle bulk upload using Laravel native Bus::batch & job_batches table.
     */
    public function bulkUpload(Request $request): JsonResponse
    {
        $request->validate([
            'club_id'     => 'required|exists:clubs,id',
            'upload_type' => 'required|in:multiple,zip',
            'zip_file'    => 'required_if:upload_type,zip|nullable|file|mimes:zip|max:524288', // max 512MB
            'files'       => 'required_if:upload_type,multiple|nullable|array|min:1',
            'files.*'     => 'file|mimes:jpg,jpeg,png,webp,mp4|max:102400', // max 100MB per file
        ]);

        $club = Club::findOrFail($request->club_id);
        $adminId = auth('admin')->id();
        $uploadType = $request->upload_type;
        $batchId = (string) Str::uuid();

        if ($uploadType === 'zip') {
            $zipFile = $request->file('zip_file');
            $originalName = $zipFile->getClientOriginalName();
            $zipRelativePath = $zipFile->storeAs('temp_uploads', "{$batchId}.zip");

            $batch = Bus::batch([
                new ProcessZipArchiveJob((int) $club->id, $adminId, $zipRelativePath, $originalName)
            ])->name("Club Media ZIP: {$club->name} ({$originalName})")
                ->allowFailures()
                ->dispatch();

            return response()->json([
                'success'  => true,
                'message'  => 'ZIP archive uploaded. Job batch queued successfully.',
                'batch_id' => $batch->id,
                'batch'    => $this->formatBatch($batch, $club->name),
            ]);
        } else {
            $uploadedFiles = $request->file('files');
            $jobs = [];

            foreach ($uploadedFiles as $file) {
                $ext = $file->getClientOriginalExtension();
                $tempName = Str::random(25) . '.' . $ext;
                // $tempRelativePath = $file->storeAs("temp_uploads/{$batchId}", $tempName);
                $tempRelativePath = $file->storeAs("temp_uploads/{$batchId}", $tempName);

                $jobs[] = new ProcessClubAssetFileJob(
                    (int) $club->id,
                    $adminId,
                    $tempRelativePath,
                    $file->getClientOriginalName(),
                    true
                );
            }

            $count = count($jobs);
            $batch = Bus::batch($jobs)
                ->name("Club Media Upload: {$club->name} ({$count} files)")
                ->allowFailures()
                ->dispatch();

            return response()->json([
                'success'  => true,
                'message'  => "{$count} files uploaded. Job batch queued successfully.",
                'batch_id' => $batch->id,
                'batch'    => $this->formatBatch($batch, $club->name),
            ]);
        }
    }

    /**
     * Get live progress status of a specific batch from Laravel job_batches.
     */
    public function batchStatus(string $batchId): JsonResponse
    {
        $batch = Bus::findBatch($batchId);

        if (! $batch) {
            return response()->json([
                'success' => false,
                'message' => 'Job batch not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'batch'   => $this->formatBatch($batch),
        ]);
    }

    /**
     * Get list of recent batches from Laravel job_batches table.
     */
    public function batchesList(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);

        $paginated = DB::table('job_batches')
            ->where('name', 'like', 'Club Media%')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $batches = collect($paginated->items())->map(function ($b) {
            $total = (int) $b->total_jobs;
            $pending = (int) $b->pending_jobs;
            $failed = (int) $b->failed_jobs;
            $processed = $total - $pending;
            $progress = $total > 0 ? (int) round(($processed / $total) * 100) : 0;
            $finished = ! is_null($b->finished_at) || ($total > 0 && $pending === 0);
            $cancelled = ! is_null($b->cancelled_at);

            $workedJobs = $processed + $failed;

            $status = 'processing';
            if ($cancelled) {
                $status = 'cancelled';
            } elseif ($finished) {
                $status = $failed > 0 ? ($failed === $total ? 'failed' : 'partial_failure') : 'completed';
            } elseif ($workedJobs >= $total) {
                $status = $failed > 0 ? ($failed === $total ? 'failed' : 'partial_failure') : 'completed';
            }

            return [
                'id'             => $b->id,
                'name'           => $b->name,
                'total_jobs'     => $total,
                'pending_jobs'   => $pending,
                'processed_jobs' => $processed,
                'failed_jobs'    => $failed,
                'worked_job'     => $workedJobs,
                'progress'       => $progress,
                'status'         => $status,
                'finished'       => $finished,
                'created_at'     => Carbon::createFromTimestamp($b->created_at)->format('M d, h:i A'),
                'finished_at'    => $b->finished_at ? Carbon::createFromTimestamp($b->finished_at)->format('M d, h:i A') : null,
            ];
        });

        return response()->json([
            'success'        => true,
            'batches'        => $batches,
            'current_page'   => $paginated->currentPage(),
            'last_page'      => $paginated->lastPage(),
            'per_page'       => $paginated->perPage(),
            'total'          => $paginated->total(),
            'has_more_pages' => $paginated->hasMorePages(),
        ]);
    }

    /**
     * Update asset metadata.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $asset = ClubAsset::findOrFail($id);

        $validated = $request->validate([
            'title'     => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'club_id'   => 'nullable|exists:clubs,id',
        ]);

        $asset->update($validated);

        return response()->json([
            'message' => 'Asset updated successfully.',
            'asset'   => $asset->load('club:id,name'),
        ]);
    }

    /**
     * Delete an asset.
     */
    public function destroy(int $id): JsonResponse
    {
        $asset = ClubAsset::findOrFail($id);

        if ($asset->file_path && Storage::disk('public')->exists($asset->file_path)) {
            Storage::disk('public')->delete($asset->file_path);
        }

        $asset->delete();

        return response()->json([
            'message' => 'Asset deleted successfully.',
        ]);
    }

    /**
     * Mass delete assets.
     */
    public function massDestroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'indices' => 'required|array|min:1',
        ]);

        $assets = ClubAsset::whereIn('id', $validated['indices'])->get();

        foreach ($assets as $asset) {
            if ($asset->file_path && Storage::disk('public')->exists($asset->file_path)) {
                Storage::disk('public')->delete($asset->file_path);
            }
            $asset->delete();
        }

        return response()->json([
            'message' => count($assets) . ' assets deleted successfully.',
        ]);
    }

    /**
     * Format a Laravel Bus Batch object into a clean array for Vue UI.
     */
    protected function formatBatch($batch, ?string $clubName = null): array
    {
        $total = $batch->totalJobs;
        $pending = $batch->pendingJobs;
        $failed = $batch->failedJobs;
        $processed = $batch->processedJobs();
        $progress = $batch->progress();
        $finished = $batch->finished() || ($total > 0 && $pending === 0);
        $cancelled = $batch->cancelled();
        $workedJobs = $processed + $failed;

        $status = 'processing';
        if ($cancelled) {
            $status = 'cancelled';
        } elseif ($finished) {
            $status = $failed > 0 ? ($failed === $total ? 'failed' : 'partial_failure') : 'completed';
        } elseif ($workedJobs >= $total) {
            $status = $failed > 0 ? ($failed === $total ? 'failed' : 'partial_failure') : 'completed';
        }

        return [
            'id'             => $batch->id,
            'name'           => $batch->name,
            'club_name'      => $clubName,
            'total_jobs'     => $total,
            'pending_jobs'   => $pending,
            'processed_jobs' => $processed,
            'success_jobs'   => max(0, $processed - $failed),
            'failed_jobs'    => $failed,
            'worked_job'     => $workedJobs,
            'progress'       => $progress,
            'status'         => $status,
            'finished'       => $finished,
            'cancelled'      => $cancelled,
            'has_failures'   => $batch->hasFailures(),
            'created_at'     => $batch->createdAt?->format('M d, h:i A'),
            'finished_at'    => $batch->finishedAt?->format('M d, h:i A'),
        ];
    }
}
