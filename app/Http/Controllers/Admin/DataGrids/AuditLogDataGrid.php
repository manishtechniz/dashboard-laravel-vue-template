<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class AuditLogDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('audit_logs')
            ->select('id', 'user_id', 'action', 'model_type', 'model_id', 'ip_address', 'created_at');
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => 'ID',
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'user_id',
            'label' => 'User ID',
            'type' => 'integer',
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'action',
            'label' => 'Action',
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => 'CREATE', 'value' => 'CREATE'],
                ['label' => 'UPDATE', 'value' => 'UPDATE'],
                ['label' => 'DELETE', 'value' => 'DELETE'],
            ],
            'closure' => function ($row) {
                switch ($row->action) {
                    case 'CREATE':
                        return '<span class="label-active">CREATE</span>';
                    case 'UPDATE':
                        return '<span class="label-processing">UPDATE</span>';
                    case 'DELETE':
                        return '<span class="label-inactive">DELETE</span>';
                    default:
                        return $row->action;
                }
            },
        ]);

        $this->addColumn([
            'index' => 'model_type',
            'label' => 'Model',
            'type' => 'string',
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'model_id',
            'label' => 'Model ID',
            'type' => 'integer',
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'ip_address',
            'label' => 'IP Address',
            'type' => 'string',
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => 'Timestamp',
            'type' => 'datetime',
            'filterable' => true,
            'filterable_type' => 'date_range',
            'sortable' => true,
        ]);
    }

    public function prepareActions()
    {
        // Audit logs are read-only
    }
}
