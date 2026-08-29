<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\PromoCodeDataGrid;
use App\Model\PromoCode;
use App\Model\Event;
use Illuminate\Http\Request;

class AdminPromoCodeController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(PromoCodeDataGrid::class)->process();
        }

        $events = Event::select('id', 'name')->orderBy('name')->get();

        return view('admin::promo-codes.index', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:promo_codes,code|max:256',
            'type' => 'required|string|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'visibility' => 'required|string|in:public,private',
            'event_id' => 'nullable|integer|exists:events,id|unique:promo_codes,event_id',
            'label' => 'nullable|string|max:200',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            PromoCode::create($validated);
            return response()->json(['message' => 'Promo code created successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during create.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $promoCode = PromoCode::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:promo_codes,code,' . $promoCode->id . '|max:2000',
            'type' => 'required|string|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'visibility' => 'required|string|in:public,private',
            'event_id' => 'nullable|integer|exists:events,id|unique:promo_codes,event_id,' . $promoCode->id,
            'label' => 'required|string|max:200',
            'description' => 'required|string|max:500',
        ]);

        try {
            $promoCode->update($validated);
            return response()->json(['message' => 'Promo code updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $promoCode = PromoCode::findOrFail($id);
            $promoCode->delete();
            return response()->json(['message' => 'Promo code deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during delete.'], 500);
        }
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        try {
            PromoCode::whereIn('id', $validated['indices'])->delete();
            return response()->json(['message' => 'Promo codes deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass delete.'], 500);
        }
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        try {
            PromoCode::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);
            return response()->json(['message' => 'Promo codes status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }
}
