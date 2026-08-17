<?php

use Illuminate\Support\Facades\Route;
use Imperial\DataGrid\Http\Controllers\DataGridController;
use Imperial\DataGrid\Http\Controllers\SavedFilterController;

/**
 * Datagrid routes.
 */
Route::controller(DataGridController::class)
    ->middleware(config('datagrid.middlewares'))
    ->prefix(config('datagrid.route_prefix'))->group(function () {
        Route::get('look-up', 'lookUp')->name(config('datagrid.route_prefix').'.look_up');

        Route::controller(SavedFilterController::class)->prefix('saved-filters')->group(function () {
            Route::post('', 'store')->name(config('datagrid.route_prefix').'.saved_filters.store');

            Route::get('', 'get')->name(config('datagrid.route_prefix').'.saved_filters.index');

            Route::put('{id}', 'update')->name(config('datagrid.route_prefix').'.saved_filters.update');

            Route::delete('{id}', 'destroy')->name(config('datagrid.route_prefix').'.saved_filters.destroy');
        });
});
    