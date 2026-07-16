<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\AuditLogDataGrid;

class AdminAuditLogController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(AuditLogDataGrid::class)->process();
        }

        return view('admin::audit-logs.index');
    }
}
