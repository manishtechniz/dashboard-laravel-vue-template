<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class PromoCodeDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        return DB::table('promo_codes')
            ->select('id', 'code', 'type', 'value', 'start_date', 'end_date', 'usage_limit', 'used_count', 'is_active');
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
            'index' => 'code',
            'label' => 'Code',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => 'Type',
            'type' => 'string',
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'value',
            'label' => 'Discount Value',
            'type' => 'decimal',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'used_count',
            'label' => 'Used',
            'type' => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'usage_limit',
            'label' => 'Limit',
            'type' => 'integer',
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
            'title' => 'Edit Promo Code',
            'method' => 'edit',
            'url' => function ($row) {
                return '';
            }
        ]);

        $this->addAction([
            'type' => 'custom',
            'icon' => 'icon-delete',
            'title' => 'Delete Promo Code',
            'method' => 'delete',
            'url' => function ($row) {
                return '';
            }
        ]);
    }
}
