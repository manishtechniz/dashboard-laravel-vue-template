<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class FloorDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('floors')
            ->join('branches', 'floors.branch_id', '=', 'branches.id')
            ->select('floors.id', 'floors.name as floor_name', 'branches.name as branch_name', 'floors.level', 'floors.is_active');
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
            'index' => 'floor_name',
            'label' => 'Floor Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'branch_name',
            'label' => 'Branch Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'level',
            'label' => 'Level',
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'is_active',
            'label' => 'Status',
            'type' => 'boolean',
            'filterable' => true,
            'closure' => function ($row) {
                return $row->is_active
                    ? '<span class="label-active">Active</span>'
                    : '<span class="label-inactive">Inactive</span>';
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon' => 'icon-edit',
            'title' => 'Edit Floor',
            'method' => 'GET',
            'url' => function ($row) {
                return route('admin.floors.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon' => 'icon-delete',
            'title' => 'Delete Floor',
            'method' => 'DELETE',
            'url' => function ($row) {
                return route('admin.floors.delete', $row->id);
            },
        ]);
    }
}
