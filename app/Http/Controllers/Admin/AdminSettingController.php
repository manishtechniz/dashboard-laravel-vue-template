<?php

namespace App\Http\Controllers\Admin;

use App\Model\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'data' => $settings,
            ]);
        }

        // dd(1);

        return view('admin::global-config.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $inputSettings = $request->except('_token');

        foreach ($inputSettings as $key => $value) {
            $valueToSave = is_array($value) || is_object($value)
                ? json_encode($value)
                : (string)$value;

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $valueToSave]
            );
        }

        $allSettings = Setting::all()->pluck('value', 'key')->toArray();

        return response()->json([
            'message' => 'Settings updated successfully.',
            'data'    => $allSettings,
        ]);
    }
}
