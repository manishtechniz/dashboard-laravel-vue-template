<?php

namespace Imperial\DataGrid\Enums;

enum DateRangeOptionEnum: string
{
    /**
     * Today.
     */
    case TODAY = 'today';

    /**
     * Yesterday.
     */
    case YESTERDAY = 'yesterday';

    /**
     * This week.
     */
    case THIS_WEEK = 'this_week';

    /**
     * This month.
     */
    case THIS_MONTH = 'this_month';

    /**
     * Last month.
     */
    case LAST_MONTH = 'last_month';

    /**
     * Last three months.
     */
    case LAST_THREE_MONTHS = 'last_three_months';

    /**
     * Last six months.
     */
    case LAST_SIX_MONTHS = 'last_six_months';

    /**
     * This year.
     */
    case THIS_YEAR = 'this_year';

    /**
     * Get options.
     */
    public static function options(string $format = 'Y-m-d H:i:s'): array
    {
        return [
            [
                'name' => self::TODAY->value,
                'label' => 'Today',
                'from' => now()->today()->format($format),
                'to' => now()->endOfDay()->format($format),
            ],
            [
                'name' => self::YESTERDAY->value,
                'label' => 'Yesterday',
                'from' => now()->yesterday()->format($format),
                'to' => now()->today()->subSecond(1)->format($format),
            ],  
            [
                'name' => self::THIS_WEEK->value,
                'label' => 'This Week',
                'from' => now()->startOfWeek()->format($format),
                'to' => now()->endOfWeek()->format($format),
            ],
            [
                'name' => self::THIS_MONTH->value,
                'label' => 'This Month',
                'from' => now()->startOfMonth()->format($format),
                'to' => now()->endOfMonth()->format($format),
            ],
            [
                'name' => self::LAST_MONTH->value,
                'label' => 'Last Month',
                'from' => now()->subMonth(1)->startOfMonth()->format($format),
                'to' => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name' => self::LAST_THREE_MONTHS->value,
                'label' => 'Last Three Months',
                'from' => now()->subMonth(3)->startOfMonth()->format($format),
                'to' => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name' => self::LAST_SIX_MONTHS->value,
                'label' => 'Last Six Months',
                'from' => now()->subMonth(6)->startOfMonth()->format($format),
                'to' => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name' => self::THIS_YEAR->value,
                'label' => 'This Year',
                'from' => now()->startOfYear()->format($format),
                'to' => now()->endOfYear()->format($format),
            ],
        ];
    }
}
