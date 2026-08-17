<?php
namespace App\Http\QuickTable;

use Illuminate\Support\Facades\DB;

class TestQuickTable extends QuickTable
{
    public function prepareQueryBuilder()
    {
        return DB::table('users')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.created_at',
                'category_translations.locale',
            );
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => "ID",
            'type' => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon' => 'icon-edit',
            'title' => trans('admin::app.catalog.attributes.index.datagrid.edit'),
            'method' => 'GET',
            'url' => function ($row) {
                return route('admin.catalog.attributes.edit', $row->id);
            },
        ]);
    }
}
