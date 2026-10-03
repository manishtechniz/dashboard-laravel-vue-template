<?php

namespace App\Http\Controllers\Admin;

use App\Model\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $activeGroup = request('active_group', null);

        $settings = Setting::all()->pluck('value', 'key')->toArray();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'data' => $settings,
            ]);
        }

        $configurations = config('configuration') ?? [];

        $configurations = array_filter($configurations, function ($config) {
            return !empty($config['is_active']);
        });

        // Collect all custom Blade paths that need to be loaded
        $customViews = [];
        foreach ($configurations as $group) {
            if (!empty($group['sections'])) {
                foreach ($group['sections'] as $section) {
                    if (!empty($section['fields'])) {
                        foreach ($section['fields'] as $field) {
                            if (($field['type'] ?? '') === 'blade' && isset($field['path'])) {
                                $customViews[] = $field['path'];
                            }
                        }
                    }
                }
            }
        }

        // dd($configurations);

        return view('admin::global-config.index', compact('settings', 'configurations', 'activeGroup', 'customViews'));
    }

    public function store(Request $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        if ($request->hasFile('settings')) {
            $files = $request->file('settings');
            foreach ($files as $configKey => $file) {
                if ($file) {
                    $value = getSystemConfig($configKey);

                    if (! empty($value) && Storage::exists($value)) {
                        Storage::delete($value);
                    }

                    $path = $file->store('settings/' . $configKey);

                    Setting::updateOrCreate(
                        ['key' => $configKey],
                        ['value' => $path]
                    );
                }
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Settings updated successfully.']);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
