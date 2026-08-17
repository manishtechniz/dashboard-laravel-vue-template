<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AllowMimeType;
use App\Enums\AssetType;
use App\Http\Controllers\Admin\DataGrids\BranchDataGrid;
use App\Http\Controllers\Admin\DataGrids\ClubDataGrid;
use App\Model\Branch;
use App\Model\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminClubController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            if (request()->has('list')) {
                return response()->json(Club::all());
            }
            if (request()->has('branches')) {
                return datagrid(BranchDataGrid::class)->process();
            }
            return datagrid(ClubDataGrid::class)->process();
        }

        $clubs = Club::all();
        return view('admin::clubs.index', compact('clubs'));
    }

    public function storeClub(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:256',
            'phone' => 'nullable|string|max:255',
            'whatsapp_no' => 'nullable|string|max:255',
            'primary_business_whatsapp' => 'nullable|string|max:255',
            'disclaimer' => 'nullable|string|max:2000',
            'opening_time' => 'required|date_format:H:i:s',
            'close_time'   => 'required|date_format:H:i:s',
            'is_active' => 'boolean',
            'logo' => 'required|image|mimes:' . implode(',', AllowMimeType::imageValues()) . '|max:2048',
            'file_type' => 'required|in:' . implode(',', AssetType::values()),
        ];

        if (in_array($request->file_type, ['image', 'video'])) {
            $rules['file_path'] = 'required|file|mimes:' . implode(',', AllowMimeType::values()) . '|max:4096';
        } elseif (in_array($request->file_type, ['image_url', 'video_url'])) {
            $rules['file_path'] = 'required|url';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clubs/logos', 'public');
        }

        if (in_array($request->file_type, ['image', 'video']) && $request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('clubs/files', 'public');
        } else if (in_array($request->file_type, ['image_url', 'video_url'])) {
            $validated['file_path'] = $request->input('file_path');
        }

        if ($request->has('file_type')) {
            $validated['file_type'] = $request->input('file_type');
        }

        Club::create($validated);

        return response()->json([
            'message' => 'Club created successfully.',
            'clubs' => Club::all()
        ]);
    }

    public function updateClub(Request $request, $id)
    {
        // dd($request->opening_time);
        $club = Club::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'disclaimer' => 'nullable|string|max:2000',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'whatsapp_no' => 'nullable|string|max:255',
            'primary_business_whatsapp' => 'nullable|string|max:255',
            'opening_time' => 'required|date_format:H:i:s',
            'close_time'   => 'required|date_format:H:i:s',
            'is_active' => 'boolean',
            'logo' => 'nullable|mimes:' . implode(',', AllowMimeType::imageValues()) . '|max:2048',
            'file_type' => 'nullable|in:' . implode(',', AssetType::values()),
        ];

        if (in_array($request->file_type, ['image', 'video'])) {
            $rules['file_path'] = 'required_if:file_type,image,video|file|mimes:' . implode(',', AllowMimeType::values()) . '|max:4096';
        } elseif (in_array($request->file_type, ['image_url', 'video_url'])) {
            $rules['file_path'] = 'required_if:file_type,image_url,video_url|url';
        }

        $validated = $request->validate($rules);

        if (! empty($request->logo)) {
            ! empty($club->logo) ? Storage::delete($club->logo) : '';

            $validated['logo'] = $request->file('logo')->store('clubs/logos');
        } else {
            unset($validated['logo']);
        }

        if (in_array($request->file_type, ['image', 'video']) && $request->hasFile('file_path')) {
            ! empty($club->file_path) ? Storage::delete($club->file_path) : '';

            $validated['file_path'] = $request->file('file_path')->store('clubs/files', 'public');
        } else if (in_array($request->file_type, ['image_url', 'video_url'])) {
            $validated['file_path'] = $request->input('file_path');
        } else {
            unset($validated['file_path'], $validated['file_type']);
        }

        $club->update($validated);

        return response()->json([
            'message' => 'Club updated successfully.',
            'clubs' => Club::all()
        ]);
    }

    public function destroyClub($id)
    {
        $club = Club::findOrFail($id);
        $club->delete();

        return response()->json([
            'message' => 'Club deleted successfully.',
            'clubs' => Club::all()
        ]);
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        Club::whereIn('id', $validated['indices'])->delete();

        return response()->json(['message' => 'Clubs deleted successfully.']);
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        Club::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);

        return response()->json(['message' => 'Clubs status updated successfully.']);
    }

    // Branch Operations
    public function storeBranch(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Branch::create($validated);

        return response()->json(['message' => 'Branch created successfully.']);
    }

    public function updateBranch(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return response()->json(['message' => 'Branch updated successfully.']);
    }

    public function destroyBranch($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->json(['message' => 'Branch deleted successfully.']);
    }

    public function massDestroyBranch(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        Branch::whereIn('id', $validated['indices'])->delete();

        return response()->json(['message' => 'Branches deleted successfully.']);
    }

    public function massUpdateBranch(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        Branch::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);

        return response()->json(['message' => 'Branches status updated successfully.']);
    }
}
