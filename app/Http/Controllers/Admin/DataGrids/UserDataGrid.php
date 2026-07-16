<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB; 
use Imperial\DataGrid\DataGrid; 

class UserDataGrid extends DataGrid
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

        $this->addColumn([
            'index' => 'id',
            'label' => 'ID',
            'type' => 'integer',
            'filterable' => true,
        ]);

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

        $this->addColumn([
            'index' => 'phone',
            'label' => 'Phone',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'user_type',
            'label' => 'User Type',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
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
            // 'title' => '', 
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
        
    }
}
