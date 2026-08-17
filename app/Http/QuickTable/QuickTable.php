<?php

namespace App\Http\QuickTable;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class QuickTable
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'id';

    /**
     * Default sort column of datagrid.
     *
     * @var ?string
     */
    protected $sortColumn;

    /**
     * Default sort order of datagrid.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Default items per page.
     *
     * @var int
     */
    protected $itemsPerPage = 10;

    /**
     * Per page options.
     *
     * @var array
     */
    protected $perPageOptions = [10, 20, 30, 40, 50];

    /**
     * Columns.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Actions.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Paginator instance.
     */
    protected LengthAwarePaginator $paginator;

    /**
     * Query builder instance.
     *
     * @var object
     */
    protected $queryBuilder;

    protected $record = [];

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    abstract protected function prepareQueryBuilder();

    abstract protected function prepareColumns();

    protected function addColumn(array $column)
    {
        $this->columns[] = $column;
    }

    protected function getColumns(array $column)
    {
        return $this->columns;
    }

    protected function addAction(array $action)
    {
        $this->actions[] = $action;
    }

    protected function getActions(array $action)
    {
        return $this->actions;
    }

    /**
     * Get primary column.
     */
    public function getPrimaryColumn(): string
    {
        return $this->primaryColumn;
    }

    /**
     * Set sort column.
     */
    public function setSortColumn(string $sortColumn): void
    {
        $this->sortColumn = $sortColumn;
    }

    /**
     * Get sort column.
     */
    public function getSortColumn(): ?string
    {
        return $this->sortColumn;
    }

    /**
     * Set sort order.
     */
    public function setSortOrder(string $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Get sort order.
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    /**
     * Set items per page.
     */
    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * Get items per page.
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Set per page options.
     */
    public function setPerPageOptions(array $perPageOptions): void
    {
        $this->perPageOptions = $perPageOptions;
    }

    /**
     * Get per page options.
     */
    public function getPerPageOptions(): array
    {
        return $this->perPageOptions;
    }

    /**
     * Set query builder.
     *
     * @param  mixed  $queryBuilder
     */
    public function setQueryBuilder($queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Get query builder.
     */
    public function getQueryBuilder(): mixed
    {
        return $this->queryBuilder;
    }

    /**
     * Validated request.
     */
    protected function validatedRequest(): array
    {
        request()->validate([
            'sort' => ['sometimes', 'required', 'array'],
            'pagination' => ['sometimes', 'required', 'array'],
        ]);

        return request()->only(['filters', 'sort', 'pagination', 'export', 'format']);
    }

    /**
     * Process requested sorting.
     */
    protected function processRequestedSorting($requestedSort)
    {

        if (! $this->sortColumn) {
            $this->sortColumn = $this->primaryColumn;
        }

        $sortColumn = $this->sortColumn;

        if (isset($requestedSort['column'])) {
            $column = collect($this->columns)
                ->first(fn ($column) => $column->getIndex() === $requestedSort['column'] && $column->getSortable());

            if ($column) {
                $sortColumn = $column->getColumnName();
            }
        }

        $sortOrder = isset($requestedSort['order']) && in_array(strtolower($requestedSort['order']), ['asc', 'desc'])
            ? $requestedSort['order']
            : $this->sortOrder;

        $this->queryBuilder->orderBy($sortColumn, $sortOrder);

    }

    /**
     * Process requested pagination.
     */
    protected function processRequestedPagination(array $requestedPagination): void
    {
        $this->paginator = $this->queryBuilder->paginate(
            $requestedPagination['per_page'] ?? $this->itemsPerPage,
            ['*'],
            'page',
            $requestedPagination['page'] ?? 1
        );
    }


    /**
     * Process request.
     */
    protected function process(): void
    {
        $requestedParams = $this->validatedRequest();

        $this->processRequestedSorting($requestedParams['sort'] ?? []);

        $this->processRequestedPagination($requestedParams['pagination'] ?? []);

        // return response()->json($this->formatData());
    }

}
