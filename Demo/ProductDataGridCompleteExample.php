<?php

namespace Webkul\Admin\DataGrids\Catalog;

use Illuminate\Database\Query\Builder; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Imperial\DataGrid\DataGrid; 

class ProductDataGridCompleteExample extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'product_id';

    /**
     * Constructor for the class.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    { 
        /**
         * Query Builder to fetch records from `product_flat` table
         */
        $queryBuilder = DB::table('product_flat')
            ->distinct()
            ->leftJoin('attribute_families as af', 'product_flat.attribute_family_id', '=', 'af.id')
            ->leftJoin('product_inventories', 'product_flat.product_id', '=', 'product_inventories.product_id')
            ->leftJoin('product_images', 'product_flat.product_id', '=', 'product_images.product_id')
            ->leftJoin('product_categories as pc', 'product_flat.product_id', '=', 'pc.product_id')
            ->leftJoin('category_translations as ct', function ($leftJoin) {
                $leftJoin->on('pc.category_id', '=', 'ct.category_id');
            })
            ->select(
                'product_flat.locale',
                'product_flat.channel',
                'product_images.path as base_image',
                'pc.category_id',
                'ct.name as category_name',
                'product_flat.product_id',
                'product_flat.sku',
                'product_flat.name',
                'product_flat.type',
                'product_flat.status',
                'product_flat.price',
                'product_flat.url_key',
                'product_flat.visible_individually',
                'af.name as attribute_family',
            )
            ->addSelect(DB::raw('SUM(DISTINCT product_inventories.qty) as quantity'))
            ->addSelect(DB::raw('COUNT(DISTINCT product_images.id) as images_count'))
            ->where('product_flat.locale', app()->getLocale())
            ->groupBy('product_flat.product_id');

        /**
         * Add filter if have multiple join table data and we can determine filter for columns.
         */
        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('channel', 'product_flat.channel');
        $this->addFilter('locale', 'product_flat.locale');
        $this->addFilter('name', 'product_flat.name');
        $this->addFilter('type', 'product_flat.type');
        $this->addFilter('status', 'product_flat.status');
        $this->addFilter('attribute_family', 'af.id');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     *
     * @return void
     */
    public function prepareColumns()
    { 

        $this->addColumn([
            'index' => 'name',
            'label' => 'name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'sku',
            'label' => 'sku',
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'attribute_family',
            'label' => 'attribute_family',
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [],
        ]);

        $this->addColumn([
            'index' => 'base_image',
            'label' => trans('admin::app.catalog.products.index.datagrid.image'),
            'type' => 'string',
            'exportable' => false,
            'closure' => function ($row) {
                if (! $row->base_image) {
                    return;
                }

                return Storage::url($row->base_image);
            },
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => 'price',
            'type' => 'decimal',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => 'qty',
            'type' => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'product_id',
            'label' => 'id',
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => 'status',
            'type' => 'boolean',
            'filterable' => true,
            'filterable_options' => [
                [
                    'label' => 'active',
                    'value' => 1,
                ],
                [
                    'label' => 'disable',
                    'value' => 0,
                ],
            ],
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'category_name',
            'label' => 'category',
            'type' => 'string',
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => 'type',
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => collect(config('product_types'))
                ->map(fn ($type) => ['label' => trans($type['name']), 'value' => $type['key']])
                ->values()
                ->toArray(),
            'sortable' => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'icon' => 'icon-copy',
            'title' => 'copy',
            'method' => 'POST',
            'url' => function ($row) {
                return route('route-name', $row->product_id);
            },
        ]);

        $this->addAction([
            'icon' => 'icon-sort-right',
            'title' => 'edit',
            'method' => 'GET',
            'url' => function ($row) {
                $filteredChannel = request()->input('filters.channel')[0] ?? null;

                return route('route-name', [
                    'id' => $row->product_id,
                    'channel' => $filteredChannel,
                ]);
            },
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
            $this->addMassAction([
                'title' => 'delete',
                'url' => route('admin.catalog.products.mass_delete'),
                'method' => 'POST',
            ]);

            $this->addMassAction([
                'title' => 'update-status',
                'url' => route('route-name'),
                'method' => 'POST',
                'options' => [
                    [
                        'label' => 'active',
                        'value' => 1,
                    ],
                    [
                        'label' => 'disable',
                        'value' => 0,
                    ],
                ],
            ]);
    } 
 
  
}
