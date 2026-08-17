<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class PaymentDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('payments')
            ->leftJoin('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->leftJoin('clients', 'payments.client_id', '=', 'clients.id')
            ->select('payments.id', 'clients.name as client_name', 'payments.payment_type', 'payments.amount', 'payments.payment_method', 'payments.status', 'payments.created_at');

        $this->addFilter('id', 'payments.id');
        $this->addFilter('client_name', 'clients.name');
        $this->addFilter('payment_type', 'payments.payment_type');
        $this->addFilter('amount', 'payments.amount');
        $this->addFilter('payment_method', 'payments.payment_method');
        $this->addFilter('status', 'payments.status');
        $this->addFilter('created_at', 'payments.created_at');

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
            'index' => 'payment_type',
            'label' => 'Type',
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return ucfirst(str_replace('_', ' ', $row->payment_type));
            }
        ]);

        $this->addColumn([
            'index' => 'amount',
            'label' => 'Amount',
            'type' => 'decimal',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'payment_method',
            'label' => 'Method',
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => 'Status',
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => 'Pending', 'value' => 'pending'],
                ['label' => 'Completed', 'value' => 'completed'],
                ['label' => 'Failed', 'value' => 'failed'],
                ['label' => 'Refunded', 'value' => 'refunded'],
            ],
            'closure' => function ($row) {
                switch ($row->status) {
                    case 'completed':
                        return '<span class="label-active">Completed</span>';
                    case 'pending':
                        return '<span class="label-pending">Pending</span>';
                    case 'failed':
                        return '<span class="label-inactive">Failed</span>';
                    case 'refunded':
                        return '<span class="label-processing">Refunded</span>';
                    default:
                        return $row->status;
                }
            },
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => 'Date',
            'type' => 'datetime',
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'sortable' => true,
        ]);
    }

    public function prepareActions()
    {
        // $this->addAction([
        //     'type' => 'custom',
        //     'icon' => 'icon-edit',
        //     'title' => 'Edit Payment',
        //     'method' => 'edit',
        //     'url' => function ($row) {
        //         return '';
        //     }
        // ]);

        $this->addAction([
            'type' => 'custom',
            'icon' => 'icon-list',
            'title' => 'View Transactions',
            'method' => 'transactions',
            'url' => function ($row) {
                return '';
            }
        ]);
    }
    public function prepareMassActions()
    {
        if (hasPermission('admin.payments.mass-delete')) {
            $this->addMassAction([
                'title' => 'Delete Payments',
                'method' => 'POST',
                'url' => route('admin.payments.mass_delete'),
                'confirm' => true,
            ]);
        }
        if (hasPermission('admin.payments.mass-update')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'method' => 'POST',
                'url' => route('admin.payments.mass_update'),
                'options' => [
                    ['label' => 'Pending', 'value' => 'pending'],
                    ['label' => 'Completed', 'value' => 'completed'],
                    ['label' => 'Failed', 'value' => 'failed'],
                    ['label' => 'Refunded', 'value' => 'refunded'],
                ],
            ]);
        }
    }
}
