<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class BranchDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('branches')
            ->join('clubs', 'branches.club_id', '=', 'clubs.id')
            ->select('branches.id', 'branches.name as branch_name', 'clubs.name as club_name', 'branches.address', 'branches.phone', 'branches.is_active');

        $this->addFilter('id', 'branches.id');
        $this->addFilter('branch_name', 'branches.name');
        $this->addFilter('club_name', 'clubs.name');
        $this->addFilter('phone', 'branches.phone');
        $this->addFilter('is_active', 'branches.is_active');

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
            'index' => 'branch_name',
            'label' => 'Branch Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'club_name',
            'label' => 'Club Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'phone',
            'label' => 'Phone',
            'type' => 'string',
            'searchable' => true,
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
            'title' => 'Edit Branch',
            'method' => 'edit',
            'url' => function ($row) {
                return '';
            }
        ]);

        $this->addAction([
            'icon' => 'icon-delete',
            'title' => 'Delete Branch',
            'method' => 'DELETE',
            'url' => function ($row) {
                return route('admin.clubs.delete_branch', $row->id);
            }
        ]);
    }
}
