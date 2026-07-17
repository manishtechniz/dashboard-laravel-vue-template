<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class BranchDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('branches')
            ->join('clubs', 'branches.club_id', '=', 'clubs.id')
            ->select('branches.id', 'branches.name as branch_name', 'clubs.name as club_name', 'branches.address', 'branches.phone', 'branches.is_active');
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
            'type' => 'custom',
            'icon' => 'icon-delete',
            'title' => 'Delete Branch',
            'method' => 'delete',
            'url' => function ($row) {
                return '';
            }
        ]);
    }
}
