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
            ->join('clubs', 'reviews.club_id', '=', 'clubs.id')
            ->select('reviews.id', 'clients.name as client_name', 'clubs.name as club_name', 'reviews.rating', 'reviews.comment', 'reviews.created_at');

        $this->addFilter('id', 'reviews.id');
        $this->addFilter('client_name', 'clients.name');
        $this->addFilter('club_name', 'clubs.name');
        $this->addFilter('rating', 'reviews.rating');
        $this->addFilter('comment', 'reviews.comment');

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
            'index' => 'rating',
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
    }

    public function prepareActions()
    {
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
