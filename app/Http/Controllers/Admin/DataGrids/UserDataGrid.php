<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class UserDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder =  DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.role_id', 'roles.name as role_name', 'users.is_active', 'users.created_at');

        $this->addFilter('name', 'users.name');
        $this->addFilter('role_name', 'roles.name');

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
            'index' => 'role_name',
            'label' => 'Role',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return $row->role_name ?? '-';
            },
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
        if (hasPermission('admin.users.update')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Client',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }

        if (hasPermission('admin.users.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Client',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.users.delete', $row->id);
                }
            ]);
        }
    }
    public function prepareMassActions()
    {
        if (hasPermission('admin.users.mass-delete')) {
            $this->addMassAction([
                'title' => 'Delete Users',
                'method' => 'POST',
                'url' => route('admin.users.mass_delete'),
                'confirm' => true,
            ]);
        }
        if (hasPermission('admin.users.mass-update')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'method' => 'POST',
                'url' => route('admin.users.mass_update'),
                'options' => [
                    ['label' => 'Active', 'value' => 1],
                    ['label' => 'Inactive', 'value' => 0],
                ],
            ]);
        }
    }
}
