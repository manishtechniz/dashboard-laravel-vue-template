<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class ClubDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('clubs')
            ->select('id', 'name', 'address', 'city', 'is_active');
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
            'index' => 'name',
            'label' => 'Club Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'address',
            'label' => 'Address',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'city',
            'label' => 'City',
            'type' => 'string',
            'searchable' => true,
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
            'title' => 'Edit Club',
            'method' => 'edit',
            'url' => function ($row) {
                return '';
            }
        ]);

        $this->addAction([
            'type' => 'custom',
            'icon' => 'icon-delete',
            'title' => 'Delete Club',
            'method' => 'delete',
            'url' => function ($row) {
                return '';
            }
        ]);
    }
}
