<?php

namespace Imperial\DataGrid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Imperial\DataGrid\Models\SavedFilter;

class SavedFilterController extends Controller
{
    public $userId = 0;

    public function __construct(Request $request)
    {
       $this->userId = $request->attributes->get('user_id', 0);
    }

    /**
     * Save filters to the database.
     */
    public function store()
    {  
        Validator::make(request()->all(), [
            'name' => 'required|unique:datagrid_saved_filters,name,NULL,id,src,'.request('src').',user_id,'.$this->userId,
        ])->validate();

        $savedFilter = SavedFilter::create([
            'user_id' => $this->userId,
            'name' => request('name'),
            'src' => request('src'),
            'applied' => request('applied'),
        ]);

        return response()->json([
            'data' => $savedFilter,
            'message' => trans('Saved filter successfully!'),
        ]);
    }

    /**
     * Retrieves the saved filters.
     */
    public function get(Request $request)
    {  
        $savedFilters = SavedFilter::where([
            'src' => request('src'),
            'user_id' => $this->userId,
        ])->get();

        return response()->json(['data' => $savedFilters]);
    }

    /**
     * Update the saved filter.
     */
    public function update(int $id)
    {  
        Validator::make(request()->all(), [
            'name' => 'required|unique:datagrid_saved_filters,name,'.$id.',id,src,'.request('src').',user_id,'.$this->userId,
        ])->validate();

        $savedFilter = SavedFilter::where([
            'id' => $id,
            'user_id' => $this->userId,
        ])->first();

        if (! $savedFilter) {
            return response()->json([], 404);
        }

        $updatedFilter = SavedFilter::where('id', $id)
            ->update(request()->only([
                'name',
                'src',
                'applied',
            ]));

        return response()->json([
            'data' => $updatedFilter,
            'message' => 'Updated filter successfully!',
        ]);
    }

    /**
     * Delete the saved filter.
     */
    public function destroy(int $id)
    {
        $success = SavedFilter::where([
            'id' => $id,
            'user_id' => $this->userId,
        ])->delete();

        if (! $success) {
            return response()->json([
                'message' => 'Error during deletion. Please try again!',
            ]);
        }

        return response()->json([
            'message' => 'Deleted filter successfully!',
        ]);
    }
}

