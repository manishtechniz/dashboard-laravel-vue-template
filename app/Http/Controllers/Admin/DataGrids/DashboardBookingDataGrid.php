<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class DashboardBookingDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'id';

    /**
     * Format currency amount into compact Indian Rupee format.
     *
     * @param  mixed  $amount
     * @return string
     */
    protected function formatRupee($amount): string
    {
        $num = abs((float) $amount);
        $sign = ((float) $amount < 0) ? '-' : '';

        if ($num >= 1000000000) {
            $b = round($num / 1000000000, 1);
            return "{$sign}₹{$b}B";
        }

        if ($num >= 1000000) {
            $m = round($num / 1000000, 1);
            return "{$sign}₹{$m}M";
        }

        if ($num >= 1000) {
            $k = round($num / 1000, 1);
            return "{$sign}₹{$k}K";
        }

        return "{$sign}₹" . number_format($num, 2);
    }

    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('bookings')
            ->leftJoin('clients', 'bookings.client_id', '=', 'clients.id')
            ->leftJoin('tables', 'bookings.table_id', '=', 'tables.id')
            ->leftJoin('events', 'bookings.event_id', '=', 'events.id')
            ->leftJoin('clubs', 'bookings.club_id', '=', 'clubs.id')
            ->select(
                'bookings.id',
                DB::raw("COALESCE(clients.name, bookings.client_name, 'Guest') as client_name"),
                DB::raw("COALESCE(clients.email, bookings.client_email, '-') as client_email"),
                DB::raw("COALESCE(clients.phone, bookings.client_phone, '-') as client_phone"),
                DB::raw("COALESCE(clubs.name, bookings.club_name, 'General') as club_name"),
                DB::raw("COALESCE(tables.name, '-') as table_name"),
                DB::raw("COALESCE(events.name, '-') as event_name"),
                'bookings.booking_date',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.guest_count',
                'bookings.base_price',
                'bookings.discount_amount',
                'bookings.tax_amount',
                'bookings.total_amount_incl_tax',
                'bookings.payment_status',
                'bookings.payment_method',
                'bookings.status',
                'bookings.status as status_html',
                'bookings.special_requests',
                'bookings.qr_code',
                'bookings.created_at'
            );

        $this->addFilter('id', 'bookings.id');
        $this->addFilter('client_name', 'clients.name');
        $this->addFilter('club_name', 'clubs.name');
        $this->addFilter('table_name', 'tables.name');
        $this->addFilter('event_name', 'events.name');
        $this->addFilter('booking_date', 'bookings.booking_date');
        $this->addFilter('payment_status', 'bookings.payment_status');
        $this->addFilter('status', 'bookings.status');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'ID',
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => false,
            'closure'    => function ($row) {
                return '<span class="font-mono text-xs font-semibold" style="color: var(--text-muted);">#' . (int) $row->id . '</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'client_name',
            'label'      => 'Client',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => false,
            'closure'    => function ($row) {
                $email = htmlspecialchars($row->client_email ?? '', ENT_QUOTES, 'UTF-8');
                $name = htmlspecialchars($row->client_name ?? 'Guest', ENT_QUOTES, 'UTF-8');

                return '<div class="flex flex-col">' .
                    '<span class="font-bold text-sm" style="color: var(--text-base);">' . $name . '</span>' .
                    '<span class="text-xs" style="color: var(--text-muted);">' . $email . '</span>' .
                    '</div>';
            },
        ]);

        $this->addColumn([
            'index'      => 'club_name',
            'label'      => 'Club / Venue',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => false,
            'closure'    => function ($row) {
                $club = htmlspecialchars($row->club_name ?? 'General', ENT_QUOTES, 'UTF-8');
                return '<span class="font-medium text-sm" style="color: var(--text-base);">' . $club . '</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'table_name',
            'label'      => 'Table / Event',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if ($row->table_name !== '-') {
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold" style="background: var(--accent-light); color: var(--accent); border: 1px solid var(--border);">' . htmlspecialchars($row->table_name, ENT_QUOTES, 'UTF-8') . '</span>';
                }
                if ($row->event_name !== '-') {
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold" style="background: color-mix(in srgb, var(--info) 12%, transparent); color: var(--info); border: 1px solid color-mix(in srgb, var(--info) 25%, transparent);">' . htmlspecialchars($row->event_name, ENT_QUOTES, 'UTF-8') . '</span>';
                }
                return '<span style="color: var(--text-muted);">-</span>';
            },
        ]);

        $this->addColumn([
            'index'           => 'booking_date',
            'label'           => 'Date & Time',
            'type'            => 'date',
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => false,
            'closure'         => function ($row) {
                $dateStr = $row->booking_date ? date('M d, Y', strtotime($row->booking_date)) : '-';
                $timeStr = $row->start_time ? date('h:i A', strtotime($row->start_time)) : '';

                return '<div class="flex flex-col">' .
                    '<span class="text-sm font-medium" style="color: var(--text-base);">' . $dateStr . '</span>' .
                    ($timeStr ? '<span class="text-xs" style="color: var(--text-muted);">' . $timeStr . '</span>' : '') .
                    '</div>';
            },
        ]);

        $this->addColumn([
            'index'      => 'guest_count',
            'label'      => 'Guests',
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => false,
            'closure'    => function ($row) {
                return '<span class="inline-flex items-center gap-1.5 font-semibold text-sm" style="color: var(--text-base);">' .
                    '<i class="pi pi-users text-xs" style="color: var(--text-muted);"></i> ' .
                    (int) $row->guest_count .
                    '</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'total_amount_incl_tax',
            'label'      => 'Amount',
            'type'       => 'decimal',
            'filterable' => true,
            'sortable'   => false,
            'closure'    => function ($row) {
                $amt = (float) ($row->total_amount_incl_tax ?? 0);
                return '<span class="font-extrabold text-sm" style="color: var(--text-base);">' . $this->formatRupee($amt) . '</span>';
            },
        ]);

        $this->addColumn([
            'index'              => 'payment_status',
            'label'              => 'Payment',
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                ['label' => 'Paid', 'value' => 'paid'],
                ['label' => 'Pending', 'value' => 'pending'],
                ['label' => 'Failed', 'value' => 'failed'],
                ['label' => 'Refunded', 'value' => 'refunded'],
            ],
            'closure'            => function ($row) {
                $status = strtolower($row->payment_status ?? 'pending');
                switch ($status) {
                    case 'paid':
                    case 'completed':
                        return '<span class="badge badge-success">Paid</span>';
                    case 'pending':
                        return '<span class="badge badge-warning">Pending</span>';
                    case 'failed':
                        return '<span class="badge badge-danger">Failed</span>';
                    case 'refunded':
                        return '<span class="px-2.5 py-0.5 rounded-full text-xs font-bold" style="background: var(--bg-subtle); color: var(--text-muted); border: 1px solid var(--border);">Refunded</span>';
                    default:
                        return '<span class="px-2.5 py-0.5 rounded-full text-xs font-bold" style="background: var(--bg-subtle); color: var(--text-muted); border: 1px solid var(--border);">' . htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') . '</span>';
                }
            },
        ]);

        $this->addColumn([
            'index'              => 'status_html',
            'label'              => 'Status',
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                ['label' => 'Pending', 'value' => 'pending'],
                ['label' => 'Confirmed', 'value' => 'confirmed'],
                ['label' => 'Checked In', 'value' => 'checked_in'],
                ['label' => 'Cancelled', 'value' => 'cancelled'],
            ],
            'closure'            => function ($row) {
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

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions() {}
}
