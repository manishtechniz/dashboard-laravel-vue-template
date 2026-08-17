<?php

namespace Imperial\DataGrid\ColumnTypes;

use Imperial\DataGrid\Column;
use Imperial\DataGrid\Enums\FilterTypeEnum;
use Imperial\DataGrid\Exceptions\InvalidColumnException;
use Imperial\DataGrid\Exceptions\InvalidColumnExpressionException;

class Boolean extends Column
{
    /**
     * Set filterable type.
     */
    public function setFilterableType(?string $filterableType): void
    {
        if (
            $filterableType
            && ($filterableType !== FilterTypeEnum::DROPDOWN->value)
        ) {
            throw new InvalidColumnException('Boolean filters will only work with `dropdown` type. Either remove the `filterable_type` or set it to `dropdown`.');
        }

        if (! $filterableType) {
            $filterableType = FilterTypeEnum::DROPDOWN->value;
        }

        parent::setFilterableType($filterableType);
    }

    /**
     * Set filterable options.
     */
    public function setFilterableOptions(mixed $filterableOptions): void
    {
        if (empty($filterableOptions)) {
            $filterableOptions = [
                [
                    'label' => 'TRUE',
                    'value' => 1,
                ],
                [
                    'label' => 'FALSE',
                    'value' => 0,
                ],
            ];
        }

        parent::setFilterableOptions($filterableOptions);
    }

    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues): mixed
    {
        return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
            if (is_string($requestedValues)) {
                $scopeQueryBuilder->orWhere($this->columnName, $requestedValues);
            } elseif (is_array($requestedValues)) {
                foreach ($requestedValues as $value) {
                    $scopeQueryBuilder->orWhere($this->columnName, $value);
                }
            } else {
                throw new InvalidColumnExpressionException('Only string and array are allowed for boolean column type.');
            }
        });
    }
}
