<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class EventDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('events')
            ->join('clubs', 'events.club_id', '=', 'clubs.id')
            ->select('events.id', 'events.name as event_name', 'clubs.name as club_name', 'events.start_time', 'events.end_time', 'events.cover_charge', 'events.capacity');
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
            'index' => 'event_name',
            'label' => 'Event Name',
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
            'index' => 'start_time',
            'label' => 'Starts At',
            'type' => 'datetime',
            'filterable' => true,
            // 'filterable_type' => 'datetime_range',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'start_time',
            'label' => 'Starts At2',
            'type' => 'date',
            'filterable' => true,
            // 'filterable_type' => 'date_range',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'end_time',
            'label' => 'Ends At',
            'type' => 'datetime',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'cover_charge',
            'label' => 'Cover Charge',
            'type' => 'decimal',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'capacity',
            'label' => 'Capacity',
            'type' => 'integer',
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'custom',
            'icon' => 'icon-edit',
            'title' => 'Edit Event',
            'method' => 'edit',
            'url' => function ($row) {
                return '';
            }
        ]);

        $this->addAction([
            'type' => 'custom',
            'icon' => 'icon-delete',
            'title' => 'Delete Event',
            'method' => 'delete',
            'url' => function ($row) {
                return '';
            }
        ]);
    }
}
