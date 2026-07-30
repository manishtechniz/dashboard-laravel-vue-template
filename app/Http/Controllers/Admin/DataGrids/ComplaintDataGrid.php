<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class ComplaintDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('complaints')
            ->join('clients', 'complaints.client_id', '=', 'clients.id')
            ->leftJoin('clubs', 'complaints.club_id', '=', 'clubs.id')
            ->leftJoin('bookings', 'complaints.booking_id', '=', 'bookings.id')
            ->select(
                'complaints.id',
                'complaints.client_id',
                'complaints.club_id',
                'complaints.booking_id',
                'clients.name as client_name',
                'clubs.name as club_name',
                'complaints.message',
                'complaints.created_at',
                'complaints.is_active',
                'complaints.remark'
            );

        $this->addFilter('id', 'complaints.id');
        $this->addFilter('client_name', 'clients.name');
        $this->addFilter('club_name', 'clubs.name');
        $this->addFilter('message', 'complaints.message');
        $this->addFilter('is_active', 'complaints.is_active');

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
            'index' => 'client_name',
            'label' => 'Client',
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
            'index' => 'message',
            'label' => 'Message',
            'type' => 'string',
            'searchable' => true,
        ]);

        $this->addColumn([
            'index' => 'remark',
            'label' => 'Remark',
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
        if (hasPermission('admin.complaints.update')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Complaint',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }
        if (hasPermission('admin.complaints.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Complaint',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.complaints.delete', $row->id);
                },
            ]);
        }
    }
}
