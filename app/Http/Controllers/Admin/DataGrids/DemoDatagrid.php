<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB; 
use Imperial\DataGrid\DataGrid; 

/** 
 * Demo for datagrid table how table render from backend server
 */
class DemoDatagrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    { 
        $queryBuilder = DB::table('users')
            ->addSelect(
                'users.id',
                'users.name', 
                'users.email',
                'users.phone',
                'users.user_type'
            ); 
             
        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    { 
    // Number column
        $this->addColumn([
            'index' => 'id',
            'label' => 'ID',
            'type' => 'integer',
            'filterable' => true,
            'searchable' => true,
        ]);

    // String Column
        $this->addColumn([
            'index' => 'name',
            'label' => 'Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'email',
            'label' => 'Email',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        // Dropdown filters and customize value into html also using closure
        $this->addColumn([
            'index' => 'status',
            'label' => 'Status',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                [
                    'label' => 'processing',
                    'value' => 'processing',
                ],
                [
                    'label' => 'completed',
                    'value' => 'completed',
                ], 
            ],
            'sortable' => true,
            'closure' => function ($row) {
                switch ($row->status) {
                    case 'processing':
                        return '<p class="label-processing">processing</p>';

                    case 'completed':
                        return '<p class="label-active">completed</p>'; 
                }
            },
        ]);

        // Use closure for modifying value of database
        $this->addColumn([
            'index' => 'method',
            'label' => trans('admin::app.sales.orders.index.datagrid.pay-via'),
            'type' => 'string',
            'closure' => function ($row) { 
                return strtolower($row->method); 
            },
        ]);

        // Add date range filter if column is date related
        $this->addColumn([
            'index' => 'date',
            'label' => 'date',
            'type' => 'date',
            'filterable' => true,
            'filterable_type' => 'date_range',
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
            'icon' => 'icon-edit',
            'title' => 'Edit',
            'method' => 'GET',
            'url' => function ($row) {
                return '';
            },
        ]);

        $this->addAction([
            'icon' => 'icon-delete',
            'title' => 'Delete',
            'method' => 'GET',
            'target' => 'blank',
            'url' => function ($row) {
                return '';
            },
        ]);

        $this->addAction([
            'type' => 'custom',  
            'icon' => 'icon-view',   
            'method' => 'test',
            'url' => function ($row) {
                return '';
            } 
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {   
        // Mass delete record from datagrid
        $this->addMassAction([
            'title' => 'Delete',
            'url' => route('route-name'),
            'method' => 'POST',
        ]);

        // Mass update status from datagrid
        $this->addMassAction([
            'title' => 'Update Status',
            'url' => route('route-name'),
            'method' => 'POST',
            'options' => [
                [
                    'label' => 'pending',
                    'value' => 'pending',
                ],
                [
                    'label' => 'paid',
                    'value' => 'paid',
                ], 
            ],
        ]);
    }
}
