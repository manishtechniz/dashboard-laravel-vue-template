<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Imperial\DataGrid\DataGrid;

class FlyerDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('flyers')
            ->select('id', 'title', 'description', 'file_type', 'file_path', 'audio_path', 'is_active', 'created_at');

        $this->addFilter('id', 'id');
        $this->addFilter('title', 'title');
        $this->addFilter('file_type', 'file_type');
        $this->addFilter('is_active', 'is_active');
        $this->addFilter('created_at', 'created_at');

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
            'index' => 'file_path',
            'label' => 'Preview',
            'type' => 'string',
            'closure' => function ($row) {
                $fileUrl = $row->file_path ? Storage::url($row->file_path) : '';
                if ($row->file_type === 'image') {
                    return '<a href="' . $fileUrl . '" target="_blank"><img src="' . $fileUrl . '" alt="' . $row->title . '" class="w-12 h-12 rounded object-cover" /></a>';
                } elseif ($row->file_type === 'video') {
                    return '<video src="' . $fileUrl . '" controls style="width: 120px; max-height: 80px;" class="rounded bg-black"></video>';
                }
                return '-';
            },
        ]);

        $this->addColumn([
            'index' => 'title',
            'label' => 'Title',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'file_type',
            'label' => 'Type',
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return ucfirst($row->file_type);
            },
        ]);

        $this->addColumn([
            'index' => 'audio_path',
            'label' => 'Audio',
            'type' => 'boolean',
            'closure' => function ($row) {
                if ($row->audio_path) {
                    $audioUrl = Storage::url($row->audio_path);
                    return '<audio src="' . $audioUrl . '" controls style="height: 35px; width: 109px; outline: none;"></audio>';
                }

                return '<span class="text-gray-400">-</span>';
            },
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

        $this->addColumn([
            'index' => 'created_at',
            'label' => 'Created At',
            'type' => 'date',
            'sortable' => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        if (hasPermission('admin.flyers.update')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Flyer',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }

        if (hasPermission('admin.flyers.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Flyer',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.flyers.delete', $row->id);
                }
            ]);
        }
    }

    public function prepareMassActions()
    {
        if (hasPermission('admin.flyers.mass_delete')) {
            $this->addMassAction([
                'title' => 'Delete Flyers',
                'method' => 'POST',
                'url' => route('admin.flyers.mass_delete'),
                'confirm' => true,
            ]);
        }

        if (hasPermission('admin.flyers.mass_update')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'method' => 'POST',
                'url' => route('admin.flyers.mass_update'),
                'options' => [
                    ['label' => 'Active', 'value' => 1],
                    ['label' => 'Inactive', 'value' => 0],
                ],
            ]);
        }
    }
}
