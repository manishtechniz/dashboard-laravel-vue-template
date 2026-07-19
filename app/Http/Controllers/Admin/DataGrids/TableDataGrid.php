<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class TableDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('tables')
            ->join('floors', 'tables.floor_id', '=', 'floors.id')
            ->select('tables.id', 'tables.name as table_name', 'floors.name as floor_name', 'tables.capacity', 'tables.status');

        $this->addFilter('id', 'tables.id');
        $this->addFilter('table_name', 'tables.name');
        $this->addFilter('floor_name', 'floors.name');
        $this->addFilter('capacity', 'tables.capacity');
        $this->addFilter('status', 'tables.status');

        return $queryBuilder;
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
            'index' => 'table_name',
            'label' => 'Table Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'floor_name',
            'label' => 'Floor',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'capacity',
            'label' => 'Capacity (Seats)',
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => 'Status',
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => 'Available', 'value' => 'available'],
                ['label' => 'Reserved', 'value' => 'reserved'],
                ['label' => 'Occupied', 'value' => 'occupied'],
                ['label' => 'Maintenance', 'value' => 'maintenance'],
            ],
            'closure' => function ($row) {
                switch ($row->status) {
                    case 'available':
                        return '<span class="label-active">Available</span>';
                    case 'reserved':
                        return '<span class="label-pending">Reserved</span>';
                    case 'occupied':
                        return '<span class="label-processing">Occupied</span>';
                    case 'maintenance':
                        return '<span class="label-inactive">Maintenance</span>';
                    default:
                        return $row->status;
                }
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'custom',
            'icon' => 'icon-edit',
            'title' => 'Edit Table',
            'method' => 'edit',
            'url' => function ($row) {
                return '';
            }
        ]);

        $this->addAction([
            'icon' => 'icon-delete',
            'title' => 'Delete Table',
            'method' => 'DELETE',
            'url' => function ($row) {
                return route('admin.tables.delete', $row->id);
            }
        ]);
    }
}
