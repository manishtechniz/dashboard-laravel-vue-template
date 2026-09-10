<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class ClubDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $query = DB::table('clubs')
            ->select('clubs.*', 'clubs.is_active as html_is_active');

        $this->addFilter('html_is_active', 'clubs.is_active');

        return $query;
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
            'index' => 'logo',
            'label' => 'Logo',
            'type' => 'string',
            'sortable' => false,
            'searchable' => false,
            'filterable' => false,
            'closure' => function ($row) {
                if ($row->logo) {
                    return '<img src="' . asset('storage/' . $row->logo) . '" class="w-10 h-10 object-cover rounded-full" alt="Logo">';
                }
                return '<div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500"><i class="pi pi-image"></i></div>';
            }
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
            'index' => 'file_path',
            'label' => 'Media',
            'type' => 'string',
            'sortable' => false,
            'searchable' => false,
            'filterable' => false,
            'closure' => function ($row) {
                if ($row->file_type == 'image') {
                    return '<a href="' . asset('storage/' . $row->file_path) . '" target="_blank" class="text-blue-500"><i class="pi pi-image"></i> View Image</a>';
                } elseif ($row->file_type == 'video') {
                    return '<a href="' . asset('storage/' . $row->file_path) . '" target="_blank" class="text-blue-500"><i class="pi pi-video"></i> View Video</a>';
                } elseif ($row->file_type == 'image_url') {
                    return '<a href="' . $row->file_path . '" target="_blank" class="text-blue-500"><i class="pi pi-link"></i> Image URL</a>';
                } elseif ($row->file_type == 'video_url') {
                    return '<a href="' . $row->file_path . '" target="_blank" class="text-blue-500"><i class="pi pi-link"></i> Video URL</a>';
                }
                return '-';
            }
        ]);

        $this->addColumn([
            'index' => 'html_is_active',
            'label' => 'Status',
            'type' => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return $row->is_active
                    ? '<span class="label-active">Active</span>'
                    : '<span class="label-inactive">Inactive</span>';
            },
        ]);
    }

    public function prepareActions()
    {
        if (hasPermission('admin.clubs.staff.index')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'd-pi pi pi-users',
                'title' => 'Manage Staff',
                'method' => 'staff',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }

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
    public function prepareMassActions()
    {
        if (hasPermission('admin.clubs.mass-delete')) {
            $this->addMassAction([
                'title' => 'Delete Clubs',
                'method' => 'POST',
                'url' => route('admin.clubs.mass_delete'),
                'confirm' => true,
            ]);
        }
        if (hasPermission('admin.clubs.mass-update')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'method' => 'POST',
                'url' => route('admin.clubs.mass_update'),
                'options' => [
                    ['label' => 'Active', 'value' => 1],
                    ['label' => 'Inactive', 'value' => 0],
                ],
            ]);
        }
    }
}
