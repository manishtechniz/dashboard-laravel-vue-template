<?php

namespace App\Http\Controllers\Admin\DataGrids;

use App\Traits\ResolvesImageUrls;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class TableDataGrid extends DataGrid
{
    use ResolvesImageUrls;

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('tables')
            ->join('clubs', 'tables.club_id', '=', 'clubs.id')
            ->select('tables.*', 'clubs.name as club_name', 'tables.status as status_html', 'tables.name as table_name', 'tables.price as price_html', 'tables.cover_charge as html_cover_charge');

        $this->addFilter('table_name', 'tables.name');

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
            'index' => 'image',
            'label' => 'Image',
            'type' => 'string',
            'filterable' => false,
            'sortable' => false,
            'closure' => function ($row) {
                // dd($row);
                // return '';
                return '<img onerror="this.src=\'' . previewImageURL() . '\';" src="' . $this->getImageUrl($row->image) . '" class="w-10 h-10 object-cover rounded-md" alt="Table Image" />';
                // return '<span class="text-xs text-gray-400">No Image</span>';
            },
        ]);

        $this->addColumn([
            'index' => 'table_name',
            'label' => 'Table Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'label',
            'label' => 'Price Label',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'price_html',
            'label' => 'Min. Price',
            'type' => 'decimal',
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return '₹' . ' ' . number_format($row->price, 2);
            }
        ]);

        $this->addColumn([
            'index' => 'html_cover_charge',
            'label' => 'Cover Charge',
            'type' => 'decimal',
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return '₹' . ' ' . number_format($row->cover_charge, 2);
            }
        ]);

        $this->addColumn([
            'index' => 'club_name',
            'label' => 'Club',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'capacity',
            'label' => 'Capacity (Seats)',
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'total_tables',
            'label' => 'Total Tables',
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
                ['label' => 'Active', 'value' => 'active'],
                ['label' => 'Inactive', 'value' => 'inactive'],
            ],
            'closure' => function ($row) {
                if ($row->status === 'active') {
                    return '<span class="label-active">Active</span>';
                }
                return '<span class="label-inactive">Inactive</span>';
            },
        ]);
    }

    public function prepareActions()
    {
        if (hasPermission('admin.tables.update')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Table',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }

        if (hasPermission('admin.tables.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Table',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.tables.delete', $row->id);
                }
            ]);
        }
    }
}
