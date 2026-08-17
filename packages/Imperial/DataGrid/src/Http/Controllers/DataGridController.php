<?php

namespace Imperial\DataGrid\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class DataGridController extends Controller
{
    /**
     * Look up.
     */
    public function lookUp()
    {
        /**
         * Validation for parameters.
         */
        $params = Validator::make(request()->all(), [
            'datagrid_id' => ['required'],
            'column' => ['required'],
            'search' => ['required', 'min:2'],
        ])->validate();

        /**
         * Preparing the datagrid instance and only columns.
         */
        $datagrid = app(Crypt::decryptString($params['datagrid_id']));
        $datagrid->prepareColumns();

        /**
         * Finding the first column from the collection.
         */
        $column = collect($datagrid->getColumns())->where('index', $params['column'])->firstOrFail();

        /**
         * Fetching on the basis of column options.
         */
        return app($column->options['params']['repository'])
            ->select([$column->options['params']['column']['label'].' as label', $column->options['params']['column']['value'].' as value'])
            ->where($column->options['params']['column']['label'], 'LIKE', '%'.$params['search'].'%')
            ->get();
    }
}
