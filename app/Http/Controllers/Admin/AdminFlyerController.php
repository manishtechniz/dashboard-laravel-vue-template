<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\FlyerDataGrid;
use App\Model\Flyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFlyerController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return app(FlyerDataGrid::class)->toJson();
        }

        return view('admin.flyers.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file_type' => 'required|in:image,video',
            'file' => 'required|file|max:102400', // max 100MB
            'audio' => 'nullable|file|mimes:audio/mpeg,mpga,mp3,wav|max:20480', // audio optional for images
            'is_active' => 'boolean'
        ]);

        $flyer = new Flyer();
        $flyer->title = $request->title;
        $flyer->description = $request->description;
        $flyer->file_type = $request->file_type;
        $flyer->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('file')) {
            $flyer->file_path = $request->file('file')->store('flyers/files', 'public');
        }

        if ($request->file_type === 'image' && $request->hasFile('audio')) {
            $flyer->audio_path = $request->file('audio')->store('flyers/audios', 'public');
        }

        $flyer->save();

        return response()->json([
            'message' => 'Flyer created successfully.',
            'flyer' => $flyer
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file_type' => 'required|in:image,video',
            'file' => 'nullable|file|max:102400',
            'audio' => 'nullable|file|mimes:audio/mpeg,mpga,mp3,wav|max:20480',
            'is_active' => 'boolean'
        ]);

        $flyer = Flyer::findOrFail($id);
        $flyer->title = $request->title;
        $flyer->description = $request->description;
        $flyer->file_type = $request->file_type;
        $flyer->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('file')) {
            if ($flyer->file_path) {
                Storage::disk('public')->delete($flyer->file_path);
            }
            $flyer->file_path = $request->file('file')->store('flyers/files', 'public');
        }

        if ($request->file_type === 'image') {
            if ($request->hasFile('audio')) {
                if ($flyer->audio_path) {
                    Storage::disk('public')->delete($flyer->audio_path);
                }
                $flyer->audio_path = $request->file('audio')->store('flyers/audios', 'public');
            }
        } else {
            // If switched to video, delete audio if exists
            if ($flyer->audio_path) {
                Storage::disk('public')->delete($flyer->audio_path);
                $flyer->audio_path = null;
            }
        }

        $flyer->save();

        return response()->json([
            'message' => 'Flyer updated successfully.',
            'flyer' => $flyer
        ]);
    }

    public function massUpdate(Request $request)
    {
        $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        Flyer::whereIn('id', $request->indices)->update([
            'is_active' => $request->value,
        ]);

        return response()->json([
            'message' => 'Flyers updated successfully.',
        ]);
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'indices' => 'required|array',
        ]);

        $flyers = Flyer::whereIn('id', $request->indices)->get();
        foreach ($flyers as $flyer) {
            if ($flyer->file_path) {
                Storage::disk('public')->delete($flyer->file_path);
            }
            if ($flyer->audio_path) {
                Storage::disk('public')->delete($flyer->audio_path);
            }
            $flyer->delete();
        }

        return response()->json([
            'message' => 'Flyers deleted successfully.',
        ]);
    }
}
