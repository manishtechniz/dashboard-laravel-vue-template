<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class FloorDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('floors')
            ->join('branches', 'floors.branch_id', '=', 'branches.id')
            ->select('floors.id', 'floors.name as floor_name', 'branches.name as branch_name', 'floors.level', 'floors.is_active');

        $this->addFilter('id', 'floors.id');
        $this->addFilter('floor_name', 'floors.name');
        $this->addFilter('branch_name', 'branches.name');
        $this->addFilter('level', 'floors.level');
        $this->addFilter('is_active', 'floors.is_active');

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
            'type' => 'custom',
            'icon' => 'icon-edit',
            'title' => 'Edit Floor',
            'method' => 'edit',
            'url' => function ($row) {
                return '';
            }
        ]);

        $this->addAction([
            'icon' => 'icon-delete',
            'title' => 'Delete Floor',
            'method' => 'DELETE',
            'url' => function ($row) {
                return route('admin.floors.delete', $row->id);
            }
        ]);
    }
    public function prepareMassActions()
    {
        if (hasPermission('admin.floors.mass-delete')) {
            $this->addMassAction([
                'title' => 'Delete Floors',
                'method' => 'POST',
                'url' => route('admin.floors.mass_delete'),
                'confirm' => true,
            ]);
        }
        if (hasPermission('admin.floors.mass-update')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'method' => 'POST',
                'url' => route('admin.floors.mass_update'),
                'options' => [
                    ['label' => 'Active', 'value' => 1],
                    ['label' => 'Inactive', 'value' => 0],
                ],
            ]);
        }
    }
}
