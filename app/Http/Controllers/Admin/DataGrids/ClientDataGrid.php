<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class ClientDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder =  DB::table('clients')
            ->leftJoin('client_balances', 'clients.id', '=', 'client_balances.client_id')
            ->leftJoin('mobile_app_roles', 'clients.role_id', '=', 'mobile_app_roles.id')
            ->select(
                'clients.id',
                'clients.name',
                'clients.email',
                'clients.phone',
                'clients.is_active',
                'clients.is_active as html_is_active',
                'clients.created_at',
                'clients.role_id',
                'mobile_app_roles.name as role_name',
                DB::raw('COALESCE(client_balances.total_due, 0) as total_due'),
                DB::raw('COALESCE(client_balances.total_advance, 0) as total_advance')
            );

        $this->addFilter('name', 'clients.name');
        $this->addFilter('email', 'clients.email');
        $this->addFilter('phone', 'clients.phone');
        $this->addFilter('is_active', 'clients.is_active');
        $this->addFilter('html_is_active', 'clients.is_active');
        $this->addFilter('role_name', 'mobile_app_roles.name');
        $this->addFilter('total_due', 'client_balances.total_due');
        $this->addFilter('total_advance', 'client_balances.total_advance');
        $this->addFilter('id', 'clients.id');

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
                return $row->role_name ? '<span class="badge badge-info">' . e($row->role_name) . '</span>' : '<span class="text-muted">N/A</span>';
            },
        ]);

        $this->addColumn([
            'index' => 'html_is_active',
            'label' => 'Status',
            'type' => 'boolean',
            'filterable' => true,
            'closure' => function ($row) {
                return $row->is_active
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-danger">Inactive</span>';
            },
        ]);

        $this->addColumn([
            'index' => 'total_due',
            'label' => 'Total Due',
            'type' => 'decimal',
            'sortable' => true,
            'closure' => function ($row) {
                return $row->total_due > 0 ? '<span class="label-inactive">₹' . number_format($row->total_due, 2) . '</span>' : '₹0.00';
            },
        ]);

        $this->addColumn([
            'index' => 'total_advance',
            'label' => 'Advance',
            'type' => 'decimal',
            'sortable' => true,
            'closure' => function ($row) {
                return $row->total_advance > 0 ? '<span class="label-active">₹' . number_format($row->total_advance, 2) . '</span>' : '₹0.00';
            },
        ]);
    }

    public function prepareActions()
    {
        if (hasPermission('admin.dashboard.index')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'd-pi pi pi-chart-line',
                'title' => 'See Analytics',
                'method' => 'seeAnalytics',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }

        $this->addAction([
            'type' => 'custom',
            'icon' => 'd-pi pi pi-eye',
            'title' => 'View Ledger',
            'method' => 'viewLedger',
            'url' => function ($row) {
                return '';
            }
        ]);

        if (hasPermission('admin.clients.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Client',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.clients.delete', $row->id);
                }
            ]);
        }

        if (hasPermission('admin.clients.update')) {
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
    }
    public function prepareMassActions()
    {
        if (hasPermission('admin.clients.mass-delete')) {
            $this->addMassAction([
                'title' => 'Delete Clients',
                'method' => 'POST',
                'url' => route('admin.clients.mass_delete'),
                'confirm' => true,
            ]);
        }
        if (hasPermission('admin.clients.mass-update')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'method' => 'POST',
                'url' => route('admin.clients.mass_update'),
                'options' => [
                    ['label' => 'Active', 'value' => 1],
                    ['label' => 'Inactive', 'value' => 0],
                ],
            ]);
        }
    }
}
