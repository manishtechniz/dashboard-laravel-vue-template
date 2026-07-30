<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class EventDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('events')
            ->join('clubs', 'events.club_id', '=', 'clubs.id')
            ->leftJoin('promo_codes', 'events.id', '=', 'promo_codes.event_id')
            ->select('events.*', 'events.name as event_name', 'clubs.name as club_name', 'promo_codes.code as coupon_code', 'promo_codes.label as coupon_name');

        $this->addFilter('id', 'events.id');
        $this->addFilter('event_name', 'events.name');
        $this->addFilter('club_name', 'clubs.name');
        $this->addFilter('event_date', 'events.event_date');

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
            'index' => 'club_name',
            'label' => 'Club Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'event_name',
            'label' => 'Event Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'coupon_code',
            'label' => 'Coupon',
            'type' => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable' => false,
            'closure' => function ($row) {
                if (empty($row->coupon_code)) {
                    return '-';
                }

                return '
                    <div class="flex flex-col items-start gap-1.5 px-3 rounded-lg w-max">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            ' . ($row->coupon_name ?? '-') . '
                        </span>
                        <span class="bg-emerald-50 text-emerald-600 border border-dashed border-emerald-400 px-2.5 py-1 rounded-md font-mono font-bold text-sm tracking-wide">
                            ' . ($row->coupon_code ?? '-') . '
                        </span>
                    </div>';
            }
        ]);

        $this->addColumn([
            'index' => 'event_date',
            'label' => 'Event Date',
            'type' => 'date',
            'filterable' => true,
            'sortable' => true,
        ]);
    }

    public function prepareActions()
    {
        if (hasPermission('admin.events.update')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Event',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }

        if (hasPermission('admin.events.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Event',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.events.delete', $row->id);
                }
            ]);
        }
    }
}
