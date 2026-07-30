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
        if (hasPermission('admin.clubs.update_club')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Club',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }

        if (hasPermission('admin.clubs.delete_club')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Club',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.clubs.delete_club', $row->id);
                }
            ]);
        }
    }
}
