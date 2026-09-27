<?php

namespace App\Http\Controllers\Admin\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imperial\DataGrid\DataGrid;

class TestimonialDataGrid extends DataGrid
{
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('testimonials')
            ->join('clients', 'testimonials.client_id', '=', 'clients.id')
            ->leftJoin('clubs', 'testimonials.club_id', '=', 'clubs.id')
            ->select(
                'testimonials.id',
                'testimonials.client_id',
                'testimonials.club_id',
                'clients.name as client_name',
                'clubs.name as club_name',
                'testimonials.rating as rating_html',
                'testimonials.rating',
                'testimonials.title',
                'testimonials.comment',
                'testimonials.created_at',
                'testimonials.is_published'
            );

        $this->addFilter('id', 'testimonials.id');
        $this->addFilter('client_name', 'clients.name');
        $this->addFilter('club_name', 'clubs.name');
        $this->addFilter('rating_html', 'testimonials.rating');
        $this->addFilter('rating', 'testimonials.rating');
        $this->addFilter('title', 'testimonials.title');
        $this->addFilter('comment', 'testimonials.comment');
        $this->addFilter('is_published', 'testimonials.is_published');

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
            'index' => 'title',
            'label' => 'Title',
            'type' => 'string',
            'searchable' => true,
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => 'Comment',
            'type' => 'string',
            'searchable' => true,
        ]);

        $this->addColumn([
            'index' => 'is_published',
            'label' => 'Status',
            'type' => 'boolean',
            'filterable' => true,
            'closure' => function ($row) {
                return $row->is_published
                    ? '<span class="label-active">Published</span>'
                    : '<span class="label-inactive">Draft</span>';
            },
        ]);
    }

    public function prepareActions()
    {
        if (hasPermission('admin.testimonials.update')) {
            $this->addAction([
                'type' => 'custom',
                'icon' => 'icon-edit',
                'title' => 'Edit Testimonial',
                'method' => 'edit',
                'url' => function ($row) {
                    return '';
                }
            ]);
        }
        if (hasPermission('admin.testimonials.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'Delete Testimonial',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.testimonials.delete', $row->id);
                },
            ]);
        }
    }
    
    public function prepareMassActions()
    {
        if (hasPermission('admin.testimonials.mass-delete')) {
            $this->addMassAction([
                'title' => 'Delete Testimonials',
                'method' => 'POST',
                'url' => route('admin.testimonials.mass_delete'),
                'confirm' => true,
            ]);
        }
        if (hasPermission('admin.testimonials.mass-update')) {
            $this->addMassAction([
                'title' => 'Update Status',
                'method' => 'POST',
                'url' => route('admin.testimonials.mass_update'),
                'options' => [
                    ['label' => 'Published', 'value' => 1],
                    ['label' => 'Draft', 'value' => 0],
                ],
            ]);
        }
    }
}
