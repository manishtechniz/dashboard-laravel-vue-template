<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class BookingDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('bookings')
            ->join('clients', 'bookings.client_id', '=', 'clients.id')
            ->leftJoin('tables', 'bookings.table_id', '=', 'tables.id')
            ->leftJoin('events', 'bookings.event_id', '=', 'events.id')
            ->leftJoin('clubs', 'bookings.club_id', '=', 'clubs.id')
            ->select(
                'bookings.id',
                'clients.name as client_name',
                'tables.name as table_name',
                'events.name as event_name',
                'clubs.name as club_name',
                'bookings.client_name as guest_name',
                'bookings.client_phone',
                'bookings.client_email',
                'bookings.base_price',
                'bookings.spend_amount',
                'bookings.discount_type',
                'bookings.discount_amount',
                'bookings.tax_amount',
                'bookings.total_amount_incl_tax',
                'bookings.payment_status',
                'bookings.booking_date',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.guest_count',
                'bookings.status',
                'bookings.status as status_html',
                'bookings.special_requests',
                'bookings.qr_code',
                'bookings.created_at'
            );

        $this->addFilter('id', 'bookings.id');
        $this->addFilter('client_name', 'clients.name');
        $this->addFilter('table_name', 'tables.name');
        $this->addFilter('event_name', 'events.name');
        $this->addFilter('booking_date', 'bookings.booking_date');
        $this->addFilter('guest_count', 'bookings.guest_count');
        $this->addFilter('status', 'bookings.status');

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
            'index' => 'status_html',
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
                $status = strtolower($row->status ?? 'pending');

                switch ($status) {
                    case 'confirmed':
                        return '<span class="px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 w-max" style="background: color-mix(in srgb, var(--success) 12%, transparent); color: var(--success); border: 1px solid color-mix(in srgb, var(--success) 25%, transparent);"><span class="w-1.5 h-1.5 rounded-full" style="background: var(--success);"></span>Confirmed</span>';
                    case 'checked_in':
                        return '<span class="px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 w-max" style="background: color-mix(in srgb, var(--info) 12%, transparent); color: var(--info); border: 1px solid color-mix(in srgb, var(--info) 25%, transparent);"><span class="w-1.5 h-1.5 rounded-full" style="background: var(--info);"></span>Checked In</span>';
                    case 'pending':
                        return '<span class="px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 w-max" style="background: color-mix(in srgb, var(--warning) 12%, transparent); color: var(--warning); border: 1px solid color-mix(in srgb, var(--warning) 25%, transparent);"><span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: var(--warning);"></span>Pending</span>';
                    case 'cancelled':
                        return '<span class="px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 w-max" style="background: color-mix(in srgb, var(--danger) 12%, transparent); color: var(--danger); border: 1px solid color-mix(in srgb, var(--danger) 25%, transparent);"><span class="w-1.5 h-1.5 rounded-full" style="background: var(--danger);"></span>Cancelled</span>';
                    default:
                        return '<span class="px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 w-max" style="background: var(--bg-subtle); color: var(--text-muted); border: 1px solid var(--border);">' . htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') . '</span>';
                }
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'custom',
            'icon' => 'd-pi pi pi-users ',
            'title' => 'View Guests',
            'method' => 'guests',
            'url' => function ($row) {
                return '';
            }
        ]);

        if (hasPermission('admin.bookings.view')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-view',
                'title' => 'View Booking',
                'method' => 'view',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }

        if (hasPermission('admin.bookings.update')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Booking',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }

        if (hasPermission('admin.bookings.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Booking',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.bookings.delete', $row->id);
                }
            ]);
        }
    }

    public function prepareMassActions()
    {
        if (hasPermission('admin.bookings.mass-delete')) {
            $this->addMassAction([
                'title' => 'Delete Bookings',
                'method' => 'POST',
                'url' => route('admin.bookings.mass_delete'),
                'confirm' => true,
            ]);
        }

        if (hasPermission('admin.bookings.mass_status')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'url' => route('admin.bookings.mass_status'),
                'method' => 'POST',
                'options' => [
                    ['label' => 'Pending', 'value' => 'pending'],
                    ['label' => 'Confirmed', 'value' => 'confirmed'],
                    ['label' => 'Cancelled', 'value' => 'cancelled'],
                    ['label' => 'Checked In', 'value' => 'checked_in'],
                ],
            ]);
        }
    }
}
