<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class FeatureRequestDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('feature_requests')
            ->leftJoin('clients', 'feature_requests.client_id', '=', 'clients.id')
            ->select(
                'feature_requests.id',
                'feature_requests.client_id',
                'clients.name as client_name',
                'feature_requests.title',
                'feature_requests.description',
                'feature_requests.status',
                'feature_requests.priority',
                'feature_requests.created_at'
            );

        $this->addFilter('id', 'feature_requests.id');
        $this->addFilter('client_name', 'clients.name');
        $this->addFilter('title', 'feature_requests.title');
        $this->addFilter('status', 'feature_requests.status');
        $this->addFilter('priority', 'feature_requests.priority');

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
            'index' => 'client_name',
            'label' => 'Client',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'title',
            'label' => 'Title',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => 'Status',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'priority',
            'label' => 'Priority',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);
    }

    public function prepareActions()
    {
        if (hasPermission('admin.feature_requests.update')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Feature Request',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }
        if (hasPermission('admin.feature_requests.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Feature Request',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.feature_requests.delete', $row->id);
                },
            ]);
        }
    }
    public function prepareMassActions()
    {
        if (hasPermission('admin.feature_requests.mass-delete')) {
            $this->addMassAction([
                'title' => 'Delete Requests',
                'method' => 'POST',
                'url' => route('admin.feature_requests.mass_delete'),
                'confirm' => true,
            ]);
        }
        if (hasPermission('admin.feature_requests.mass-update')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'method' => 'POST',
                'url' => route('admin.feature_requests.mass_update'),
                'options' => [
                    ['label' => 'Pending', 'value' => 'pending'],
                    ['label' => 'Reviewing', 'value' => 'reviewing'],
                    ['label' => 'Planned', 'value' => 'planned'],
                    ['label' => 'In Progress', 'value' => 'in_progress'],
                    ['label' => 'Completed', 'value' => 'completed'],
                    ['label' => 'Rejected', 'value' => 'rejected'],
                ],
            ]);
        }
    }
}
