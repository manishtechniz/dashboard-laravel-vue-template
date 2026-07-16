<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class BookingDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('bookings')
            ->join('clients', 'bookings.client_id', '=', 'clients.id')
            ->leftJoin('tables', 'bookings.table_id', '=', 'tables.id')
            ->leftJoin('events', 'bookings.event_id', '=', 'events.id')
            ->select(
                'bookings.id',
                'clients.name as client_name',
                'tables.name as table_name',
                'events.name as event_name',
                'bookings.booking_date',
                'bookings.start_time',
                'bookings.guest_count',
                'bookings.status'
            );
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
            'index' => 'table_name',
            'label' => 'Table',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'event_name',
            'label' => 'Event',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'booking_date',
            'label' => 'Date',
            'type' => 'date',
            'filterable' => true,
            'filterable_type' => 'date_range',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'guest_count',
            'label' => 'Guests',
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
                ['label' => 'Pending', 'value' => 'pending'],
                ['label' => 'Confirmed', 'value' => 'confirmed'],
                ['label' => 'Cancelled', 'value' => 'cancelled'],
                ['label' => 'Checked In', 'value' => 'checked_in'],
            ],
            'closure' => function ($row) {
                switch ($row->status) {
                    case 'pending':
                        return '<span class="label-pending">Pending</span>';
                    case 'confirmed':
                        return '<span class="label-active">Confirmed</span>';
                    case 'cancelled':
                        return '<span class="label-inactive">Cancelled</span>';
                    case 'checked_in':
                        return '<span class="label-processing">Checked In</span>';
                    default:
                        return $row->status;
                }
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon' => 'icon-edit',
            'title' => 'Edit Booking',
            'method' => 'GET',
            'url' => function ($row) {
                return route('admin.bookings.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon' => 'icon-delete',
            'title' => 'Delete Booking',
            'method' => 'DELETE',
            'url' => function ($row) {
                return route('admin.bookings.delete', $row->id);
            },
        ]);
    }
}
