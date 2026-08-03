<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class ReviewDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('reviews')
            ->join('clients', 'reviews.client_id', '=', 'clients.id')
            ->leftJoin('clubs', 'reviews.club_id', '=', 'clubs.id')
            ->select(
                'reviews.id',
                'reviews.client_id',
                'reviews.club_id',
                'reviews.booking_id',
                'clients.name as client_name',
                'clubs.name as club_name',
                'reviews.rating as rating_html',
                'reviews.rating',
                'reviews.comment',
                'reviews.created_at',
                'reviews.is_active',
                'reviews.is_anonymous',
                'reviews.remark'
            );

        $this->addFilter('id', 'reviews.id');
        $this->addFilter('client_name', 'clients.name');
        $this->addFilter('club_name', 'clubs.name');
        $this->addFilter('rating', 'reviews.rating');
        $this->addFilter('comment', 'reviews.comment');
        $this->addFilter('is_active', 'reviews.is_active');
        $this->addFilter('is_anonymous', 'reviews.is_anonymous');

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
            'index' => 'club_name',
            'label' => 'Club Name',
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'rating_html',
            'label' => 'Rating',
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return str_repeat('★', $row->rating) . str_repeat('☆', 5 - $row->rating);
            },
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => 'Comment',
            'type' => 'string',
            'searchable' => true,
        ]);

        $this->addColumn([
            'index' => 'remark',
            'label' => 'Remark',
            'type' => 'string',
            'searchable' => true,
        ]);

        $this->addColumn([
            'index' => 'is_anonymous',
            'label' => 'Anonymous',
            'type' => 'boolean',
            'filterable' => true,
            'closure' => function ($row) {
                return $row->is_anonymous
                    ? '<span class="label-active">Yes</span>'
                    : '<span class="label-inactive">No</span>';
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
        if (hasPermission('admin.reviews.update')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Review',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }
        if (hasPermission('admin.reviews.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Review',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.reviews.delete', $row->id);
                },
            ]);
        }
    }
    public function prepareMassActions()
    {
        if (hasPermission('admin.reviews.mass-delete')) {
            $this->addMassAction([
                'title' => 'Delete Reviews',
                'method' => 'POST',
                'url' => route('admin.reviews.mass_delete'),
                'confirm' => true,
            ]);
        }
        if (hasPermission('admin.reviews.mass-update')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'method' => 'POST',
                'url' => route('admin.reviews.mass_update'),
                'options' => [
                    ['label' => 'Active', 'value' => 1],
                    ['label' => 'Inactive', 'value' => 0],
                ],
            ]);
        }
    }
}
